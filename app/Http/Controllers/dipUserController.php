<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;
class dipUserController extends Controller
{
    public function registration(request $request){
        try {
            Log::info($request);
            $username        =   $request->username;
            $dob             =   $request->dob;
            $gender          =   $request->gender;
            $email           =   $request->email;
            $doses_taken     =   $request->doses_taken;   
            $infected_status =   $request->infected_status;
            $immunity_status =   $request->immunity_status;   
            $qr_code         =   $request->qr_code;
            // if($username == '' || $dob == '' || $gender == '' || $email == '' || $doses_taken == '' || $infected_status == '' || $qr_code == '' || $immunity_status == ''){
            //     return response()->json([
            //         'success' => false,
            //         'msg' => 'Missing or invalid parameters'
            //     ]);
            // }
           
            $result =DB::table('dip_user')->insertGetId(
                        ['username' => $username,'dob' => $dob,'address' => $request->address,'email' => $email,'phone_no' => $request->phone_no,
                        'gender' => $gender, 'dose_taken' => $doses_taken,'qr_code'=> $qr_code, 'infected_status' => $infected_status, 'immunity_status' => $immunity_status
                        ]
            );
            return response()->json([
                'success' => true,
                'user_id' => $result
            ]);
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
        
    }
    public function update(request $request){
        try {
            $username        =   $request->username;
            $dob             =   $request->dob;
            $gender          =   $request->gender;
            $email           =   $request->email;
            $doses_taken     =   $request->doses_taken;   
            $infected_status =   $request->infected_status;
            $immunity_status =   $request->immunity_status;   
            
            if($username == '' || $dob == '' || $gender == '' || $email == '' || $doses_taken == '' || $infected_status == '' || $immunity_status == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('dip_user')
                        ->where('id', (int)$request->id)
                        ->update(
                            ['username' => $username,'dob' => $dob,'address' => $request->address,'email' => $email,'phone_no' => $request->phone_no,
                                'gender' => $gender, 'dose_taken' => $doses_taken, 'infected_status' => $infected_status, 'immunity_status' => $immunity_status
                            ]
                        );
            
                return response()->json([
                    'success' => true,
                    'msg' => 'Updated Successfully'
                ]);
            
        
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
        
    }
    public function getInfo(request $request){
        try {
            $id        =   (int)$request->id;
        
            
            if($id == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('dip_user')->select('id','qr_code')
                        ->where('id', (int)$request->id)
                        ->get();
            if(!empty($result)){
                return response()->json([
                    'success' => true,
                    'msg' => json_decode($result,true)
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'No result found'
                ]);
            }
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
        
    }
    public function ListOfSubdelegates(request $request){
        try {
            $id        =   (int)$request->id;
            if($id == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('subdelegate_user')
                        ->where('dipuser_id', (int)$request->id)
                        ->join('subdelegate', 'subdelegate_user.subdelegate_id', '=', 'subdelegate.id')
                        ->select('subdelegate.name','subdelegate.address','subdelegate.contact_no','subdelegate_user.date')
                        ->get();
            if(!empty($result)){
                return response()->json([
                    'success' => true,
                    'msg' => json_decode($result,true)
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'No result found'
                ]);
            }
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
        
    }
    public function LinktoSubdelegates(request $request){
        try {
            $subdelegate_id        =   (int)$request->subdelegate_id;
            $user_id             =   (int)$request->user_id;
            $date          =   $request->date;
              
            
            if($subdelegate_id  == '' || $user_id == '' || $date == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('subdelegate_user')
                    ->where('subdelegate_id',$subdelegate_id)
                    ->where('dipuser_id',$user_id)
                    ->get();
            if(count($result) > 0){
                DB::table('subdelegate_user')
                ->where('subdelegate_id',$subdelegate_id)
                            ->where('dipuser_id',$user_id)
                            ->update(
                                ['date' => $date]
                            );
            }else{
                DB::table('subdelegate_user')->insert(['subdelegate_id' => $subdelegate_id,'dipuser_id' => $user_id,'date' => $date]);
              
            }
           
            
                return response()->json([
                    'success' => true,
                    
                ]);
            
        
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                
            ]);
        }
        
    }
}
