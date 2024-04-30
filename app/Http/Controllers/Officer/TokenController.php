<?php
namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Token;
use App\Models\Department;
use App\Models\Counter;
use App\Models\User;
use App\Models\TransactionType;
use App\Models\TokenSetting;
use DB;

class TokenController extends Controller
{

    public function index(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));
        $counters = Counter::where('status',1)->pluck('name','id');
        $departments = Department::where('status',1)->pluck('name','id');
        $officers = User::select(DB::raw('CONCAT(firstname, " ", lastname) as name'), 'id')
            ->where('user_type',1)
            ->where('status',1)
            ->orderBy('firstname', 'ASC')
            ->pluck('name', 'id');
        $transaction_types = TransactionType::pluck('name', 'id');

        return view('backend.officer.token.list', compact('transaction_types', 'counters', 'departments', 'officers'));
    }  

    public function tokenData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'token_no',
            2 => 'department',
            3 => 'counter',
            4 => 'client_mobile',
            // 4 => 'transaction_type',
            5 => 'note',
            6 => 'status',
            7 => 'created_by',
            8 => 'created_at',
            9 => 'updated_at',
            10 => 'options',
        ]; 

        $totalData = Token::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir'); 
        $search = $request->input('search'); 
            
        if(empty($search))
        {            
            $tokens = Token::where('user_id', auth()->user()->id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else 
        { 
            $tokensProccess = Token::where(function($query)  use($search) {
                $query->where('user_id', auth()->user()->id);

                if (!empty($search['status'])) {
                    $query->where('status', '=', $search['status']);
                }
                if (!empty($search['counter'])) {
                    $query->where('counter_id', '=', $search['counter']);
                }
                if (!empty($search['department'])) {
                    $query->where('department_id', '=', $search['department']);
                }

                if (!empty($search['start_date']) && !empty($search['end_date'])) {
                    $query->whereBetween("created_at",[
                        date('Y-m-d', strtotime($search['start_date']))." 00:00:00", 
                        date('Y-m-d', strtotime($search['end_date']))." 23:59:59"
                    ]);
                }

                // if (!empty($search['transaction_types'])) {
                //     $transactionTypeId = TransactionType::where('name', 'LIKE', "%{$search['value']}%")->pluck('id')->toArray();
                //     if (!empty($transactionTypeId)) {
                //         $query->orWhereIn('transaction_type_id', $transactionTypeId);
                //     }
                // }

                if (!empty($search['value'])) {
                    if ((strtolower($search['value']))=='vip') 
                    {
                        $query->where('is_vip', '1');
                    }
                    else
                    {
                        $date = date('Y-m-d', strtotime($search['value']));
                        $query->where('token_no', 'LIKE',"%{$search['value']}%")
                            ->orWhere('client_mobile', 'LIKE',"%{$search['value']}%")
                            ->orWhere('note', 'LIKE',"%{$search['value']}%")
                            ->orWhere(function($query)  use($date) {
                                $query->whereDate('created_at', 'LIKE',"%{$date}%");
                            })
                            ->orWhere(function($query)  use($date) {
                                $query->whereDate('updated_at', 'LIKE',"%{$date}%");
                            })
                            ->orWhereHas('generated_by', function($query) use($search) {
                                $query->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE',"%{$search['value']}%");
                            }); 
                    }
                }
            });

            $totalFiltered = $tokensProccess->count();
            $tokens = $tokensProccess->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get(); 

        }

        $data = array();
        if(!empty($tokens))
        {
            $loop = 1;
            foreach ($tokens as $token)
            {  
                # complete time calculation
                $complete_time = "";
                if (!empty($token->updated_at)) {  
                    $date1 = new \DateTime($token->created_at); 
                    $date2 = new \DateTime($token->updated_at); 
                    $diff  = $date2->diff($date1); 
                    $complete_time = (($diff->d > 0) ? " $diff->d Days " : null) . "$diff->h Hours $diff->i Minutes ";
                }

                # buttons
                $options = "<div class=\"btn-group\">";
                if ($token->status == 0) {
                    $options .= "<a href=\"".url("officer/token/complete/$token->id")."\"  class=\"btn btn-success btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Complete\"><i class=\"fa fa-check\"></i></a>";
                    $options .= "<a href=\"". url("officer/token/stoped/$token->id")."\"  class=\"btn btn-warning btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Stoped\"><i class=\"fa fa-stop\"></i></a>";
                } else {
                    $options .= "<a href=\"".url("officer/token/recall/$token->id")."\"  class=\"btn btn-info btn-sm\" onclick=\"return confirm('Are you sure?')\" title=\"Call\"><i class=\"fa fa-phone\"></i></a>";
                }

                $options .= "<button type=\"button\" href=\"".url("officer/token/print")."\" data-token-id='$token->id' class=\"tokenPrint btn btn-default btn-sm\" title=\"Print\"><i class=\"fa fa-print\"></i></button>"; 
                $options .= "</div>"; 

                $data[] = [
                    'serial'     => $loop++,
                    'token_no'   => (!empty($token->is_vip)?("<span class=\"label label-danger\" title=\"VIP\">$token->token_no</span>"):$token->token_no),
                    'department' => (!empty($token->department)?$token->department->name:null),
                    'counter'    => (!empty($token->counter)?$token->counter->name:null), 
                    'client_mobile'    => $token->client_mobile. "<br/>" .(!empty($token->client)?("(<a href='".url("officer/user/view/{$token->client->id}")."'>".$token->client->firstname." ". $token->client->lastname."</a>)"):null),
                    // 'transaction_type' => (!empty($token->transactionType) ? $token->transactionType->name : null),
                    'note'       => $token->note,
                    'status'     => (($token->status==1)?("<span class='label label-success'>".trans('app.complete')."</span>"):(($token->status==2)?"<span class='label label-danger'>".trans('app.stop')."</span>":"<span class='label label-primary'>".trans('app.pending')."</span>")).(!empty($token->is_vip)?('<span class="label label-danger" title="VIP">VIP</span>'):''),
                    'created_by'    => (!empty($token->generated_by)?("<a href='".url("officer/user/view/{$token->generated_by->id}")."'>".$token->generated_by->firstname." ". $token->generated_by->lastname."</a>"):null),
                    'created_at' => (!empty($token->created_at)?date('j M Y h:i a',strtotime($token->created_at)):null),
                    'updated_at' => (!empty($token->updated_at)?date('j M Y h:i a',strtotime($token->updated_at)):null),
                    'complete_time' => $complete_time,
                    'options'    => $options
                ];  
            }
        }
            
        return response()->json([
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        ]);
    }
    public function current(Request $request)
    {  
        @date_default_timezone_set(session('app.timezone'));
        $currentDateTime = now()->format('Y-m-d H:i:s');
        $token_setting = TokenSetting::where('user_id', auth()->user()->id)->first();
    
        // Get the current date in 'Y-m-d' format
        $currentDate = now()->toDateString();
    
        $tokens = Token::whereIn('status', [0,2])
            ->where('created_at', '<=', $currentDateTime)
            ->where('department_id', $token_setting->department_id)
            ->whereDate('created_at', $currentDate) // Filter tokens created today
            ->orderBy('is_vip', 'DESC')
            ->orderBy('id', 'ASC')
            ->with('transactionType')
            ->get();

        $counters = Counter::where('status', 1)->pluck('name', 'id');
        $departments = Department::where('status', 1)->pluck('name', 'id');
        $officers = User::select(DB::raw('CONCAT(firstname, " ", lastname) as name'), 'id')
            ->where('user_type', 1)
            ->where('status', 1)
            ->orderBy('firstname', 'ASC')
            ->pluck('name', 'id');

        // Check if the request wants JSON response
        if ($request->expectsJson()) {
            // Return the data as a JSON response
            return response()->json([
                'tokens' => $tokens,
                'counters' => $counters,
                'departments' => $departments,
                'officers' => $officers,
            ]);

        } else {
            return view('backend.officer.token.current', compact('counters', 'departments', 'officers', 'tokens'));
        }
    }

    public function getCurrentTicket()
{
    // Logic to fetch the current ticket, assign it to the $ticket variable
    $tokens = Token::whereIn('status', [0, 2])->get();

    return view('backend.officer.token.current', compact('tokens'));
}


    public function viewSingleToken(Request $request)
    {
        return Token::select('token.*', 'department.name as department', 'counter.name as counter', 'user.firstname', 'user.lastname')
            ->leftJoin('department', 'token.department_id', '=', 'department.id')
            ->leftJoin('counter', 'token.counter_id', '=', 'counter.id')
            ->leftJoin('user', 'token.user_id', '=', 'user.id')
            ->where('token.id', $request->id)
            ->where('user_id', auth()->user()->id )
            ->first(); 
    }

    public function recall($id = null)
    {
        @date_default_timezone_set(session('app.timezone'));

        $token = DB::table('token AS t')
            ->select(
                "t.token_no AS token",
                // "t.client_mobile AS mobile",
                "d.name AS department",
                "c.name AS counter",
                DB::raw("CONCAT_WS(' ', u.firstname, u.lastname) AS officer"),
                "t.created_at AS date" 
            )
            ->leftJoin('department AS d', 'd.id', '=', 't.department_id')
            ->leftJoin('counter AS c', 'c.id', '=', 't.counter_id')
            ->leftJoin('user AS u', 'u.id', '=', 't.user_id')
            ->where('t.id', $id)
            ->first();

        $user = auth()->user();
        $counterId = $user->tokenSettings->first()->counter_id;

        Token::where('id', $id)
            ->update([
                'user_id' => $user->id,
                'counter_id' => $counterId,
                'updated_at' => date('Y-m-d H:i:s'), 
                'status'     => 0
            ]);

        //RECALL 
        return redirect()->back()->with('message', trans('app.recall_successfully'));
    } 
    
    public function stoped($id = null)
    {
        // Retrieve the token based on the provided ID
        $token = Token::find($id);
    
        if (!$token) {
            return redirect()->back()->with('error', trans('app.token_not_found'));
        }
    
        // Get all tokens with the same token_no and department that were created today
        $tokensToUpdate = Token::where('token_no', $token->token_no)
            ->where('department_id', $token->department_id)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->get();
    
        // Update counter_id, status and updated_at for each of the tokens
        foreach ($tokensToUpdate as $tokenToUpdate) {
            $tokenToUpdate->counter_id = null;
            $tokenToUpdate->status = 2;
            $tokenToUpdate->updated_at = now()->format('Y-m-d H:i:s');
            $tokenToUpdate->save();
        }
    
        return redirect()->back()->with('message', trans('app.update_successfully'));
    }    

    public function complete($id = null)
    {
        @date_default_timezone_set(session('app.timezone'));
        
        Token::where('id', $id)
            ->update(['updated_at' => date('Y-m-d H:i:s'), 'status' => 1]);
        return redirect()->back()->with('message', trans('app.complete_successfully'));
    } 

}

 