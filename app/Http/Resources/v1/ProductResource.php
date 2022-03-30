<?php

namespace App\Http\Resources\v1;

use App\Models\ProductsCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use \App\Models\User;
use Illuminate\Support\Facades\DB;


class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'user_name' => User::find($this->user_id)->name ?? '',
            'created_at' => date('Y-m-d h:i:s' , strtotime($this->created_at)),
            'updated_at' => date('Y-m-d h:i:s' , strtotime($this->updated_at)),
        ];
    }
}
