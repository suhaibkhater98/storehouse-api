<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $numberOfProduct = DB::table('products_categories')->where('category_id' , '=' ,$this->id)->count('id');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number_of_products' => $numberOfProduct,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
