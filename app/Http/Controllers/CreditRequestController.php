<?php

namespace App\Http\Controllers;

use App\Models\CreditRequest;
use Illuminate\Http\Request;
use DB;

class CreditRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $creditRequest = CreditRequest::all();
            return response()->json($creditRequest);

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request){
                $creditRequest = new CreditRequest();
                $creditRequest->create($request->all());
                return ["mensaje" => "solicitud de credito creada con exito"];
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CreditRequest $creditRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditRequest $creditRequest)
    {
        //
    }
}
