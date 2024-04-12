<?php

namespace App\Interfaces;

interface Sortable
{
    /**
     * Get the columns that a sortable entity can be sorted by.
     */
    public static function getSortableColumns(): array;
}