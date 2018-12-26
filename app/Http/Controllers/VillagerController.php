<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villager;

class VillagerController extends Controller
{
    public function all()
    {
        $villagers = Villager::pluck('name', 'id');

        return response()->json([
            'success' => true,
            'description' => 'Berhasil mengambil data.',
            'data' => $villagers
        ], 200);
    }
}
