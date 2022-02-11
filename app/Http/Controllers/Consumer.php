<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Consumer extends Controller
{
    public function user_Login(Request $request){
        //Validate request
        $field = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);
        //Check if entry exists
        $user = User::where('name', $field['name'])->first();

        //If entry doesn't exist, return false
        if(!$user || !Hash::check($field['password'], $user->password)){
            //Return false
            return response([
                'message' => 'Gegevens kloppen niet!'
            ],401);
        }

        //Create token
        $token = $user->createToken('web')->plainTextToken;

        //Return data
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
    public function user_register(Request $request){
        //Validate login fields
        $field = $request->validate([
            'name' => 'required|string|unique:users,name','password' => 'required|string'
        ]);

        //Create user table
        $user = User::create([
            'name' => $field['name'], 'role' => 2,
            'password' => bcrypt($field['password'])
        ]);

        //Create token
        $token = $user->createToken('web')->plainTextToken;

        //Return data
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
}
