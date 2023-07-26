<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->all());
        }

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        return ResponseBuilder::success("You have successfully registered", 201);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->all());
        }

        $user = User::whereEmail($request->email)->first();

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                return ResponseBuilder::error("Invalid login credentials.");
            }
            $success['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
            return ResponseBuilder::success($success);
        } else {
            return ResponseBuilder::error("Sorry, It Seems this email address isn\'t in our records.");
        }
    }
}
