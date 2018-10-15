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
        $users = User::with('role')->get();

        return response()->json([
            'request_time' => Carbon::now()->timestamp,
            'amount_of_data' => $users->count(),
            'data' => $users
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

    public function getUser($id)
    {
        $user = User::with('role')->find($id);
        
        if($user){
            return response()->json([
                'success' =>true,
                'description' => '',
                'data' => $user
            ], 200);
        }

        return response()->json([
            'success' => false,
            'description' => 'User not found!',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->birthplace = $request->birthplace;
            $user->birthdate = $request->birthdate;
            $user->sex = $request->sex;
            $user->address = $request->address;
            $user->handphone = $request->handphone;
            $user->role_id = $request->role_id;
            if($request->password)
                $user->password = Hash::make($request->password);

            if($user->update()){
                return response()->json([
                    'success' =>true,
                    'description' => 'User succesfully updated!',
                    'data' => $user
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'description' => 'Something went wrong, not updated!',
                    'data' => null
                ], 401);
            }
        }else{
            return response()->json([
                'success' => false,
                'description' => 'User not found!',
                'data' => null
            ], 404);
        }
    }

    public function delete($id) {
        $user = User::find($id);

        if($user->delete()) {
            return response()->json([
                'success' => true,
                'description' => 'Data berhasil dihapus!'
            ], 200);
        }

        return reponse()->json([
            'success' => false,
            'description' => 'Data gagal dihapus!'
        ], 400);
    }

    public function changeStatus(Request $request)
    {
        $user = User::find($request->user_id);
        if($user->status){
            $user->status = false;
        }else{
            $user->status = true;
        }
        if($user->save())
            return response()->json([
                'success' => true,
                'description' => 'Status user berhasil dirubah'
            ], 201);

        return response()->json([
            'success' => false,
            'description' => 'Status user gagal dirubah'
        ], 400);
    }
}
