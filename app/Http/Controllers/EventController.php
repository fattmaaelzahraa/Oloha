<?php

namespace App\Http\Controllers;


use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function GetEventData($id): JsonResponse
    {
        $event = DB::table('events')
            ->where('events.id', '=', $id)
            ->first();

        if ($event)
        {
            $event_photo_url = url('storage/' . $event->event_photo);

            return response()->json([
                'status' => true,
                'message' => 'Event data retrieved successfully',
                'event_photo_url' => $event_photo_url,
                'data' => $event
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }
    }



    public function AllEvents(): JsonResponse
    {
        $events = Event::all();
        return response()->json([
            'status' => true,
            'message' => 'events retrieved successfully',
            'events' => $events
        ]);

    }
}
