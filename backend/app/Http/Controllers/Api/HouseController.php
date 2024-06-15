<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::with('residentHouses')->get();

        return response(json_encode([
            'success' => true,
            'message' => 'Daftar data hunian',
            'data' => $houses,
        ]));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'house_number' => 'required|unique:houses|max:4',
            'description' => 'max:255',
            'house_image' => 'required|image',
            'active' => 'required',
        ], [
            'house_number.required' => 'Mohon input nomor rumah',
            'house_number.unique' => 'Nomor rumah telah terdaftar',
            'house_number.max' => 'Nomor rumah terlalu panjang (maks. 4 digit)',
            'description.max' => 'Teks deskripsi terlalu panjang (maks. 255 karakter)',
            'house_image.required' => 'Mohon hanya input file gambar',
            'house_image.image' => 'Mohon input file gambar',
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

        if ($request->hasFile('house_image')) {
            $houseImage = $request->file('house_image');
            $houseImage->storeAs('public/images/houses/', $houseImage->hashName());
            $validatedRequest['house_image'] = $houseImage->hashName();
        }

        if (is_null($validatedRequest['description'])) {
            $validatedRequest['description'] = '';
        }

        House::create($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $validatedRequest,
        ]));
    }

    public function show($id)
    {
        $house = House::where('id', $id)
            ->with(['residentHouses' => function ($query) {
                $query->withTrashed()->with(['residentialStatus', 'resident']);
            }])
            ->first();

        return response(json_encode([
            'success' => true,
            'message' => 'Data hunian',
            'data' => $house
        ]));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'house_number' => 'max:4',
            'description' => 'max:255',
            'house_image' => 'image',
            'active' => '',
        ], [
            'house_number.required' => 'Mohon input nomor rumah',
            'house_number.max' => 'Nomor rumah terlalu panjang (maks. 4 digit)',
            'description.max' => 'Teks deskripsi terlalu panjang (maks. 255 karakter)',
            'house_image.image' => 'Mohon hanya input file gambar',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $house = House::where('id', $id)->first();

        $duplicateHouseNumber = House::where('house_number', $request->house_number)
            ->where('id', '!=', $id)
            ->first();

        if ($duplicateHouseNumber != null) {
            return response(json_encode([
                'success' => false,
                'message' => 'Nomor rumah telah terdaftar',
                'data' => null
            ]), 422);
        }

        if (!$request->active) {
            $checkHouse = House::where('id', $id)->with('residentHouses')->first();

            if ($checkHouse->has_resident) {
                return response(json_encode([
                    'success' => false,
                    'message' => 'Hunian tidak dapat di-nonaktifkan karena masih memiliki penghuni',
                    'data' => null
                ]), 422);
            }
        }

        $validatedRequest = $validator->validated();

        if ($request->hasFile('house_image')) {
            $validator = Validator::make($request->all(), [
                'house_image' => 'image',
            ], [
                'house_image.image' => 'Mohon hanya input file gambar',
            ]);

            if ($validator->fails()) {
                return response(json_encode([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'data' => null
                ]), 422);
            }

            $houseImage = $request->file('house_image');
            $houseImage->storeAs('public/images/houses/', $houseImage->hashName());
            $validatedRequest['house_image'] = $houseImage->hashName();

            if (basename($house->house_image) != 'default.jpg') {
                Storage::delete('public/images/houses/' . basename($house->house_image));
            }
        } else {
            $validatedRequest['house_image'] = basename($house->house_image);
        }

        if (is_null($validatedRequest['description'])) {
            $validatedRequest['description'] = '';
        }

        House::where('id', $id)->update($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $validatedRequest
        ]));
    }
}
