<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class symptoms extends Controller
{
    public function addSymptoms(Request $request){
        Log::info($request);
        $sympotms = ['Temperature','Cough','Smell','Breathing'];
        $result = true;
        $UserCount = DB::table('User')->where('User_id', (int)$request->id)->count();
        if($UserCount >= 1){ 
            foreach ($sympotms as $sympotm) {
            	   $sympotmId   = DB::table('Symptom')
                        ->where('Disease', $sympotm)
                        ->first();
                        
                if($request->$sympotm == 'true'){
                    
                 	$db_result = DB::table('Diagnostic_history')->insert(
                        ['date' => $request->Date , 'User_id' => (int)$request->id,'Symptom_id'=>$sympotmId->Symptom_id,"Status"=>true]
                    );
                    if(!$db_result){
                        $result = false;
                    }
                
                    
                }else{
                	$db_result = DB::table('Diagnostic_history')->insert(
                        ['date' => $request->Date , 'User_id' => (int)$request->id,'Symptom_id'=>$sympotmId->Symptom_id,"Status"=>false]
                    );
                    if(!$db_result){
                        $result = false;
                    }

                }
            }
            if($result){
                $checkin = DB::table('Checkin_history')->insert(
                    ['date' => $request->Date , 'User_id' => (int)$request->id]
                );
                return response()->json([
                    'success' => 'true',
                ]);
            }else{
                return response()->json([
                    'success' => 'false',
                ]);
            }
        }else{
            return response()->json([
                'success' => 'false',
                'msg'      =>'Invlaid User id'
            ]);
        }


    }
}
