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

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function join(string $table, string $column, string $condition, string $value): self
    {
        $this->joins[] = "INNER JOIN "
                            . $table . " ON "
                            . $column . " "
                            . $condition . " "
                            . $value;

        return $this;
    }

    private function buildWhereQuery(): string
    {
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

        return " WHERE " . implode(" ", $whereRaw);
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

        return $raw;
    }

    public function get(): Model|array
    {
        $stmt = Flight::db()->prepare($this->getRawSql());

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($data) == 1) {
            return $this->class::morph($data[0]);
        }

        return array_map(function ($row) {
            return $this->class::morph($row);
        }, $data);
    }
}