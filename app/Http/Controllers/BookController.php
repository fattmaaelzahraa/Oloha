<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function bookTicket(Request $request, $event_id): JsonResponse
    {
        $user = $request->user();
        if (!$user)
        {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        DB::table('books')->insert([
            'user_id' => $user->id,
            'event_id' => $event_id,
            'quantity' => $request->quantity,
            'first_name_reciever' => $request->first_name_reciever,
            'last_name_reciever' => $request->last_name_reciever,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'ticket_type' => $request->ticket_type,
            'payment_method' => $request->payment_method,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

//        $ticket = DB::table('books')
//            ->where('user_id', $user->id)
//            ->where('event_id', $event_id)
//            ->orderBy('created_at', 'desc')
//            ->first();

        $ticket = DB::table('books')
            ->join('events', 'books.event_id', '=', 'events.id')
            ->where('books.user_id', $user->id)
            ->where('books.event_id', $event_id)
            ->orderBy('books.created_at', 'desc')
            ->select('books.*', 'events.*')
            ->first();

        return response()->json([
            'message' => 'Your ticket is being booked, please wait',
            'ticket_data' => $ticket
        ]);
    }
}
