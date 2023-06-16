<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

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
}
