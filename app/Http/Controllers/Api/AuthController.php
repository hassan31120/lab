<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

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

    public function login(Request $request)
    {

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
                    'message' => 'Please Check your credentials',
                ],
                200
            );
        }
    }

    public function ChangePassword(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $user = User::find(Auth::id());
        if (password_verify($data['password'], $user->password)) {
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'password has been successfully changed!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The old password is incorrect'
            ], 200);
        }
    }

    public function editProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $data = $request->all();

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'data has been edited successfully!',
            'user' => new UsersResource($user)
        ], 200);
    }

    public function viewUsers()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'users' => UsersResource::collection($users)
        ], 200);
    }

    public function addUser(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'number' => 'required|numeric|unique:users,number',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'userType' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response()->json([
            'success' => true,
            'user' => new UsersResource($user)
        ], 200);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required',
                'number' => 'required|numeric|unique:users,number,' . $user->id,
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
                'userType' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $data['password'] = Hash::make($data['password']);
            $user->update($data);
            return response()->json([
                'success' => true,
                'user' => new UsersResource($user)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user doesn\'t exist!'
            ], 200);
        }
    }

    public function delUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'user has been deleted successfully!'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user doesn\'t exist!'
            ], 200);
        }
    }
}
