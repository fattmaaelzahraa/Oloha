<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuideController extends Controller
{
    public function GetGuideData($id): JsonResponse
    {
        $guide = DB::table('guides')
            ->where('guides.id', '=', $id)
            ->first();

        if ($guide)
        {
            $guide_photo_url = url('storage/' . $guide->guide_photo);

            return response()->json([
                'status' => true,
                'message' => 'guide data retrieved successfully',
                'place_photo_url' => $guide_photo_url,
                'data' => $guide
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Guide not found'
            ], 404);
        }
    }



    public function AllGuides(): JsonResponse
    {
        $guides = Guide::all();

        return response()->json([
            'status' => true,
            'message' => 'Guides retrieved successfully',
            'guides' => $guides
        ]);

    }
}
