<?php

namespace Matcha\Api\Builder;

/**
 * Class JoinBuilder
 *
 * Constructs an SQL INNER JOIN clause with optional AND conditions.
 * Used by the QueryBuilder class to define JOIN logic fluently.
 */
class JoinBuilder
{
    /** @var string The name of the table to join */
    private string $table;

    /** @var array List of ON clause conditions */
    private array $joins = [];

    /**
     * JoinBuilder constructor.
     *
     * @param string $table The name of the table to join.
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Adds a condition to the JOIN ON clause with an optional AND prefix.
     *
     * @param string $column The left-hand side column name.
     * @param string $condition The SQL operator (e.g., '=', '<>').
     * @param string $value The right-hand side value or column.
     * @return self
     */
    public function and(string $column, string $condition, string $value): self
    {
        $startWith = '';

        if (!empty($this->joins)) {
            $startWith = 'AND';
        }

        $this->joins[] = $startWith . " " . $column . " " . $condition . " " . $value;

        return $this;
    }

    /**
     * Builds the final INNER JOIN clause as a string.
     *
     * @return string The complete INNER JOIN SQL fragment.
     */
    public function build(): string
    {
        return "INNER JOIN " . $this->table . " ON " . implode(" ", $this->joins);
    }
}
