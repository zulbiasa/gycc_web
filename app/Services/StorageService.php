<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class StorageService
{
    protected $bucket;

    public function __construct()
    {
        // Fetch Firebase Storage image
    $factory = (new Factory)
    ->withServiceAccount([
        'type' => 'service_account',
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
        'private_key' => str_replace('\n', "\n", env('FIREBASE_PRIVATE_KEY')),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'client_id' => env('FIREBASE_CLIENT_ID'),
        'auth_uri' => env('FIREBASE_AUTH_URI'),
        'token_uri' => env('FIREBASE_TOKEN_URI'),
        'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL'),
        'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
        'universe_domain'=> env('FIREBASE_UNIVERSE_DOMAIN'),
    ]);

    $storage = $factory->createStorage();
    $bucket = $storage->getBucket(env('FIREBASE_STORAGE_BUCKET'));

    $this->bucket = $bucket;
    }

    public function getImage($userID)
    {
        $imageUrl = null;
        $image = $this->bucket->object('User/' . $userID . '/profile_image');
        if ($image->exists()) {
            $imageUrl = $image->signedUrl(new \DateTime('+1 day'));
        }

        return $imageUrl;
    }

    public function setImage($userID, $imageFile)
{
    try {
        // Crop the image to 1:1 aspect ratio
        $croppedImage = Image::make($imageFile->getPathname())
            ->fit(1240, 1240) // Resize and crop to 500x500 pixels (adjust as needed)
            ->encode($imageFile->getClientOriginalExtension()); // Encode to the original format

        // Define the file path
        $filePath = "User/{$userID}/profile_image";

        // Upload the cropped image to Firebase Storage
        $this->bucket->upload($croppedImage->stream(), [
            'name' => $filePath,
            'metadata' => [
                'contentType' => $imageFile->getMimeType(), // Preserve MIME type
            ],
        ]);

        // Generate a signed URL valid for 1 day
        $image = $this->bucket->object($filePath);
        if ($image->exists()) {
            return $image->signedUrl(new \DateTime('+1 day'));
        }

        return null; // Return null if the file was not uploaded correctly
    } catch (\Exception $e) {
        \Log::error('Error uploading cropped image:', ['exception' => $e->getMessage()]);
        return null; // Return null in case of an error
    }
}


}
