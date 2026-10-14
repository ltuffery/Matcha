<?php

namespace Matcha\Api\Model;

use Flight;
use flight\net\UploadedFile;
use Matcha\Api\Exceptions\PhotoException;
use Matcha\Api\Validator\Asserts\NotBlank;
use Matcha\Api\Services\Photo as PhotoService;

class Photo extends Model
{
    protected string $table = 'photos';
    protected array $uniques = ['name'];

    #[NotBlank()]
    public string $name;
    public int $user_id;

    public function user(): User
    {
        return User::find([
            'id' => $this->user_id,
        ]);
    }

    public static function upload(UploadedFile $file): self
    {
        $service = PhotoService::new($file);

        if (!$service->isValidExtension()) {
            throw new PhotoException();
        }

        if (!is_dir(BASE_PATH . "/storage/photos")) {
            mkdir(BASE_PATH . "/storage/photos", recursive: true);
        }

        return $service->save(Flight::user());
    }
}
