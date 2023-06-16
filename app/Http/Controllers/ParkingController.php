<?php

namespace App\Http\Controllers;

use App\Models\DiscountCard;
use App\Models\Parking;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParkingController extends Controller
{
    /**
     * Show free parking spaces.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSpaces()
    {
        return ["Free spaces" => Parking::find(1)->left_places];
    }

    /**
     * Register a new vehicle.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function enter(Request $request)
    {
        // validate input data
        $validator =  Validator::make($request->all(), [
            'registration_number' => 'required|min:6',
            'category' => 'required',
        ]);
    
        // when validation fail
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors()
            ], 401);
        }

        // check if already have vehicle with same registration number
        $checkSameVehicle = Vehicle::where('registration_number', $request->registration_number)->get();
        if ($checkSameVehicle->isNotEmpty()) {
            return response()->json([
                'message' => 'Already have vehicle with that registration number in the parking!',
            ], 401);
        }

        // get vehicle category
        $getCategory = VehicleCategory::where('name', $request->category)->get();

        // Check if there are free spaces on the parking
        $freeParkingSpaces = Parking::find(1);
        if ($freeParkingSpaces->left_places < $getCategory[0]->number_of_places) {
            return response()->json([
                'message' => 'There are no available slots for your vehicle!',
            ], 401);
        }
        
        // get discount card if exist on input data
        if (isset($request->discount_card) && $request->discount_card != '') {
            $getDiscountCard = DiscountCard::where('name', $request->discount_card)->get();
        }    

        // register new vehicle in the parking
        $vehicle = new Vehicle();
        $vehicle->registration_number = $request->registration_number;
        $vehicle->vehicle_category_id = $getCategory[0]->id;
        $vehicle->discount_card_id = $getDiscountCard[0]->id;
        $vehicle->save();

        // Remove free slot from Parking
        $freeParkingSpaces->left_places -= $getCategory[0]->number_of_places;
        $freeParkingSpaces->save();

        return response()->json([
            'message' => 'Your vehicle was succesfully registered in parking.',
        ], 200);
    }
}
