<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class getSymptomsHistory extends Controller
{
    public function getSymptomsHistory(Request $request){
        $UserCount = DB::table('User')->where('User_id', (int)$request->id)->count();
        if($UserCount >= 1){
            $currentMonth = date('Y-m-d', strtotime('today - 30 days'));
            $currentDate= date('Y-m-d');
           // dd($currentMonth);
            $data = DB::table("Diagnostic_history")
                        ->whereBetween('date', array($currentMonth, $currentDate))
                        ->where('User_id', '=',(int)$request->id)
                        ->get();
                      // dd($data);
            $dates= [];
            $completeData= [];
            
            foreach($data as $date){
                if(!in_array($date->date, $dates, true)){
                    array_push($dates,$date->date);
                }
            }
            foreach($dates as $date){
                $syptomsData = ['date'         =>   null,
                            'Temperature'  =>   false,
                            'Cough'        =>   false,
                            'Smell'        =>   false,
                            'Breathing'    =>   false
                        ];
                foreach($data as $syptoms){
                    if($date == $syptoms->date){
                        $syptomName  = DB::table('Symptom')
                        ->where('Symptom_id', $syptoms->Symptom_id)
                        ->first();
                        $syptomsData['date']=substr($syptoms->date, 0, 10);
                        if($syptoms->Status){
                            $syptomsData[$syptomName->Disease]  = true;
                        }else{
                            $syptomsData[$syptomName->Disease]  = false;
                        }
                    
                    }
                }
                array_push($completeData,$syptomsData);
            }
            if(!empty($completeData)){
                        return response()->json([
                            'success' => true,
                            'data' => $completeData,
                        ]);
                    
            }else{
                        return response()->json([
                            'success' => true,
                            'data' => 'no results found',
                        ]);
            }
        }else{
                return response()->json([
                    'success' => false,
                    'data' => 'Invalid UserID',
                ]);
            }
       
    }
}
