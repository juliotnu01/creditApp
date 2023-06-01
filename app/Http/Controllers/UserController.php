<?php

namespace App\Http\Controllers;

use App\Models\{User,CreditRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $users = User::with(
                'hasManyCreditsRequests', 
                'hasManyCreditsRequests.hasManyAmortizaciones', 
                'hasManyCreditsRequests.hasManyAmortizaciones.hasManyCompromisos', 
                'hasManyCreditsRequests.hasManyAmortizaciones.hasOneScore', 
                'hasManyCreditsRequests.belongToUser', 
                'hasOneDocument', 
                'hasOneDocument.hasManyFileDocument', 
                'hasOneDocument.hasManyFileDocument.hasOneStatusDocument', 
                'hasManyComentarios', 
                'hasManyComentarios.WhoDidComment')->where('id', "<>", Auth()->user()->id)->get();
            return response()->json($users);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function indexClientes()
    {
        try {

            $users = User::with(
                'hasManyCreditsRequests', 
                'hasManyCreditsRequests.hasManyAmortizaciones', 
                'hasManyCreditsRequests.hasManyAmortizaciones.hasManyCompromisos', 
                'hasManyCreditsRequests.hasManyAmortizaciones.hasOneScore', 
                'hasManyCreditsRequests.belongToUser', 
                'hasOneDocument', 
                'hasOneDocument.hasManyFileDocument', 
                'hasOneDocument.hasManyFileDocument.hasOneStatusDocument', 
                'hasManyComentarios', 
                'hasManyComentarios.WhoDidComment')->where('id', "<>", Auth()->user()->id)->where('raw_rol', 'cliente')->get();
            return response()->json($users);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function viewCliente($id)
    {
        try {

            $user = User::with(
                'hasManyCreditsRequests', 
                'hasManyCreditsRequests.hasManyAmortizaciones',
                'hasManyCreditsRequests.hasManyAmortizaciones.hasManyCompromisos',  
                'hasManyCreditsRequests.hasManyAmortizaciones.hasOneScore', 
                'hasManyCreditsRequests.belongToUser', 
                'hasOneDocument', 
                'hasOneDocument.hasManyFileDocument', 
                'hasOneDocument.hasManyFileDocument.hasOneStatusDocument', 
                'hasManyComentarios', 
                'hasManyComentarios.WhoDidComment')->where('id', $id)->first();
            return response()->json($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function viewClienteParaPagar($id)
    {
        try {

            $user = CreditRequest::with(
                'hasManyAmortizaciones', 
                'hasManyAmortizaciones.hasOneScore', 
                'belongToUser')->where('ine_o_identificacion_oficial', $id)->first();
            return response()->json($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Validator::make($request->toArray(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($request) {
            return User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'raw_rol' => $request['raw_rol'] ?? 'admin'
            ]);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        return DB::transaction(function () use ($request, $id) {
            return User::find($id)->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'raw_rol' => $request['raw_rol']
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}  
