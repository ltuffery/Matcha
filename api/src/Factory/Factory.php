<?php

namespace Matcha\Api\Factory;

use Matcha\Api\Model\Model;

abstract class Factory
{
    private string $model;
    private int $count = 1;
    protected array $states = [];
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
            $states = [];

            foreach ($this->states as $key => $state) {
                if ($state instanceof Model) {
                    $states[$key . '_id'] = $state->id;
                } else {
                    $states[$key] = $state;
                }
            }

            $data = array_merge($this->define(), $states);

            $model->fill($data);

            $saved = $model->save();

            if (!empty($this->child)) {
                $namespace = explode('\\', $this->model);
                $className = end($namespace);

                /**
                 * @var Factory $child
                 */
                foreach ($this->child as $child) {
                    $child->state([
                        strtolower($className) => $saved,
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
