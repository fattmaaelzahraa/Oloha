<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function all_experiences(): JsonResponse
    {
        $experiences = Experience::all();

        return response()->json([
            'status' => true,
            'message' => 'experiences retrieved successfully',
            'experiences' => $experiences
        ]);
    }


    public function Get_Experience_Data(Request $request, $id): JsonResponse
    {
        $experience = DB::table('experiences')
            ->where('experiences.id', '=', $id)
            ->first();
        //
        //php artisan make:seeder ExperiencesTableSeeder

        if ($experience)
        {
            $experience_photo_url = url('storage/' . $experience->experience_photo);

            return response()->json([
                'status' => true,
                'message' => 'Experience data retrieved successfully',
                'experience_photo_url' => $experience_photo_url,
                'data' => $experience
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Experience not found'
            ], 404);
        }

    }
}
