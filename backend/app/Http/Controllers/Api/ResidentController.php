<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('residentHouse.residentialStatus')->get();

        return response(json_encode([
            'success' => true,
            'message' => 'Daftar data penghuni',
            'data' => $residents,
        ]));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'id_card_number' => 'required|unique:residents|max:20',
            'id_card_image' => 'required|image',
            'phone_number' => 'required|max:20',
            'marital_status' => 'required',
            'active' => 'required',
        ], [
            'name.required' => 'Mohon input nama',
            'id_card_number.required' => 'Mohon input nomor identitas',
            'id_card_number.unique' => 'Nomor kartu identitas telah terdaftar',
            'id_card_image.required' => 'Mohon input foto kartu identitas',
            'id_card_image.image' => 'Mohon hanya input file gambar',
            'phone_number.required' => 'Nomor input nomor yang dapat dihubungi',
            'phone_number.max' => 'Nomor rumah terlalu panjang (maks. 20 digit)',
            'marital_status.required' => 'Mohon input status pernikahan',
            'active.required' => 'Mohon input status',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $validatedRequest = $validator->validated();

        if ($request->hasFile('id_card_image')) {
            $idCardImage = $request->file('id_card_image');
            $idCardImage->storeAs('public/images/id-cards/', $idCardImage->hashName());
            $validatedRequest['id_card_image'] = $idCardImage->hashName();
        }

        Resident::create($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $validatedRequest
        ]));
    }

    public function show($id)
    {
        $resident = Resident::where('id', $id)->with('residentHouse.residentialStatus')->first();

        return response(json_encode([
            'success' => true,
            'message' => 'Detail data penghuni',
            'data' => $resident,
        ]));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'id_card_number' => 'required|max:20',
            'id_card_image' => 'image',
            'phone_number' => 'required|max:20',
            'marital_status' => 'required',
            'active' => 'required',
        ], [
            'name.required' => 'Mohon input nama',
            'id_card_number.unique' => 'Nomor kartu identitas telah terdaftar',
            'id_card_image.required' => 'Mohon input foto kartu identitas',
            'id_card_image.image' => 'Mohon hanya input file gambar',
            'phone_number.required' => 'Nomor input nomor yang dapat dihubungi',
            'phone_number.max' => 'Nomor rumah terlalu panjang (maks. 20 digit)',
            'marital_status.required' => 'Mohon input status pernikahan',
            'active.required' => 'Mohon input status',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $resident = Resident::where('id', $id)->first();

        $duplicateidCardNumber = Resident::where('id_card_number', $request->id_card_number)
            ->where('id', '!=', $id)
            ->first();

        if ($duplicateidCardNumber != null) {
            return response(json_encode([
                'success' => false,
                'message' => 'Nomor kartu identitas telah terdaftar',
                'data' => null
            ]), 422);
        }

        if (!$request->active) {
            $check = Resident::where('id', $id)->with('residentHouse')->first();

            if ($check->is_resident) {
                return response(json_encode([
                    'success' => false,
                    'message' => 'Penghuni tidak dapat di-nonaktifkan karena masih berstatus menempati hunian',
                    'data' => null
                ]), 422);
            }
        }

        $validatedRequest = $validator->validated();

        if ($request->hasFile('id_card_image')) {
            $idCardImage = $request->file('id_card_image');
            $idCardImage->storeAs('public/images/id-cards/', $idCardImage->hashName());
            $validatedRequest['id_card_image'] = $idCardImage->hashName();

            if (basename($resident->id_card_image) != 'default.jpg') {
                Storage::delete('public/images/id-cards/' . basename($resident->id_card_image));
            }
        } else {
            $validatedRequest['id_card_image'] = basename($resident->id_card_image);
        }

        Resident::where('id', $id)->update($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $resident,
        ]));
    }
}
