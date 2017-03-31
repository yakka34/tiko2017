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
        return [
            'name' => 'required',
            'email' => 'required|email|min:2',
        ];
    }

    public function persist($id) {
        $user = User::find($id);
        //$user = Auth::user();

        $user->email = $this->email;
        $user->name = $this->name;
        // Jos käyttäjällä on oikeudet päivittää muutakin tietoa
        if ($user->can('update-student-info')) {
            $user->studentId = $this->studentId;
            $user->major = $this->major;
        }

        $user->save();
    }
}
