<?php

namespace Matcha\Api\Factory;

use Exception;

final class Factory
{
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function create(array $data): void
    {
        $model = new $this->model();

        try {
            foreach ($data as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        } catch (Exception $e) {
            if (!getenv('PHPUNIT_TEST')) {
                echo sprintf("\033[0;31m[%s] \033[0m%s", $this->model, $e->getMessage()) . PHP_EOL;
            }
        }
    }
}
