<?php
/**
 * ImageMagick PHP Extension wrapper class for image transformations
 * Uses the Imagick PHP extension instead of command-line ImageMagick
 */

class JimageMagick {
    /**
     * Resize an image
     * 
     * @param string $input_path Path to input image
     * @param string $output_path Path to output image
     * @param array $params Parameters: ['width' => int, 'height' => int]
     * @return bool True on success, false on failure
     * @throws Exception
     */
    public static function resize($input_path, $output_path, $params) {
        try {
            $width = intval($params['width'] ?? 0);
            $height = intval($params['height'] ?? 0);
            
            if ($width <= 0 || $height <= 0) {
                throw new Exception('Invalid width or height for resize operation');
            }
            
            $imagick = new Imagick($input_path);
            $imagick = $imagick->coalesceImages();
            do {
                $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
            } while ($imagick->nextImage());
            $imagick->writeImages($output_path, true);
            $imagick->destroy();
            
            return true;
        } catch (ImagickException $e) {
            throw new Exception('Imagick resize failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Crop an image
     * 
     * @param string $input_path Path to input image
     * @param string $output_path Path to output image
     * @param array $params Parameters: ['width' => int, 'height' => int, 'x' => int, 'y' => int]
     * @return bool True on success, false on failure
     * @throws Exception
     */
    public static function crop($input_path, $output_path, $params) {
        try {
            $width = intval($params['width'] ?? 0);
            $height = intval($params['height'] ?? 0);
            $x = intval($params['x'] ?? 0);
            $y = intval($params['y'] ?? 0);
            
            if ($width <= 0 || $height <= 0) {
                throw new Exception('Invalid width or height for crop operation');
            }
            
            $imagick = new Imagick($input_path);
            $imagick = $imagick->coalesceImages();
            do {
                $imagick->cropImage($width, $height, $x, $y);
            } while ($imagick->nextImage());
            $imagick->writeImages($output_path, true);
            $imagick->destroy();
            
            return true;
        } catch (ImagickException $e) {
            throw new Exception('Imagick crop failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Rotate an image
     * 
     * @param string $input_path Path to input image
     * @param string $output_path Path to output image
     * @param array $params Parameters: ['angle' => float]
     * @return bool True on success, false on failure
     * @throws Exception
     */
    public static function rotate($input_path, $output_path, $params) {
        try {
            $angle = floatval($params['angle'] ?? 0);
            
            if ($angle == 0) {
                throw new Exception('Invalid angle for rotate operation');
            }
            
            $imagick = new Imagick($input_path);
            $imagick = $imagick->coalesceImages();
            do {
                $imagick->rotateImage(new ImagickPixel('none'), -$angle);
            } while ($imagick->nextImage());
            $imagick->writeImages($output_path, true);
            $imagick->destroy();
            
            return true;
        } catch (ImagickException $e) {
            throw new Exception('Imagick rotate failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Flip an image (vertical or horizontal)
     * 
     * @param string $input_path Path to input image
     * @param string $output_path Path to output image
     * @param array $params Parameters: ['direction' => 'vertical' | 'horizontal']
     * @return bool True on success, false on failure
     * @throws Exception
     */
    public static function flip($input_path, $output_path, $params) {
        try {
            $direction = strtolower($params['direction'] ?? 'vertical');
            
            $imagick = new Imagick($input_path);
            $imagick = $imagick->coalesceImages();
            
            if ($direction === 'vertical') {
                do {
                    $imagick->flipImage();
                } while ($imagick->nextImage());
            } elseif ($direction === 'horizontal') {
                do {
                    $imagick->flopImage();
                } while ($imagick->nextImage());
            } else {
                throw new Exception('Invalid flip direction. Use "vertical" or "horizontal"');
            }
            
            $imagick->writeImages($output_path, true);
            $imagick->destroy();
            
            return true;
        } catch (ImagickException $e) {
            throw new Exception('Imagick flip failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute a transformation action
     * 
     * @param string $action The transformation action: resize, crop, rotate, flip
     * @param string $input_path Path to input image
     * @param string $output_path Path to output image
     * @param array $params Action parameters
     * @return bool True on success, false on failure
     * @throws Exception
     */
    public static function execute($action, $input_path, $output_path, $params) {
        // Validate input file exists
        if (!file_exists($input_path)) {
            throw new Exception('Input file does not exist: ' . $input_path);
        }
        
        // Ensure output directory exists
        $output_dir = dirname($output_path);
        if (!is_dir($output_dir)) {
            mkdir($output_dir, 0777, true);
        }
        
        switch ($action) {
            case 'resize':
                return self::resize($input_path, $output_path, $params);
            case 'crop':
                return self::crop($input_path, $output_path, $params);
            case 'rotate':
                return self::rotate($input_path, $output_path, $params);
            case 'flip':
                return self::flip($input_path, $output_path, $params);
            default:
                throw new Exception('Unknown transformation action: ' . $action);
        }
    }
}
