<?php

namespace Matcha\Api\Resources;

use JsonSerializable;
use Matcha\Api\Model\Model;

abstract class JsonResource implements JsonSerializable
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param Model[] $collection
     * @return JsonResource[]
     */
    public static function collection(array $collection): array
    {
        $resources = [];

        foreach ($collection as $m) {
            $resources[] = new (get_called_class())($m);
        }

        return $resources;
    }
}
