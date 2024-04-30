<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Display; 
use App\Models\DisplaySetting; 
use Carbon\Carbon;
use App\Models\Token;
use DB, Response, File, Validator;


class CronjobController extends Controller
{ 
    public function display1()
    {  
        $setting   = DisplaySetting::first();  
        $tokenInfo = DB::table('token AS t')
            ->select(
                "t.id",
                "t.token_no AS token",
                "t.client_mobile AS mobile",
                "d.name AS department",
                "c.name AS counter",
                DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                "t.status",
                "t.created_at AS date" 
            )
            ->leftJoin("department AS d", "d.id", "=", "t.department_id")
            ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
            ->leftJoin("user AS o", "o.id", "=", "t.user_id")
            ->where("t.status", "0")
            ->orderBy('t.is_vip', 'DESC')
            ->orderBy('t.id', 'ASC')
            ->offset($setting->alert_position)
            ->limit(1)
            ->get(); 

            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0) 
            {
                //nothing
                $data['status'] = false;
                $data['result'] = $tokenInfo; 
            }  

        return Response::json($data);
    }

    //counter wise 
    public function display3()
    {
        $setting = DisplaySetting::first();
        $counters = DB::table('counter')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();

        $allToken = array();
        $data     = array();
        foreach ($counters as $counter) 
        {
            $tokens = DB::table('token AS t')
                ->select(
                    "t.id",
                    "t.token_no AS token",
                    "t.client_mobile AS mobile",
                    "d.name AS department",
                    "c.name AS counter",
                    DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                    "t.status",
                    "t.created_at" 
                )
                ->leftJoin("department AS d", "d.id", "=", "t.department_id")
                ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
                ->leftJoin("user AS o", "o.id", "=", "t.user_id")
                ->where("t.counter_id", $counter->id)
                ->where("t.status", "0")
                ->offset($setting->alert_position)
                ->orderBy('t.is_vip', 'DESC')
                ->orderBy('t.id', 'ASC')
                ->limit(1)
                ->get(); 

            foreach ($tokens as $token) 
            {
                $allToken[$counter->name] = (object)array(
                    'id'         => $token->id,
                    'token'      => $token->token,
                    'department' => $token->department,
                    'counter'    => $token->counter,
                    'officer'    => $token->officer,
                    'mobile'     => $token->mobile,
                    'date'       => $token->created_at,
                    'status'     => $token->status,
                ); 
            }   
        }  

        foreach ($allToken as $counter => $tokenInfo) 
        {  
            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0) 
            {
                $data['status'] = false;
                $data['result'][] = $tokenInfo;
            }
        }

        return Response::json($data);
    }

    //department wise 
    public function display4()
    {
        $setting = DisplaySetting::first();
        $departments = DB::table('department')
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();

        $allToken = array();
        $data     = array();
        foreach ($departments as $department) 
        {
            $tokens = DB::table('token AS t')
                ->select(
                    "t.id",
                    "t.token_no AS token",
                    "t.client_mobile AS mobile",
                    "d.name AS department",
                    "c.name AS counter",
                    DB::raw("CONCAT_WS(' ', o.firstname, o.lastname) as officer"),
                    "t.status",
                    "t.created_at" 
                )
                ->leftJoin("department AS d", "d.id", "=", "t.department_id")
                ->leftJoin("counter AS c", "c.id", "=", "t.counter_id")
                ->leftJoin("user AS o", "o.id", "=", "t.user_id")
                ->where("t.department_id", $department->id)
                ->where("t.status", "0")
                ->orderBy('t.is_vip', 'DESC')
                ->orderBy('t.id', 'ASC')
                ->offset($setting->alert_position)
                ->limit(1)
                ->get(); 

            foreach ($tokens as $token) 
            {
                $allToken[$department->name] = (object)array(
                    'id'         => $token->id,
                    'token'      => $token->token,
                    'department' => $token->department,
                    'counter'    => $token->counter,
                    'officer'    => $token->officer,
                    'mobile'     => $token->mobile,
                    'date'       => $token->created_at,
                    'status'     => $token->status,
                ); 
            }   
        }  

        foreach ($allToken as $counter => $tokenInfo) 
        {  
            if (!empty($tokenInfo->mobile) && $tokenInfo->status==0) 
            {
                $data['status'] = false;
                $data['result'][] = $tokenInfo;
            }
        }

        return Response::json($data);
    }
}
