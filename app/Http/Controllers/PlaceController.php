<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
        public function GetPlaceData($id): JsonResponse
        {
        $place = DB::table('places')
            ->where('places.id', '=', $id)
            ->first();

        if ($place)
        {
            $place_photo_url = url('storage/' . $place->place_photo);

            return response()->json([
                'status' => true,
                'message' => 'Place data retrieved successfully',
                'place_photo_url' => $place_photo_url,
                'data' => $place
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Place not found'
            ], 404);
        }
    }



    public function AllPlaces(): JsonResponse
    {
        $places = Place::all();

        return response()->json([
            'status' => true,
            'message' => 'Places retrieved successfully',
            'places' => $places
        ]);

    }
}
