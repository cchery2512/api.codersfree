<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = DB::transaction(function () use($request){
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return $user;
        });

        return UserResource::make($user);
    }

    public function show($id){
        $user = User::findOrFail($id);
        return new UserResource($user);
    }
}
