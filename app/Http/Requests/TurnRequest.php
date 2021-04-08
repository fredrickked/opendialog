<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurnRequest extends FormRequest
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
            'id' => 'string',
            'od_id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'interpreter' => 'nullable|string',
            'behaviours' => 'array',
            'conditions' => 'array',
            'valid_origins' => 'array',
            'intents' => 'array'
        ];
    }
}