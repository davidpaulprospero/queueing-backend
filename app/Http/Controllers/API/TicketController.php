<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\TokenSetting;
use App\Models\TransactionType;
use App\Models\Counter;
use App\Models\Department;
use App\Http\Controllers\Common\Token_lib;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = [];

        try {
            DB::beginTransaction();

            $payload = json_decode($request->getContent(), true);

            $tokens = [];
            $ticketNumbers = [];
            $counterAssignments = [];

            foreach ($payload as $request) {
                $departmentId = $request['department'];
                $transactionTypeId = $request['service'];
                $userSubmission = $request['studentId'];

                // Determine if it's a multiple transaction
                $isMultipleTransaction = count($payload) > 1;

                // Find available counters for the department
                $availableCounters = TokenSetting::where('department_id', $departmentId)
                    ->where('status', 1)
                    ->pluck('counter_id');

                if ($availableCounters->isEmpty()) {
                    $tokens[] = [
                        'ticket' => null,
                        'message' => 'No available counter found for the department',
                    ];
                    continue;
                }

                $counterId = null;

                $transactionType = TransactionType::find($transactionTypeId);

                // Generate or reuse the ticket number for the same department and transaction type within the submission
                $ticketNumber = $this->getTicketNumber($departmentId, $transactionTypeId, $ticketNumbers, $isMultipleTransaction);

                // Get the user_id assigned to the counter
                $userId = TokenSetting::where('department_id', $departmentId)
                ->where('counter_id', $counterId)
                ->value('user_id');

                $token = new Token();
                $token->token_no = $ticketNumber;
                $token->client_mobile = null;
                $token->studentId = $request['studentId'];
                $token->transaction_type_id = $transactionTypeId;
                $token->department_id = $departmentId;
                $token->counter_id = $counterId;
                $token->user_id = $userId;
                $token->note = null;
                $token->created_by = null;
                $token->created_at = now();
                $token->status = 0;
                $token->save();

                $tokens[] = [
                    'ticket' => $token->token_no,
                    'message' => 'Token generated successfully',
                    'studentId' => $token->studentId,
                    'department' => strtoupper(Department::where('id', $departmentId)->value('name')),
                    'service' => $transactionType->name,
                    'counter' => Counter::where('id', $counterId)->value('name'),
                    'created_at' => $token->created_at,
                ];
            }

            DB::commit();

            $data['ticket'] = $tokens;

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            $data['error'] = 'An error occurred while generating tokens';
            return response()->json($data, 500);
        }
    }

    private function getCounterForTransaction($departmentId, $transactionTypeId, $userSubmission, &$counterAssignments, $availableCounters)
    {
        $transactionKey = $departmentId;

        if (!isset($counterAssignments[$transactionKey])) {
            // Find the counter with the fewest tokens
            $counterId = $this->getCounterWithFewestTokens($availableCounters);

            if (!$counterId) {
                return null;
            }

            $counterAssignments[$transactionKey] = $counterId;
        }

        return $counterAssignments[$transactionKey];
    }

    private function getTicketNumber($departmentId, $transactionTypeId, &$ticketNumbers, $isMultipleTransaction)
    {
        $departmentCode = strtoupper(Department::where('id', $departmentId)->value('key'));
        $transactionTypeCode = $isMultipleTransaction ? 'M' : 'S';
    
        // Use the current date to create a unique key for the ticket numbers
        $dateKey = date('Ymd');
    
        $ticketKey = $departmentCode . $transactionTypeCode . $dateKey;
    
        if (!isset($ticketNumbers[$ticketKey])) {
            $ticketNumbers[$ticketKey] = Token::where('department_id', $departmentId)
                ->where('transaction_type_id', $transactionTypeId)
                ->whereDate('created_at', now()->toDateString()) // Filter tickets for the current date
                ->max('token_no');
    
            if (!$ticketNumbers[$ticketKey]) {
                $ticketNumbers[$ticketKey] = $departmentCode . $transactionTypeCode . '-' . '00001';
            } else {
                $ticketNumbers[$ticketKey]++;
                $ticketNumbers[$ticketKey] = str_pad($ticketNumbers[$ticketKey], 5, '0', STR_PAD_LEFT);
                $ticketNumbers[$ticketKey] = substr($ticketNumbers[$ticketKey], -5); // Get the last 5 characters
                $ticketNumbers[$ticketKey] = $departmentCode . $transactionTypeCode . '-' . $ticketNumbers[$ticketKey];
            }
        }
    
        return $ticketNumbers[$ticketKey];
    }    


    private function getCounterWithFewestTokens($availableCounters)
    {
        $counterId = null;
        $minTokens = null;

        foreach ($availableCounters as $counter) {
            $tokenCount = Token::where('counter_id', $counter)
                ->whereIn('status', [0, 1])
                ->count();

            if ($minTokens === null || $tokenCount < $minTokens) {
                $minTokens = $tokenCount;
                $counterId = $counter;
            }
        }

        return $counterId;
    }

 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    // public function checkStudentNumber - T/F
    // public function latestTickets
}