<?php

namespace Matcha\Api\Controllers\User;

use Flight;
use Matcha\Api\Model\Photo;
use Matcha\Api\Model\User;
use Matcha\Api\Resources\PhotoResource;

class PhotoController
{
    public function index()
    {
        $photos = Photo::all([
            'user_id' => Flight::user()->id,
        ]);

        Flight::json(PhotoResource::collection($photos));
    }

    public function store()
    {
        /** @var User $user */
        $user = Flight::user();
        $files = Flight::request()->getUploadedFiles();
        $photos = $files['photos'] ?? null;

        if (!$photos) {
            Flight::json(['success' => false, 'error' => 'Photos is required.'], 400);
            return;
        }
        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (count($user->getPhotosUrl()) + count($photos) > 5) {
            Flight::json(['success' => false, 'error' => 'You cannot upload more than 5 photos.'], 422);
            return;
        }

        foreach ($photos as $photo) {
            if ($photo->getError() !== UPLOAD_ERR_OK) {
                Flight::json(['success' => false, 'error' => 'Upload failed (code ' . $photo->getError() . ').'], 422);
                return;
            }
        }

        $p = [];
        foreach ($photos as $photo) {
            $p[] = Photo::upload($photo);
        }

        Flight::json(PhotoResource::collection($p), 201);
    }


    public function delete($id)
    {
        $photo = Photo::find(['id' => $id]);

        if (is_null($photo)) {
            Flight::json([
                'success' => false,
                'message' => "The photo does not exist.",
            ], 404);
            return;
        }

        unlink(BASE_PATH . "/storage/photos/" . $photo->name . ".png");
        $photo->delete();

        Flight::json([
            'success' => true,
            'message' => "The photo has been deleted.",
        ], 201);
    }
}