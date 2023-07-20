<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use App\Models\DisplaySetting;
use App\Models\TokenSetting;
use App\Models\TransactionType;
use App\Models\Counter;
use App\Models\Department;
use App\Http\Controllers\Common\Token_lib;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callTicket(Request $request, $ticketId)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // return response()->json(['message' => $user], 404);
        $counterId = $user->tokenSettings->first()->counter_id;
        // Find the token setting with the given ticketId, counter_id, and user_id
        $tokenSetting = TokenSetting::where([
            'counter_id' => $counterId,
            'user_id' => $userId,
        ])->first();

        if (!$tokenSetting) {
            return response()->json(['message' => 'Token setting not found'], 404);
        }
    
        if ($tokenSetting) {
            // Retrieve the token based on the token_id from the token settings
            $token = Token::find($ticketId);
    
            // Check if the token exists and belongs to the user's department
            if ($token && $token->department_id == $tokenSetting->department_id) {
                // Update the counter_id directly on the model and save it
                $token->update(['counter_id' => $counterId]);
    
                // Optionally, you can return a response indicating a successful update
                return response()->json(['message' => 'Token updated successfully'], 200);
            } else {
                // Return a response indicating that the token was not found or does not belong to the user's department
                return response()->json(['message' => 'Token not found or invalid'], 404);
            }
        } else {
            // Return a response indicating that the token setting was not found
            return response()->json(['message' => 'Token setting not found'], 404);
        }
    }

     public function getNowServingTickets()
     {
         $nowServingTickets = Token::select(
             'token.*',
             'department.name as department',
             'counter.name as counter',
             'user.firstname',
             'user.lastname'
         )
             ->leftJoin('department', 'token.department_id', '=', 'department.id')
             ->leftJoin('counter', 'token.counter_id', '=', 'counter.id')
             ->leftJoin('user', 'token.user_id', '=', 'user.id')
             ->whereNotNull('token.counter_id')
             ->where('token.status', 0)
             ->orderBy('token.created_at', 'DESC')
             ->groupBy('token.counter_id', 'token.department_id')
             ->get();
     
         $displaySetting = DisplaySetting::first();
         $message = $displaySetting ? $displaySetting->message : null;
     
         $counters = Counter::pluck('name', 'id');
     
         $data = [
             'message' => $message,
             'tickets' => [],
             'counters' => $counters,
         ];
     
         foreach ($nowServingTickets as $ticket) {
             $data['tickets'][] = [
                 'id' => $ticket->id, // Add the ticket ID to the response
                 'department' => $ticket->department,
                 'counter' => $ticket->counter,
                 'ticket_no' => $ticket->token_no,
                 'officer' => $ticket->firstname . ' ' . $ticket->lastname,
                 'created_at' => $ticket->created_at,
             ];
         }
     
         return response()->json($data);
     }
     
    public function index()
    {
        return response()->json([
            'message' => 'Hello'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'id' => $id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}