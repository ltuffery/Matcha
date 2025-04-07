<?php

namespace Matcha\Api\Builder;

use Flight;
use Matcha\Api\Model\Model;

class QueryBuilder
{
    private string $class;
 
    private array $wheres = [];

    private ?string $order = null;

    private array $joins = [];

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function where(string $column, string $condition, string $value): self
    {
        $this->wheres[] = ['AND', $column, $condition, $value];

        return $this;
    }

    public function orWhere(string $column, string $condition, string $value): self
    {
        $this->wheres[] = ['OR', $column, $condition, $value];

        return $this;
    }

    public function orderBy(string $column, string $order): self
    {
        $this->order = "ORDER BY " . $column . " " . $order;

        return $this;
    }

    public function join(string $table, callable $func): self
    {
        $builder = new JoinBuilder($table);

        $func($builder);

        $joins[] = $builder->build();

        return $this;
    }

    private function buildWhereQuery(): string
    {
        if (empty($this->wheres)) {  
            return "";
        }

        $whereRaw = array_map(function ($value, $index) {
            if ($index === 0) {
                return $value[1] . $value[2] . $value[3];
            }

            return join(" ", $value);
        }, $this->wheres, array_keys($this->wheres));

        return "WHERE " . join(" ", $whereRaw);
    }

    public function getRawSql(): string
    {
        $raw = "SELECT * FROM " . $this->class::getTable() . " "
                                . join(" ", $this->joins) . " "
                                . $this->buildWhereQuery();

        if ($this->order != null) {
            $raw .= " " . $this->order;
        }

        return $raw;
    }

    public function get(): Model
    {
        $stmt = Flight::db()->prepare($this->getRawSql());

        $stmt->execute();

        return $this->class::morph($stmt->fetch());
    }
}