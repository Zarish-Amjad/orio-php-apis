<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class checkIn extends Controller
{
    public function userInfo(Request $request){
        Log::info($request);
        $UserCount = DB::table('User')->where('User_id', (int)$request->id)->count();
        if($UserCount >= 1){  
            if($request->country !== null){
                $getCountry   = DB::table('countries')
                ->where('name', $request->country)
                ->first();
                if($request->state !== null){
                    $getCountryId = $getCountry->id;
                    $getState  = DB::table('states')
                            ->where('country_id', $getCountryId)
                            ->where('name', $request->state)
                            ->first(); 
                    $state = $getState->id;
                }else{
                    $state = null;
                }
            }else{
                if($request->state !== null){
                    $getState  = DB::table('states')
                            ->where('name', $request->state)
                            ->first(); 
                    $state = $getState->id;
                }else{
                    $state = null;
                }

            }

            if($request->age !== null){
                $age = $request->age;
            }else{
                $age = null;
            }
            if($request->gender !== null){
                $gender = $request->gender;
            }else{
                $gender = null;
            }
                
                    $update= DB::table('User')
                            ->where('User_id', (int)$request->id)
                            ->update(['Gender' => $gender,'Age' => $age,'State_id' => $state]);
                
                        return response()->json([
                            'success' => true,
                        ]);
        }else{
            return response()->json([
                'success' => false,
                'msg'     => 'Invalid User id'
            ]);
        }
       
        

    }
     
}
