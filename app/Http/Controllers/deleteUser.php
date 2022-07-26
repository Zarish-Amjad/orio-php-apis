<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class deleteUser extends Controller
{
    public function deleteUser(Request $request){
        
        $UserCount = DB::table('User')->where('User_id', (int)$request->id)->count();
        if($UserCount >= 1){
            DB::table('Checkin_history')->where('User_id', (int)$request->id)->delete();
            DB::table('Diagnostic_history')->where('User_id', (int)$request->id)->delete();
            $deleteResult = DB::table('User')->where('User_id', (int)$request->id)->delete();
            if($deleteResult){
                if (file_exists( public_path('images').'/'.$request->id.'.jpeg')) {
                    unlink(public_path('images').'/'.$request->id.'.jpeg');
                }
                
                return response()->json([
                    'success' => true,
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'msg'    => 'Invalid user id'
            ]);
            
        }
        
    }
}
