<?php

namespace Matcha\Api\Builder;

use Flight;
use Matcha\Api\Model\Model;
use PDO;

/**
 * Class QueryBuilder
 *
 * Provides a fluent API to build and execute SQL SELECT queries.
 * Supports WHERE clauses, JOINs, ORDER BY, LIMIT, and GROUP BY.
 */
class QueryBuilder
{
    /** @var string The model class associated with the query */
    private string $class;

    /** @var array List of WHERE conditions */
    private array $wheres = [];

    /** @var string|null ORDER BY clause */
    private ?string $order = null;

    /** @var array List of JOIN clauses */
    private array $joins = [];

    /** @var int|null LIMIT clause */
    private ?int $limit = null;

    /** @var string|null GROUP BY clause */
    private ?string $group = null;

    /**
     * QueryBuilder constructor.
     *
     * @param string $class The model class name.
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Adds an AND WHERE condition to the query.
     *
     * @param string $column Column name.
     * @param string $condition SQL condition (e.g., '=', 'IN').
     * @param string $value Value to compare.
     * @return self
     */
    public function andWhere(string $column, string $condition, string $value): self
    {
        $this->wheres[] = ['AND', $column, $condition, $value];
        return $this;
    }

    /**
     * Adds an OR WHERE condition to the query.
     *
     * @param string $column Column name.
     * @param string $condition SQL condition.
     * @param string $value Value to compare.
     * @return self
     */
    public function orWhere(string $column, string $condition, string $value): self
    {
        $this->wheres[] = ['OR', $column, $condition, $value];
        return $this;
    }

    /**
     * Specifies an ORDER BY clause.
     *
     * @param string $column Column to order by.
     * @param string $order 'ASC' or 'DESC'.
     * @return self
     */
    public function orderBy(string $column, string $order): self
    {
        $this->order = "ORDER BY " . $column . " " . $order;
        return $this;
    }

    /**
     * Sets a LIMIT for the number of results.
     *
     * @param int $limit Maximum number of rows to return.
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets a GROUP BY clause.
     *
     * @param string $column Column to group by.
     * @return self
     */
    public function groupBy(string $column): self
    {
        $this->group = $column;
        return $this;
    }

    /**
     * Adds a JOIN clause using a JoinBuilder.
     *
     * @param string $table Table to join.
     * @param callable $func Function that defines join conditions.
     * @return self
     */
    public function join(string $table, callable $func): self
    {
        $builder = new JoinBuilder($table);
        $func($builder);
        $this->joins[] = $builder->build();
        return $this;
    }

    /**
     * Builds the WHERE clause string from all conditions.
     *
     * @return string The SQL WHERE clause.
     */
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

    /**
     * Generates the full SQL SELECT statement.
     *
     * @return string The raw SQL query string.
     */
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

    /**
     * Executes the query and returns the result(s).
     *
     * @param bool|null $array If true, always return an array of results.
     * @return Model|array|null A model instance, an array of models, or null.
     */
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
