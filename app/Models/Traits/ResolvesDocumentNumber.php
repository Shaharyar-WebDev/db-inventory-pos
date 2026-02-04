<?php

namespace App\Models\Traits;

trait ResolvesDocumentNumber
{
    /**
     * Resolve the top-level document number for ledger/source exports.
     *
     * Rules:
     * - If the model itself is a parent document → use its own document column
     * - If the model is a line/child → walk via static::$parentRelation
     */
    public function resolveDocumentNumber(): ?string
    {
        // Case 1: This model IS the parent document
        if (property_exists(static::class, 'documentNumberColumn')) {
            $column = static::$documentNumberColumn;

            return $this->{$column} ?? null;
        }

        // Case 2: This model is a child/line item
        if (! property_exists(static::class, 'parentRelation')) {
            return null;
        }

        $relation = static::$parentRelation;

        if (! method_exists($this, $relation)) {
            return null;
        }

        $parent = $this->{$relation};

        if (! $parent) {
            return null;
        }

        if (! property_exists($parent::class, 'documentNumberColumn')) {
            return null;
        }

        return $parent->{ $parent::$documentNumberColumn } ?? null;
    }
}
