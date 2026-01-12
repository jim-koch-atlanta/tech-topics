<?php

namespace App;

use Aws\S3\S3Client as AwsS3Client;
use Aws\Exception\AwsException;

class S3Client
{
    private AwsS3Client $client;
    private string $bucket;

    public function __construct()
    {
        $this->bucket = getenv('S3_BUCKET') ?: 'gif-transformer';

        $config = [
            'version' => 'latest',
            'region' => getenv('AWS_DEFAULT_REGION') ?: 'us-east-1',
            'credentials' => [
                'key' => getenv('AWS_ACCESS_KEY_ID') ?: 'test',
                'secret' => getenv('AWS_SECRET_ACCESS_KEY') ?: 'test',
            ],
        ];

        $endpoint = getenv('S3_ENDPOINT');
        if ($endpoint) {
            $config['endpoint'] = $endpoint;
            $config['use_path_style_endpoint'] = getenv('S3_USE_PATH_STYLE') === 'true';
        }

        $this->client = new AwsS3Client($config);
    }

    public function upload(string $filePath, string $key): bool
    {
        try {
            $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $filePath,
                'ContentType' => 'image/gif',
            ]);
            return true;
        } catch (AwsException $e) {
            error_log("S3 upload error: " . $e->getMessage());
            return false;
        }
    }

    public function download(string $key): ?string
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return (string) $result['Body'];
        } catch (AwsException $e) {
            error_log("S3 download error: " . $e->getMessage());
            return null;
        }
    }

    public function list(): array
    {
        try {
            $result = $this->client->listObjects([
                'Bucket' => $this->bucket,
            ]);

            $files = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $object) {
                    $files[] = [
                        'key' => $object['Key'],
                        'size' => $object['Size'],
                        'lastModified' => $object['LastModified']->format('Y-m-d H:i:s'),
                        'url' => $this->getPublicUrl($object['Key']),
                    ];
                }
            }
            return $files;
        } catch (AwsException $e) {
            error_log("S3 list error: " . $e->getMessage());
            return [];
        }
    }

    public function delete(string $key): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);
            return true;
        } catch (AwsException $e) {
            error_log("S3 delete error: " . $e->getMessage());
            return false;
        }
    }

    public function getPublicUrl(string $key): string
    {
        $endpoint = getenv('S3_ENDPOINT');
        if ($endpoint) {
            return rtrim($endpoint, '/') . '/' . $this->bucket . '/' . $key;
        }
        return "https://{$this->bucket}.s3.amazonaws.com/{$key}";
    }
}
