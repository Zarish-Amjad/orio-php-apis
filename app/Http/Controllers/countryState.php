<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class countryState extends Controller
{
    public function getcountry(){
       $countries= DB::table('countries')->get('name');
       if($countries != null){   
            for($i=0;$i<=sizeof($countries)-1;$i++) {
                $country[$i] = $countries[$i]->name;
            }
            return response()->json([
                'success' => true,
                'countries' => $country,
                ]);
        }else{
        return response()->json([
            'success' => false,
            ]);
        } 
    }
    public function getState(Request $request){
       // dd($request->country);
        $getCountry   = DB::table('countries')
                ->where('name', $request->country)
                ->first();
        if($getCountry != null){
            $getCountryId = $getCountry->id;
            //dd($getCountryId);
            $States=[];
            $getStates   = DB::table('states')
                    ->where('country_id', $getCountryId)
                    ->get('name');
            if($getStates != null){
                for($i=0;$i<=sizeof($getStates)-1;$i++) {
                    $States[$i] = $getStates[$i]->name;
                }
                return response()->json([
                'success' => true,
                'states' => $States,
                ]);
            }else{
                 return response()->json([
                'success' => false,
                'states' => "No results found",
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                ]);
        }

    }
}