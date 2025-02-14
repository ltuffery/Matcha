<?php

namespace Matcha\Api\Resources;

use Matcha\Api\Model\Model;
use Matcha\Api\Model\Preference;

/**
 * @property Preference $model
 */
class PreferencesResource extends JsonResource
{
    public function __construct(Preference $model)
    {
        parent::__construct($model);
    }

    public function jsonSerialize(): array
    {
        return [
            'age_minimum' => $this->model->age_minimum,
            'age_maximum' => $this->model->age_maximum,
            'distance_maximum' => $this->model->distance_maximum,
            'by_tags' => $this->model->by_tags,
            'sexual_preferences' => $this->model->sexual_preferences,
            'lat' => $this->model->lat,
            'lon' => $this->model->lon,
            'is_custom_loc' => $this->model->is_custom_loc,
        ];
    }
}
