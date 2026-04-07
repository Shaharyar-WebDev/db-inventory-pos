<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'full_name'              => $this->full_name,
            'thumbnail'              => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'unit_id'                => $this->unit_id,
            'unit'                   => $this->unit?->name,
            'sub_unit_id'            => $this->sub_unit_id,
            'sub_unit'               => $this->subUnit?->name,
            'sub_unit_conversion'    => $this->sub_unit_conversion,
            'selling_price'          => $this->selling_price,
            'current_outlet_stock'   => $this->current_outlet_stock ?? 0,
            'sub_unit_selling_price' => $this->sub_unit_selling_price,
            'parent_product_id'      => $this->parent_product_id,
            'parent_product_name'    => $this->parent?->name,   // fixed duplicate
            'category_id'            => $this->category_id,
            'category_name'          => $this->category?->name,
            'brand_id'               => $this->brand_id,
            'brand_name'             => $this->brand?->name,
            'current_outlet_stock_sub_unit' => $this->subUnit ?
                (($this->current_outlet_stock ?? 0) * ($this->sub_unit_conversion ?? 1)) : 0,
        ];
    }
}
