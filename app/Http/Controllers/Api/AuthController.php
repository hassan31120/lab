<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'number' => 'required|numeric|unique:users,number',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        $user = User::create($data);

        return response()->json([
            'success' => true,
            'user' => new UserResource($user)
        ], 200);
    }

    public function login(Request $request){

        if (Auth::attempt(['number' => $request->number, 'password' => $request->password])) {

            $user =  User::find(Auth::user()->id);
            $user->save();
            return response()->json([
                'success' => true,
                'user' => new UserResource($user)
            ], 200);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Please Check your credentials' ,
                ],
                200
            );
        }
    }
}
