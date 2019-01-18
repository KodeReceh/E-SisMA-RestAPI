<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\IncomingLetter;
use App\Models\Disposition;
use App\Models\TemplateField;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('check:atur_pengguna', [
            'except' => [
                'notifications',
                'getCurrentUser'
            ]
        ]);
    }

    public function index()
    {
        $users = User::with('role')->get();

        return response()->json([
            'request_time' => Carbon::now()->timestamp,
            'amount_of_data' => $users->count(),
            'data' => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->employee_id_number = $request->employee_id_number;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->sex = $request->sex;
        $user->role_id = $request->role_id;
        $user->handphone = $request->handphone;
        $user->birthplace = $request->birthplace;
        $user->birthdate = $request->birthdate;
        $user->status = 1;
        if ($request->hasFile('signature')) {
            $theFile = $request->file('signature');
            $ext = $theFile->getClientOriginalExtension();
            $fileName = 'signature-' . time() . '.' . $ext;
            $theFile->storeAs(config('esisma.signatures'), $fileName);
            $user->signature = $fileName;
        }

        if ($user->save())
            return response()->json([
            'success' => true,
            'description' => 'Register successed!',
            'data' => $user
        ], 201);

        return response()->json([
            'success' => false,
            'description' => 'Register failed!',
            'data' => ''
        ], 401);

    }

    public function getUser($id)
    {
        $user = User::with('role')->find($id);

        if ($user) {
            return response()->json([
                'success' => true,
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

    public function getCurrentUser()
    {
        $user = app('auth')->user();
        $user->role = $user->role;

        return response()->json([
            'success' => true,
            'description' => '',
            'data' => $user
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->name = $request->name;
            $user->employee_id_number = $request->employee_id_number;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->sex = $request->sex;
            $user->role_id = $request->role_id;
            $user->handphone = $request->handphone;
            $user->birthplace = $request->birthplace;
            $user->birthdate = $request->birthdate;
            
            if($request->filled('password')) $user->password = Hash::make($request->password);
            
            if ($request->hasFile('signature')) {
                $oldFile = $user->signature;

                if(Storage::exists(config('esisma.signatures').'/'.$oldFile))
                    Storage::delete(config('esisma.signatures').'/'.$oldFile);

                $theFile = $request->file('signature');
                $ext = $theFile->getClientOriginalExtension();
                $fileName = 'signature-' . time() . '.' . $ext;
                $theFile->storeAs(config('esisma.signatures'), $fileName);
                $user->signature = $fileName;
            }

            if ($user->update())
                return response()->json([
                'success' => true,
                'description' => 'Updated successed!',
                'data' => $user
            ], 201);

            return response()->json([
                'success' => false,
                'description' => 'Update failed!',
                'data' => ''
            ], 401);
        } else {
            return response()->json([
                'success' => false,
                'description' => 'User not found!',
                'data' => null
            ], 404);
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        if($user->signature && Storage::exists(config('esisma.signatures').'/'.$user->signature))
            Storage::delete(config('esisma.signatures').'/'.$user->signature);

        if ($user->delete()) {
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
        if ($request->user_id == app('auth')->user()->id)
            return response()->json([
            'success' => false,
            'description' => 'Tidak mengubah status sendiri!'
        ], 403);

        if ($user->status) {
            $user->status = false;
        } else {
            $user->status = true;
        }

        if ($user->save())
            return response()->json([
            'success' => true,
            'description' => 'Status user berhasil dirubah'
        ], 201);

        return response()->json([
            'success' => false,
            'description' => 'Status user gagal dirubah'
        ], 400);
    }

    public function notifications()
    {
        $user = app('auth')->user();
        $incomingLetters = Disposition::where(['status' => false, 'user_id' => $user->id])->get();
        $fields = TemplateField::where('user_id', $user->id)->get();
        $data = [];

        foreach ($incomingLetters as $key => $incomingLetter) {
            $time = $incomingLetter->incoming_letter->letter->created_at;
            $data[] = [
                'title' => 'Surat Masuk ' . $incomingLetter->incoming_letter->letter->subject,
                'color' => 'cyan',
                'icon' => 'inbox',
                'timeLabel' => \Carbon\Carbon::parse($time)->diffForHumans(),
                'type' => 'incoming-letter',
                'id' => $incomingLetter->incoming_letter_id,
                'time' => $time
            ];
        }

        foreach ($fields as $key => $field) {
            $letters = $field->template->letter_templates;
            foreach ($letters as $index => $letter) {
                if (!$letter->hasUserSignedIt($user->id)) {
                    $data[] = [
                        'title' => 'Tanda Tangan untuk ' . $letter->letter_name,
                        'color' => 'red',
                        'icon' => 'border_color',
                        'timeLabel' => \Carbon\Carbon::parse($letter->created_at)->diffForHumans(),
                        'type' => 'signature',
                        'id' => $letter->id,
                        'time' => $letter->created_at
                    ];
                }
            }
        }

        if (count($data) > 0) {
            usort($data, function ($a, $b) {
                if ($a['time'] == $b['time']) return 0;
                return ($a['time'] > $b['time']) ? -1 : 1;
            });
        }

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $data
        ], 200);
    }
}
