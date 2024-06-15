<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ResidentialStatus;
use Illuminate\Http\Request;

class ResidentialStatusController extends Controller
{
    public function index()
    {
        $residentialStatuses = ResidentialStatus::all();

        return response(json_encode([
            'success' => true,
            'message' => 'Daftar data status hunian',
            'data' => $residentialStatuses,
        ]));
    }
}
