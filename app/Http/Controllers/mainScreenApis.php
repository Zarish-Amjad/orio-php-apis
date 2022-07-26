<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class mainScreenApis extends Controller
{
    public function getTotalRegisterCount(){
    	try {
    		$UserCount = DB::table('User')->count();
			return response()->json([
               	'success' => true,
                'data' => $UserCount
            ]);
        }catch(Exception $e) {
		  return response()->json([
                    'success' => false,
                    'data' => 'Something Went Wrong'
                ]);
		}
     }
     public function dailyCheckin(){
		try {
			$date = date("Y-m-d");
			//$date="2020-11-25";
			$checkinCount = DB::table('Checkin_history')->where('date','=',$date)->get();
			$infected = [];
			$nonInfected = [];
			if(count($checkinCount) >= 1){
				foreach($checkinCount as $data){
					//echo $data->User_id;
					$history = DB::table('Diagnostic_history')->where('date','=',$date)->where('Status','=',1)->where('User_id','=',$data->User_id)->count();
					if($history >= 1){
						array_push($infected,$data->User_id);
					}else{
						array_push($nonInfected,$data->User_id);
					}
				}
				if(count($infected) >= 1){
					$infectedPercentage = (count($infected)/count($checkinCount))*100;
				}else{
					$infectedPercentage = 0;
				}
				if(count($nonInfected) >= 1){
					$noninfectedPercentage = (count($nonInfected)/count($checkinCount))*100;
				}else{
					$noninfectedPercentage = 0;
				}
				
				return response()->json([
						'success' => true,
						'data' => [	'total' => count($checkinCount),
									'infected' =>round($infectedPercentage).'%',
									'nonInfected' =>round($noninfectedPercentage).'%']
					]);
			}else{
				return response()->json([
						'success' => true,
						'data' => 'No records found'
					]);
			}
		}catch(Exception $e) {
			return response()->json([
					  'success' => false,
					  'data' => 'Something Went Wrong'
				  ]);
		}
    }
    public function getInfectedCountByStates(Request $request){
		try {
			//GET STATES LIST//
			$countryData = DB::table('countries')->where('name','=',$request->country)->first();
			$stateName = DB::table('states')->where('country_id','=',$countryData->id)->get('name');
			//GET USERS DATA AGAINST COUNTRY
			$userCountry = [];
			if($stateName != null){
				foreach($stateName as $key=>$value){
					foreach($value as $state=>$name)
					$userCountry[$name] = 0;
				}
				$userid = DB::table('Diagnostic_history')->select('User_id')->where('Status','=',1)->groupBy('User_id')->get();
				if(count($userid) >=1){
					$total=0;
					//$userCountry = [];
					foreach($userid as $id){
						$user = DB::table('User')->where('User_id','=',$id->User_id)->first();
						if($user != null){
							$state = DB::table('states')->where('id','=',$user->State_id)->first();
							//dd($state);
							if($state != null){
								$country = DB::table('countries')->where('id','=',$state->country_id)->first();
								if($country != null){
									if(strtolower($country->name) == strtolower($request->country)){
										$total++;
										if (!array_key_exists($state->name,$userCountry)){
											$c[$state->name] = 1;
										}else{
											$count = $userCountry[$state->name];
											$userCountry[$state->name] = $count+1;
										}
									}

								}
							}

						}

					}
					$mainarray= [];
					
					if(!empty($userCountry)){
						if($total !==  0){
							foreach($userCountry as $key=>$value){
								$subarray = [];
								$percantage = $value/$total*100;
								//$userCountry[$key] = (int)$percantage;
								$subarray['state'] = $key;
								$subarray['count'] = $value;
								$subarray['percentage']=round($percantage);
								array_push($mainarray,$subarray);
							}
							//dd($mainarray);
							return response()->json([
										'success' => true,
										'data' => $mainarray
									]);
						}else{
							foreach($userCountry as $key=>$value){
								$subarray = [];
								$subarray['state'] = $key;
								$subarray['count'] = $value;
								$subarray['percentage']=$value;
								array_push($mainarray,$subarray);
							}
							//dd($mainarray);
							return response()->json([
										'success' => true,
										'data' => $mainarray
									]);
						}
					}else{
							
						}
				}else{
					return response()->json([
							'success' => true,
							'data' => 'No records found'
						]);
				}
			}else{
				return response()->json([
						'success' => true,
						'data' => 'No records found'
					]);
			}
		}catch(Exception $e) {
			return response()->json([
					  'success' => false,
					  'data' => 'Something Went Wrong'
				  ]);
		}

    }
    public function addPhoneNo(Request $request){
		Log::info($request);
    	 $update= DB::table('User')
                    ->where('User_id', (int)$request->id)
                    ->update(['Phone_no' => $request->phone_no]);
                  //  dd($request->Phone_no);
           if($update){
                return response()->json([
                    'success' => true,
                ]);

            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
    }
    public function getPhoneNo(Request $request){
        Log::info($request);
         $user= DB::table('User')
                    ->where('User_id', (int)$request->id)
                    ->first();
                  //  dd($request->Phone_no);
           if($user != null){
                return response()->json([
                    'success' => true,
                    'phone_no' => $user->Phone_no
                ]);

            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
    }
}
