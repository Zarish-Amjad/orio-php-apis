<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;

class subdelegateController extends Controller
{
    public function registration(request $request){
        try {
            
            Log::info($request);
            $name                              =   $request->name;
            $representative_name               =   $request->representative_name;   
            $representative_email              =   $request->representative_email;
            $email                             =   $request->email;   
            $address                           =   $request->address;
            $contact_no                        =   $request->contact_no;
            $country                           =   $request->country;
            $department_identification_number  =   $request->department_identification_number;
            if(!empty($request->verification_status)){
                $verification_status	       =  filter_var($request->verification_status, FILTER_VALIDATE_BOOLEAN) ;
            }else{
                $verification_status = false;
            }
            if($name == '' || $representative_email == '' || $representative_name == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $result =DB::table('subdelegate')->insertGetId(
                            ['name' => $name,'representative_name' => $representative_name,'representative_email' => $representative_email,'email' => $email,
                            'address' => $address,'contact_no' => $contact_no,'country_id' => $getCountry->id,'department_identification_number' => $department_identification_number,'verification_status' => $verification_status
                            ]
                );
                return response()->json([
                    'success' => true,
                    'subdelegate_id' => $result
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid countryName'
                ]);
            }
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
    public function update(request $request){
        try {
            $name                   =   $request->name;
            $representative_name    =   $request->representative_name;   
            $representative_email   =   $request->representative_email;
            $email                  =   $request->email;   
            $address                =   $request->address;
            $contact_no             =   $request->contact_no;
            $country                =   $request->country;
            $department_identification_number  =   $request->department_identification_number;
            if(!empty($request->verification_status)){
                $verification_status	       =  filter_var($request->verification_status, FILTER_VALIDATE_BOOLEAN) ;
            }else{
                $verification_status = false;
            }
            if($name == '' || $representative_email == '' || $representative_name == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $result =DB::table('subdelegate')
                ->where('id', (int)$request->id)
                ->update(
                            ['name' => $name,'representative_name' => $representative_name,'representative_email' => $representative_email,'email' => $email,
                            'address' => $address,'contact_no' => $contact_no,'country_id' => $getCountry->id,'department_identification_number' => $department_identification_number,'verification_status' => $verification_status
                            ]
                );
                return response()->json([
                    'success' => true,
                    'msg' => 'Updated sucessfully'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid countryName'
                ]);
            }
            
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
    public function subdelegateByCountry(request $request){
        try {
            $country     =   $request->country;
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $getSubdelegates =DB::table('subdelegate')->select('id', 'name')->where('country_id', $getCountry->id)->get();
                if(count($getSubdelegates) > 0){
                    return response()->json([
                        'success' => true,
                        'msg' => json_decode($getSubdelegates,true)
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'msg' => 'No result found'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid Country Name'
                ]);
            }
           
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
            $result =DB::table('subdelegate')
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
    public function ListOfUsers(request $request){
        try {
            $id        =   (int)$request->id;
            if($id == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('subdelegate_user')
                        ->where('subdelegate_id', (int)$request->id)
                        ->join('dip_user', 'subdelegate_user.dipuser_id', '=', 'dip_user.id')
                        ->select('dip_user.id','dip_user.username','subdelegate_user.date')
                        ->get();
                       
            if(count($result) > 0){
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
    public function unverifiedList(request $request){
        try {
            $country     =   $request->country;
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $getSubdelegates =DB::table('subdelegate')->select('id', 'name','address','contact_no')->where('country_id', $getCountry->id)->where('verification_status',0)->get();
                if(count($getSubdelegates) > 0){
                    return response()->json([
                        'success' => true,
                        'msg' => json_decode($getSubdelegates,true)
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'msg' => 'No result found'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid Country Name'
                ]);
            }
           
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
    public function unverifiedCount(request $request){
        try {
            $country     =   $request->country;
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $getSubdelegates =DB::table('subdelegate')->select('id', 'name','address','contact_no')->where('country_id', $getCountry->id)->where('verification_status',0)->get();
                return response()->json([
                    'success' => true,
                    'msg' => count($getSubdelegates)
                ]);
                
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid Country Name'
                ]);
            }
           
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
    public function verify(request $request){
        try {
           
            $verification_status	=  filter_var($request->isVerified, FILTER_VALIDATE_BOOLEAN) ;
            if($verification_status == ''){
                return response()->json([
                    'success' => false,
                    'msg' => 'Missing or invalid parameters'
                ]);
            }
            $result =DB::table('subdelegate')
                ->where('id', (int)$request->id)
                ->update(
                        ['verification_status' => $verification_status]
                );
            return response()->json([
                'success' => true,
            ]);
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
    public function verifiedList(request $request){
        try {
            $country     =   $request->country;
            $getCountry   = DB::table('countries')
            ->where('name', $country)
            ->first();
            if(!empty($getCountry)){
                $getSubdelegates =DB::table('subdelegate')->select('id', 'name','address','contact_no')->where('country_id', $getCountry->id)->where('verification_status',1)->get();
                if(count($getSubdelegates) > 0){
                    return response()->json([
                        'success' => true,
                        'msg' => json_decode($getSubdelegates,true)
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'msg' => 'No result found'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid Country Name'
                ]);
            }
           
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong'
            ]);
        }
    }
   
}
