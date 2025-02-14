<?php

namespace Matcha\Api\Services;

use Exception;
use flight\net\UploadedFile;
use Matcha\Api\Model\User;

class Photo
{
    const VALID_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];
    private UploadedFile $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public static function new(UploadedFile $file): self
    {
        return new static($file);
    }

    public function isValidExtension(): bool
    {
        $extension = pathinfo($this->file->getClientFilename(), PATHINFO_EXTENSION);

        return in_array($extension, self::VALID_EXTENSIONS) && $this->testCreateByGD($extension);
    }

    private function testCreateByGD(string $extension): bool
    {
        return match ($extension) {
            'jpeg', 'jpg' => is_object(@imagecreatefromjpeg($this->file->getTempName())),
            'png' => is_object(@imagecreatefrompng($this->file->getTempName())),
            'gif' => is_object(@imagecreatefromgif($this->file->getTempName())),
            default => false,
        };
    }

    public function save(User $user): void
    {
        try {
            $name = bin2hex(random_bytes(15));
        } catch (Exception) {
            $name = $this->file->getTempName();
        }

        $photo = new \Matcha\Api\Model\Photo();

        $photo->name = $name;
        $photo->user_id = $user->id;

        $photo->create();
        $this->file->moveTo(BASE_PATH . "/storage/photos/" . $name . ".png");
    }
}