<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class DuplicateSimulationRequest extends FormRequest
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
        return [
            'id' => 'exists:simulations,id',
            'simulator_title' => 'required|max:30',
            'inquiries' => 'required|email',
        ];
    }
}
