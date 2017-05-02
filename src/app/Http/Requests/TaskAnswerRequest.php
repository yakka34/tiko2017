<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TaskAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('solve-task');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    //TODO Syntaksin tarkistaminen(sulkujen lkm täsmäys ja päättyminen puolipisteeseen)
    public function rules()
    {
        return [
            'query' => 'required'
        ];
    }
}
