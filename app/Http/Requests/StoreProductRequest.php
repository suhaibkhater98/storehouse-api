<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $data = [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'user_id' => 'exists:\App\Models\User,id'
        ];

        if($this->request->has('image') && !empty($this->request->get('image')))
            $data['image'] = 'image|mimes:jpg,png,jpeg,gif,svg|max:2048';

        return $data;
    }
}
