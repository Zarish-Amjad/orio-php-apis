<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;
use DB;
class metadataController extends Controller
{
    public function insert(request $request){
        //inserting data in db for storing last login details
        $checkUser = DB::table('Login_detail')->where("user_id","=",(int)$request->user_id)->first();
        if($checkUser == ''){
            $result =DB::table('Login_detail')->insert(
                ['user_id' => (int)$request->user_id,'Ad_ID' => $request->Ad_ID,'time' => $request->time,'date' => $request->date,'location'=>$request->location,'model'=>$request->model]
            );
            if($result){
                return response()->json([
                    'success' => true,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
        }else{
            $result =DB::table('Login_detail')->where("user_id","=",(int)$request->user_id)->update(
                ['Ad_ID' => $request->Ad_ID,'time' => $request->time,'date' => $request->date,'location'=>$request->location,'model'=>$request->model]
            );
            return response()->json([
                'success' => true,
            ]);
        }
     
        
    }
    //verfication of mac address
    public function verifyAd_ID(request $request){
        $user_id =(int)$request->user_id;
        $Ad_ID = $request->Ad_ID;
        $result =DB::table('Login_detail')->where('User_id','=',$user_id)->where('Ad_ID','=',$Ad_ID)->orderBy('date', 'DESC')->first();
        
        if($result != ''){
            return response()->json([
                'success' => true,
                'result'  => 'verified'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'result'  => 'unverified'
            ]);
        }
    }
    //verify last login 
    public function verifyLastLogin(request $request){

        $user_id        = (int)$request->user_id;
        $Ad_ID           = $request->Ad_ID;
        $date           = $request->date;
        $location       = $request->location; 
        $model          = $request->model;
        $percent        = null;
        $result =DB::table('Login_detail')->where('User_id','=',$user_id)->orderBy('date', 'DESC')->first();
        if($result != ''){
            // if($result->Ad_ID == $Ad_ID){
            //    $Ad_IDVerify = 'verified';
            // }else{
            //     $Ad_IDVerify = 'unverified';
            // }
            if($result->date != null){
                $dbdate =explode(" ",$result->date);
                if($date == $dbdate[0]){
                    $dateVerify = 'verified';
                }else{
                    $dateVerify = 'unverified';
                }
            }
            chdir('/var/www/html/api_orio/Face_recog_v2');
            if($location != '' && $model != ''){ 
              // dd("python3 string_compare.py '".$location."' '".$result->location."' '".$model."' '".$result->model."' 2>&1");
               $totalPercentage = shell_exec("python3 string_compare.py '".$location."' '".$result->location."' '".$model."' '".$result->model."' 2>&1");
               // similar_text($location, $result->location, $locationPercentage);
               $percent = explode("\n",$totalPercentage);
            }
            // if($model != ''){ 
            //     // similar_text($model, $result->model, $modelPercentage);
            //     $modelPercentage = shell_exec("python3 string_compare.py '".$model."' '".$result->model."'");
            // }else{
            //     $modelPercentage = null;
            // }
           
            return response()->json([
                'success'       => true,
                'date'          => $dateVerify,
                'location'      => (double)$percent[0],
                'model'         => (double)$percent[1]
            ]);
        }else{
            return response()->json([
                'success' => false,
                'result'  => 'No result found'
            ]);
        }
    }
    public function getAd_ID(request $request){

        $user_id        = (int)$request->user_id;
        $result =DB::table('Login_detail')->where('User_id','=',$user_id)->orderBy('date', 'DESC')->first();
        if($result != ''){
            return response()->json([
                'success' => true,
                'Ad_ID'  => $result->Ad_ID
            ]);
        }else{
            return response()->json([
                'success' => false,
                'result'  => 'No result found'
            ]);
        }
    }
  
}
