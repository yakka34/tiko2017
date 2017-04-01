<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update-info');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|min:2'
        ];
        if (Auth::user()->can('update-student-info')) {
            $rules['studentId'] = 'nullable|min:2';
            $rules['major'] = 'nullable|min:2';
        }

        return $rules;
    }

    public function persist($id) {
        $user = User::find($id);

        $user->email = $this->email;
        $user->name = $this->name;
        // Jos käyttäjällä on oikeudet päivittää muutakin tietoa
        if (Auth::user()->can('update-student-info')) {
            $user->studentId = $this->studentId;
            $user->major = $this->major;
        }

        $user->save();
    }
}
