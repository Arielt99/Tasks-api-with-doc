<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Register
     *
     * @param  Request  $request
     * @return Response
     */
    function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','string','min:6','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/']
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //login
    function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required','string','min:6','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/']
        ]);

        $user= User::where('email', $request->email)->first();

        if (!$user) {
            return response([
                    'message'=> 'The given data was invalid.',
                    'errors'=> array(
                        'email' => [
                            'The email do not match our records.'
                        ]
                    )
            ], 422);
        }
        elseif(!Hash::check($request->password, $user->password)) {
            return response([
                'message'=> 'The given data was invalid.',
                'errors'=> array(
                    'password' => [
                        'The password do not match our records.'
                    ]
                )
            ], 422);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

            return response($response, 201);
    }
}
