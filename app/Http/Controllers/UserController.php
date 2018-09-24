<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'request_time' => Carbon::now()->timestamp,
            'amount_of_data' => $users->count(),
            'data' => User::all()
        ], 200);
    }

    public function register(Request $request)
    {
        $password = Hash::make($request->password);
        $request['password'] = $password;

        $save = User::create($request->all());

        if($save)
            return response()->json([
                'success' => true,
                'description' => 'Register successed!',
                'data' => $save
            ], 201);

        return response()->json([
            'success' => false,
            'description' => 'Register failed!',
              'data' => ''
         ], 400);
        
    }
}
