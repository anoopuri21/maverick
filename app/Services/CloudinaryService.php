<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey    = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        // Validate credentials
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException(
                'Cloudinary credentials are missing. Please check your .env file for: ' .
                'CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET. ' .
                'After adding them, run: php artisan config:clear'
            );
        }

        // Pass configuration directly to Cloudinary constructor
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    /**
     * Upload an image to Cloudinary and return the secure URL.
     */
    public function uploadImage(string $filePath, string $folder = 'general'): ?string
    {
        try {
            $baseFolder = config('services.cloudinary.upload_folder', 'maverick-academy');
            $fullFolder = $baseFolder . '/' . $folder;

            $result = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder' => $fullFolder,
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto:good',
                    'fetch_format' => 'auto',
                ],
                'overwrite' => false,
                'unique_filename' => true,
            ]);

            return $result['secure_url'] ?? null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an image from Cloudinary using its URL.
     */
    public function deleteImage(string $imageUrl): bool
    {
        try {
            $publicId = $this->extractPublicId($imageUrl);
            if (!$publicId) {
                return false;
            }

            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract public_id from a Cloudinary URL.
     */
    protected function extractPublicId(string $url): ?string
    {
        if (!str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $pattern = '#/upload/(?:v\d+/)?(.+?)(?:\.[a-z]{3,4})?$#i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}