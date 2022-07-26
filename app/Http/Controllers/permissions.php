<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Http\Request;

class permissions extends Controller
{
    public function updatePermissions(Request $request){
        $UserCount = DB::table('User')->where('User_id', (int)$request->id)->count();
        if($UserCount >= 1){
            Log::info($request);
            $update= DB::table('User')
                    ->where('User_id', (int)$request->id)
                    ->update(['Is_started' => filter_var($request->is_started, FILTER_VALIDATE_BOOLEAN),
                            'Data_permission'=>filter_var($request->data_permission, FILTER_VALIDATE_BOOLEAN),
                            'App_metrics'=>filter_var($request->metrics_permission, FILTER_VALIDATE_BOOLEAN),
                            'Contact_tracing_enabled'=>filter_var($request->contact_tracing_enabled, FILTER_VALIDATE_BOOLEAN),
                            'Phone_no'=>$request->phone_no]);
            if($update){
                return response()->json([
                    'success' => true,
                ]);

            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'msg' => 'Invalid UserID',
            ]);
        }

    }
    public function verifyUsername(Request $request){
        $username = $request->username;
        $user = DB::table('User')->where('username', '=',$username)->first();
        if($user != ''){
            return response()->json([
                'success' => true,
                'msg' => 'Found',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'msg' => 'Not-Found',
            ]);
        }

    }
}
