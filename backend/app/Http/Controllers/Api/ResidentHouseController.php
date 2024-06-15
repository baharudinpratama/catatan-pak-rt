<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ResidentHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResidentHouseController extends Controller
{
    public function destroy($id)
    {
        ResidentHouse::find($id)->delete();

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil dihapus',
            'data' => null,
        ]));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'house_id' => 'required',
            'resident_id' => 'required',
            'residential_status_id' => 'required',
        ], [
            'house_id.required' => 'Mohon input rumah',
            'resident_id.required' => 'Mohon input penghuni',
            'residential_status_id.required' => 'Mohon input tipe hunian',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $validatedRequest = $validator->validated();

        ResidentHouse::create($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $validatedRequest,
        ]));
    }
}
