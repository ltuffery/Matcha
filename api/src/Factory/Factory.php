<?php

namespace Matcha\Api\Factory;

use Exception;

abstract class Factory
{
    private string $model;
    private int $count = 1;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function count(int $n): self
    {
        if ($n >= 1) {
            $this->count = $n;
        }

        return $this;
    }

    public function create(): array
    {
        $entities = [];

        for ($i = 0; $i < $this->count; $i++) {
            $model = new $this->model();
            $data = $this->define();

            try {
                foreach ($data as $key => $value) {
                    $model->{$key} = $value;
                }

                $entities[] = $model->save();
            } catch (Exception $e) {
                if (!getenv('PHPUNIT_TEST')) {
                    echo sprintf("\033[0;31m[%s] \033[0m%s", $this->model, $e->getMessage()) . PHP_EOL;
                } else {
                    throw $e;
                }
            }
        }

        return $entities;
    }

    protected abstract function define(): array;
}
