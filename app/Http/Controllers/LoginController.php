<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
            ],
            [
                'email.required' => 'Էլ․ փոստը պարտադիր լրացման դաշտ է', // custom message
                'email.email' => 'Էլ. փոստը պետք է վավեր էլ․ փոստի հասցե լինի',
                'password.required' => 'Գաղտնաբառը պարտադիր լրացման դաշտ է' // custom message
            ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Էլ․ հասցեն կամ գաղտնաբառը սխալ է մուտքագրված']
            ]);
        }
    
        return $user->createToken($request->device_name)->plainTextToken;

    }

    public function logout()
    {
        return Auth::logout();
    }

    public function resetPassword(Request $request)
    {
        $currentUser = User::where('email', $request->input('email'))->first();
        $currentPassword = $request->input('current_password');
        $newPassword = Hash::make($request->input('new_password'));

        if(Hash::check($currentPassword, $currentUser->password)){
            $currentUser->password = $newPassword;
            if($currentUser->save()){
                return $currentUser;
            }
        }else{
            return response()->json(['error' => 'Not matched'], 500);
        }


    }
}
