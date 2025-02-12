<?php

namespace Matcha\Api\Resources;

use Flight;
use Matcha\Api\Model\User;

/**
 * @property User $model
 */
class ProfileResource extends JsonResource
{
    private User $user;

    public function __construct(User $model)
    {
        parent::__construct($model);

        $this->user = Flight::user();
    }

    private function getDistance(): float|int
    {
        if ($this->user->username == $this->model->username) {
            return 0;
        }

        $distance = haversine($this->user->lat, $this->user->lon, $this->model->lat, $this->model->lon);

        if ($distance < 1) {
            return -1;
        }

        return $distance;
    }

    private function countCommonTags(): int
    {
        $tags = $this->user->getTags();

        return count($tags) - count(array_diff($tags, $this->model->getTags()));
    }

    public function jsonSerialize(): array
    {
        $resource = [
            'username' => $this->model->username,
            'avatar' => $this->model->getAvatar(),
            'photos' => $this->model->getPhotosUrl(),
            'first_name' => $this->model->first_name,
            'last_name' => $this->model->last_name,
            'age' => $this->model->getAge(),
            'biography' => $this->model->biography,
            'fame_rating' => $this->model->fame_rating,
            'distance' => $this->getDistance(),
            'me' => $this->user->username == $this->model->username,
            'common_tags' => $this->countCommonTags(),
            'gender' => $this->model->gender,
            'tags' => $this->model->getTags(),
        ];

        if ($resource['me']) {
            $resource['preferences'] = new PreferencesResource($this->model->getPreferences());
            $resource['email'] = $this->model->email;
            $resource['lat'] = $this->model->lat;
            $resource['lon'] = $this->model->lon;
            $resource['user_set_loc'] = $this->model->user_set_loc;
        }

        return $resource;
    }
}
