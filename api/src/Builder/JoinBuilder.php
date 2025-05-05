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
        $startWith = '';

        if (!empty($this->joins)) {
            $startWith = 'AND';
        }

        $this->joins[] = $startWith . " " . $column . " " . $condition . " " . $value;

        return $this;
    }

    public function build(): string
    {
        return "INNER JOIN " . $this->table . " ON " . implode(" ", $this->joins);
    }

}