<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
class OtcController extends Controller
{
    public function generateOTC(request $request){
        $date = date('d-m-Y');
        $userId = (int)$request->user_id;
        $user = DB::table('User')->where('User_id','=',$userId)->first();// user details against user_id
        if($user != ''){
            $hash = md5($user->hash);  //encrypted orio scret
            $get_date = $user->created_at;  //get current date
                 
            $date1 = $get_date;
            $date2 = $date;
            
            function dateDiff($date1, $date2)
            {
            $date1_ts = strtotime($date1);
            $date2_ts = strtotime($date2);
            $diff = $date2_ts - $date1_ts;
            return round($diff / 86400);
            }

            $dateDiff = dateDiff($date1, $date2);        
            //dd($dateDiff);

            $timestamp = dechex(Carbon::now()->timestamp); //current timestamp
            //dd($timestamp);
            $chunked_hash = explode("\n",chunk_split($hash, 4)."\n") ;
            if(count($chunked_hash) >= 1){
                $merged_str =$chunked_hash[0]; 
                for ($x = 0; $x <= strlen($timestamp)-1; $x++) {
                    for ($i = $x+1; $i <= count($chunked_hash)-1; $i++) {
                        $merged_str = $merged_str.$timestamp[$x];
                        if($chunked_hash[$i] != ''){
                            $merged_str = $merged_str.$chunked_hash[$i];
                        }
                        break;
                    }
                }
            }
            $Otc =  preg_replace('/[^A-Za-z0-9\-]/', '',$merged_str);
            //console.log($Otc);
            return response()->json([
                'success' => true,
                'OTC' => $Otc,
                'No of Days' => $dateDiff
            ]);

        }else{
            return response()->json([
                'success' => false,
                'msg' => 'Invalid user'
            ]);
        }
    }
}