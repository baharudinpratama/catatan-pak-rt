<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();

        return response(json_encode([
            'success' => true,
            'message' => 'Data pengeluaran',
            'data' => $expenses,
        ]));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:255',
            'amount' => 'required',
            'date' => 'required|date',
        ], [
            'description.required' => 'Mohon input deskripsi',
            'amount.required' => 'Mohon input nilai transaksi',
            'date.required' => 'Mohon input tanggal transaksi',
            'date.date' => 'Tanggal tidak dikenali',
        ]);

        if ($validator->fails()) {
            return response(json_encode([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]), 422);
        }

        $validatedRequest = $validator->validated();

        Expense::create($validatedRequest);

        return response(json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $validatedRequest,
        ]));
    }
}
