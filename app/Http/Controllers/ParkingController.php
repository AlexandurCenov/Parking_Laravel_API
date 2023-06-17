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

        $totalBill = $this->calculateParkingBill($request);

        return response()->json([
            'message' => 'Your parking bill is: ' . $totalBill . 'lv.',
        ], 200);
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

    /**
     * Calculate parking bill.
     * 
     * @param Request $request
     * @return number
     */
    private function calculateParkingBill(Request $request)
    {
        $totalBill = 0;

        // Get vehicle by registration number with category and card relations
        $vehicle = Vehicle::with(['category', 'card'])
            ->where('registration_number', $request->registration_number)
            ->get();

        // Get parking options    
        $parking = Parking::find(1);
        $dayTariffStart = Carbon::createFromFormat("H:i:s", $parking->day_shift_start);
        $dayTariffEnd = Carbon::createFromFormat("H:i:s", $parking->day_shift_end);

        // Set vehicle entry date and present date
        $vehicleEntryDateTime = Carbon::createFromDate($vehicle[0]->entered_on);
        $presentDateTime = Carbon::now();
        $presentHoursLeft = ceil($presentDateTime->diffInMinutes($vehicleEntryDateTime) / 60);

        // Rounding up the total hours
        $totalHours = ceil($vehicleEntryDateTime->diffInMinutes($presentDateTime) / 60);

        // Find total days and total hours left if total hours are equal or above 24h.
        if ($totalHours >= 24) {
            $totalDays = intval($totalHours / 24);
            $presentHoursLeft = $totalHours % 24;
            $totalBill += ceil($dayTariffEnd->diffInMinutes($dayTariffStart) / 60) * $totalDays * $vehicle[0]->category->day_tariff;
            $totalBill += (($totalDays * 24) - (ceil($dayTariffEnd->diffInMinutes($dayTariffStart) / 60) * $totalDays)) * $vehicle[0]->category->night_tariff;
            
            // Adding full days
            $vehicleEntryDateTime->addDays($totalDays);
        }

        // Check if entry time is before day tariff start
        if ($vehicleEntryDateTime->lt($dayTariffStart)) {
            // When exit time is before day tariff start
            if ($presentDateTime->lt($dayTariffStart)) {
                $totalBill += $presentHoursLeft * $vehicle[0]->category->night_tariff;
            }

            // When exit time is in day tariff range
            if ($presentDateTime->gte($dayTariffStart) && $presentDateTime->lte($dayTariffEnd)) {
                $totalBill += ceil($presentDateTime->diffInMinutes($dayTariffStart) / 60) * $vehicle[0]->category->day_tariff;
                $totalBill += ceil($dayTariffStart->diffInMinutes($vehicleEntryDateTime) / 60) * $vehicle[0]->category->night_tariff;
            }

            // When exit time is after day tariff end
            if ($presentDateTime->gt($dayTariffEnd)) {
                $totalBill += ceil($dayTariffEnd->diffInMinutes($dayTariffStart) / 60) * $vehicle[0]->category->day_tariff;
                $totalBill += ($presentHoursLeft - ceil($dayTariffEnd->diffInMinutes($dayTariffStart) / 60)) * $vehicle[0]->category->night_tariff;
            }
        }

        // Check if entry time is in day tariff range
        if ($vehicleEntryDateTime->gte($dayTariffStart) && $vehicleEntryDateTime->lte($dayTariffEnd)) {
            // When exit time is in day tariff range
            if ($presentDateTime->lte($dayTariffEnd)) {
                $totalBill += $presentHoursLeft * $vehicle[0]->category->day_tariff;
            }

            // When exit time is after day tariff range
            if ($presentDateTime->gt($dayTariffEnd)) {
                $totalBill += ceil($dayTariffEnd->diffInMinutes($vehicleEntryDateTime) / 60) * $vehicle[0]->category->day_tariff;
                $totalBill += ceil($presentDateTime->diffInMinutes($dayTariffEnd) / 60) * $vehicle[0]->category->night_tariff;
            }
        }

        // Check if entry time is after day tariff range
        if ($vehicleEntryDateTime->gt($dayTariffEnd)) {
            $totalBill += $presentHoursLeft * $vehicle[0]->category->night_tariff;
        }

        // Check if vehicle have discount card
        if ($vehicle[0]->card !== null) {
            $totalBill -= $totalBill * $vehicle[0]->card->discount;
        }

        return $totalBill;
    }
}
