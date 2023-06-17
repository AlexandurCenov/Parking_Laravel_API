<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Check bill.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBill(Request $request)
    {
        // validate input data
        $validator =  Validator::make($request->all(), [
            'registration_number' => 'required|min:6',
        ]);

        // when validation fail
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors()
            ], 401);
        }

        $vehicle = Vehicle::with(['category'])->get();
        // $vehicle = Vehicle::with(['category'])->get();
        // ->where('registration_number', $request->registration_number)
        
        echo "<pre>";
        print_r($vehicle);
        die();
    
    }
}
