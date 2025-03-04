<?php

namespace Matcha\Api\Factory;

use Matcha\Api\Model\Model;

abstract class Factory
{
    private string $model;
    private int $count = 1;
    private array $states = [];
    private array $child = [];

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

    public function state(array $states): self
    {
        $this->states = $states;

        return $this;
    }

    public function create(): array|Model
    {
        $entities = [];

        for ($i = 0; $i < $this->count; $i++) {
            $model = new $this->model();
            $data = array_merge($this->define(), $this->states);

            foreach ($data as $key => $value) {
                $model->{$key} = $value;
            }

            $saved = $model->save();

            if (!empty($this->child)) {
                $namespace = explode('\\', $this->model);
                $className = end($namespace);

                /**
                 * @var Factory $child
                 */
                foreach ($this->child as $child) {
                    $child->state([
                        strtolower($className) . '_id' => $saved->id,
                    ])->create();
                }
            }

            $entities[] = $saved;
        }

        return count($entities) === 1 ? $entities[0] : $entities;
    }

    public function has(Factory $factory): self
    {
        $this->child[] = $factory;

        return $this;
    }

    abstract protected function define(): array;
}
