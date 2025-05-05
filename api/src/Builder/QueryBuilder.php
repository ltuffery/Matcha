<?php

namespace Matcha\Api\Builder;

use Flight;
use Matcha\Api\Model\Model;
use PDO;

class QueryBuilder
{
    private string $class;
 
    private array $wheres = [];

    private ?string $order = null;

    private array $joins = [];

    private ?int $limit = null;

    private ?string $group = null;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function andWhere(string $column, string $condition, string $value): self
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

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function groupBy(string $column): self
    {
        $this->group = $column;

        return $this;
    }

    public function join(string $table, callable $func): self
    {
        $builder = new JoinBuilder($table);

        $func($builder);

        $this->joins[] = $builder->build();

        return $this;
    }

    private function buildWhereQuery(): string
    {
        if (empty($this->wheres)) {  
            return "";
        }

        $whereRaw = array_map(function ($value, $index) {
            if ($index === 0) {
                $base = $value[1] . " " . $value[2] . " ";

                if ($value[2] == "IN") {
                    return $base . $value[3];
                } else {
                    return $base . "'" . $value[3] . "'";
                }
            }

            return implode(" ", $value);
        }, $this->wheres, array_keys($this->wheres));

        return "WHERE " . join(" ", $whereRaw);
    }

    public function getRawSql(): string
    {
        $raw = "SELECT * FROM " . $this->class::getTable() . " "
                                . implode(" ", $this->joins) . " "
                                . $this->buildWhereQuery();

        if (!is_null($this->order)) {
            $raw .= " " . $this->order;
        }

        if (!is_null($this->limit)) {
            $raw .= " LIMIT " . $this->limit;
        }

        if (!is_null($this->group)) {
            $raw .= " GROUP BY " . $this->group;
        }

        return $raw;
    }

    public function get(?bool $array = false): Model|array|null
    {
        $stmt = Flight::db()->prepare($this->getRawSql());

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = array_map(function ($row) {
            return $this->class::morph($row);
        }, $data);

        if (count($models) > 1 || $array) {
            return $models;
        } else if (isset($models[0])) {
            return $models[0];
        }

        return null;
    }
}