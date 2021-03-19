<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
            'sender' => 'required|email:rfc,dns,filter',
            'recipient' => 'required|email:rfc,dns,filter',
            'subject' => 'required|string',
            'text' => 'nullable|string',
            'html' => 'nullable|string',
            'attachments' => 'nullable|array|max:10240',
            'attachments.*' => 'file',
        ];
    }
}
