<?php

namespace App;

class GifTransformer
{
    private string $inputPath;
    private string $outputPath;

    public function __construct(string $inputPath)
    {
        $this->inputPath = $inputPath;
        $this->outputPath = $this->generateOutputPath();
    }

    private function generateOutputPath(): string
    {
        $tempDir = getenv('TMP_DIR') ?: '/var/www/html/tmp';
        return $tempDir . '/' . uniqid('gif_') . '.gif';
    }

    public function resize(int $width, int $height, bool $keepAspectRatio = true): ?string
    {
        $geometry = $keepAspectRatio ? "{$width}x{$height}" : "{$width}x{$height}!";

        $command = sprintf(
            'convert %s -coalesce -resize %s -layers optimize %s 2>&1',
            escapeshellarg($this->inputPath),
            escapeshellarg($geometry),
            escapeshellarg($this->outputPath)
        );

        return $this->execute($command);
    }

    public function crop(int $x, int $y, int $width, int $height): ?string
    {
        $geometry = "{$width}x{$height}+{$x}+{$y}";

        $command = sprintf(
            'convert %s -coalesce -crop %s +repage -layers optimize %s 2>&1',
            escapeshellarg($this->inputPath),
            escapeshellarg($geometry),
            escapeshellarg($this->outputPath)
        );

        return $this->execute($command);
    }

    public function rotate(float $degrees): ?string
    {
        $command = sprintf(
            'convert %s -coalesce -rotate %s -layers optimize %s 2>&1',
            escapeshellarg($this->inputPath),
            escapeshellarg((string) $degrees),
            escapeshellarg($this->outputPath)
        );

        return $this->execute($command);
    }

    public function flip(string $direction): ?string
    {
        $flipOption = $direction === 'horizontal' ? '-flop' : '-flip';

        $command = sprintf(
            'convert %s -coalesce %s -layers optimize %s 2>&1',
            escapeshellarg($this->inputPath),
            $flipOption,
            escapeshellarg($this->outputPath)
        );

        return $this->execute($command);
    }

    private function execute(string $command): ?string
    {
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            error_log("ImageMagick error: " . implode("\n", $output));
            return null;
        }

        if (!file_exists($this->outputPath)) {
            error_log("Output file not created: " . $this->outputPath);
            return null;
        }

        return $this->outputPath;
    }

    public static function validate(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType === 'image/gif';
    }

    public static function getInfo(string $filePath): ?array
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $command = sprintf(
            'identify -format "%%w %%h %%n" %s 2>&1',
            escapeshellarg($filePath . '[0]')
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        $parts = explode(' ', $output[0]);
        if (count($parts) < 3) {
            return null;
        }

        return [
            'width' => (int) $parts[0],
            'height' => (int) $parts[1],
            'frames' => (int) $parts[2],
            'size' => filesize($filePath),
        ];
    }
}
