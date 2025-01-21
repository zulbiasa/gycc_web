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
    ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));

    $storage = $factory->createStorage();
    $bucket = $storage->getBucket(env('FIREBASE_STORAGE_BUCKET'));

    $this->bucket = $bucket;
    }

    public function getImage($userID)
    {
        $imageUrl = null;
        $image = $this->bucket->object($userID);
        if ($image->exists()) {
            $imageUrl = $image->signedUrl(new \DateTime('+1 day'));
        }

        return $imageUrl;
    }
}
