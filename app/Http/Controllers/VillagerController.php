<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villager;
use Illuminate\Support\Facades\DB;

class VillagerController extends Controller
{
    public function all()
    {
        $villagers = Villager::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $villagers
        ], 200);
    }
}
