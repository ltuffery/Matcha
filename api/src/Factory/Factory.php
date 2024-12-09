<?php

namespace Matcha\Api\Factory;

use Exception;

final class Factory
{
    private string $model;
    private int $count = 1;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function count(int $n): static
    {
        if ($n <= 0) {
            throw new Exception("The count value must be greater than zero. Provided value: {$n}");
        }

        $this->count = $n;

        return $this;
    }

    public function create(array $data): void
    {
        for ($i = 0; $i < $this->count; $i++) {
            $model = new $this->model;

            try {
                foreach ($data as $key => $value) {
                    $model->{$key} = $value;
                }

                $model->save();
            } catch(Exception $e) {
                if (!getenv('PHPUNIT_TEST')) {
                    echo sprintf("\033[0;31m%s (%d) : %s\033[0m", $this->model, $i, $e->getMessage()) . PHP_EOL;
                }
            }
        }
    }
}