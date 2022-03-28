<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use \App\Models\User;


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
        $user_name = User::find($this->user_id)->name;
        return [
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'user_namr' => $user_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
