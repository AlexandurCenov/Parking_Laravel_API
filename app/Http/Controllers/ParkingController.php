<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use App\Models\Parking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        // Get vehicle by registration number with category and card relations
        $vehicle = Vehicle::with(['category', 'card'])
            ->where('registration_number', $request->registration_number)
            ->get();

        // Set vehicle entry date and present date
        $vehicleEntryDateTime = Carbon::createFromDate($vehicle[0]->entered_on);
        $presentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        // Rounding up the total hours
        $totalHours = ceil($vehicleEntryDateTime->diffInMinutes($presentDateTime) / 60);

        // Find total days and total hours left if total hours are equal or above 24h.
        if ($totalHours >= 24) {
            $totalDays = intval($totalHours / 24);
            $todayHoursLeft = $totalHours % 24;



        }

        // if ($totalHours < 24) {
        //     $totalDays = intval($totalHours / 24);
        //     $todayHoursLeft = $totalHours % 24;
        // }


        
        echo "<pre>";
        print_r($vehicleEntryDateTime->toTimeString());
        echo "<pre>";
        print_r($totalDays);
        echo "<pre>";
        print_r($todayHoursLeft);
        die();
    
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
            'category' => [
                'required',
                Rule::in(['Car', 'Motor', 'Van', 'Bus', 'Truck']),
            ],
            'discount_card' => [
                'nullable',
                'string',
                Rule::in(['Silver', 'Gold', 'Platinum']),
            ],
            'entered_on' => 'nullable|date_format:Y-m-d H:i:s'
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
        $getCategory = Category::where('name', $request->category)->get();

        // Check if there are free spaces on the parking
        $freeParkingSpaces = Parking::find(1);
        if ($freeParkingSpaces->left_places < $getCategory[0]->number_of_places) {
            return response()->json([
                'message' => 'There are no available slots for your vehicle!',
            ], 401);
        }
        
        // get discount card if exist on input data
        if (isset($request->discount_card) && $request->discount_card != '') {
            $getDiscountCard = Card::where('name', $request->discount_card)->get();
        }    

        // register new vehicle in the parking
        $vehicle = new Vehicle();
        $vehicle->registration_number = $request->registration_number;
        $vehicle->category_id = $getCategory[0]->id;
        $vehicle->card_id = $getDiscountCard[0]->id ?? null;

        // get vehicle entered_on if exist on input data
        if (isset($request->entered_on) && $request->entered_on != '') {
            $vehicle->entered_on = $request->entered_on;
        }

        $vehicle->save();

        // Remove free slot from Parking
        $freeParkingSpaces->left_places -= $getCategory[0]->number_of_places;
        $freeParkingSpaces->save();

        return response()->json([
            'message' => 'Your vehicle was succesfully registered in parking.',
        ], 200);
    }
}
