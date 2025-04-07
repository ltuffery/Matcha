<?php

namespace Matcha\Api\Builder;

class JoinBuilder
{
    private string $table;

    private array $joins = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function and(string $column, string $condition, string $value): self
    {
        $join = [];

        if (!empty($this->joins)) {
            $join[0] = 'AND';
        }

        $this->joins[] = array_merge($join, [$column, $condition, $value]);

        return $this;
    }

    public function build(): string
    {
        return "INNER JOIN " . $this->table . " ON " . implode(" ", $this->joins);
    }

}