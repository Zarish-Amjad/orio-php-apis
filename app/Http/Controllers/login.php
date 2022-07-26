<?php
namespace App\Http\Controllers;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;

class login extends Controller
{
    public function facerecognition(Request $request)
    {   
        $userId = (int)$request->user_id; //user_id
        if($request->hasFile('image'))
        {   
            $imageName = time() . '.' . $request->image->extension(); //getting image name
            $request->image->move('/var/www/html/api_orio/Face_recog_v2/loginUpload', $imageName); //upload image
            chdir('/var/www/html/api_orio/Face_recog_v2'); //change current directory
            //execute python code
            $hash = md5_file('./Registered/'.$userId.".jpg"); //creating md5 hash of image
            $secret = shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/MPC_Algo/shamir_secret.py " . $hash);
            $recognitionResult = shell_exec("python3 simple_auth.py " . $userId . " /var/www/html/api_orio/Face_recog_v2/loginUpload/" . $imageName);
            //Checking Phrase against user
            $check = false;
            $checkphrase = DB::table('phrase')->select('phrase')->where('user_id', '=', $userId)->first();
            if($checkphrase != null)
            {   
                $check = true;
            }
            //No Face Found
            else
            {
                return response()
                    ->json(['Message' => 'No Face Found', 'status' => $check]);
            }
            //User Info
            $user = DB::table('User')->where('user_id', '=', $userId)->first();
            if($user != null)
            {
                //Getting User Info From mongo DB
                $url      =  "3.140.132.65:3083/v1/user/user_info";
                $data     =  ['_id' => $user->mongo_id, 'secret' => $secret];
                Log::info(json_encode($data));
                $userInfo =  json_decode(getPOSTRequest($url, json_encode($data)));
                Log::info(json_encode($userInfo));
                //dd(json_encode($data));
                if(!empty($userInfo)){
                    if($userInfo->status)
                    {
                        if($recognitionResult !== null)
                        {
                            $image = explode("\n", $recognitionResult);
                            if($image[0] == "True")
                            {
                                return response()->json([
                                    'success' => true, 
                                    'msg' => 'verified', 
                                    'phrase available' => $check, 
                                    'blockchain_address' => $userInfo->result->userAddress,
                                    'wallet_id' => $userInfo->result->id,
                                    'btc' => $userInfo->result->btc,
                                    'avax' => $userInfo->result->avax,
                                    'xlm' => $userInfo->result->xlm,
                                    'eth' => $userInfo->result->eth,
                                    'eth' => $userInfo->result->eth,
                                    'token' => $userInfo->token,
                                ]);
                            }
                            else{
                                return response()
                                    ->json(['success' => false, 'msg' => 'Face not matched']);
                            }
                        }
                        else{
                            return response()
                                ->json(['success' => false , 'msg' => 'No face found']);
                        }
                    }
                    else{
                        return response()
                                ->json(['success' => false, 'msg' => 'No Data From BlockChain']);
                    }
                }else{
                    return response()
                                ->json(['success' => false, 'msg' => 'Blockchain error']);
                }
                
            }
            else{
                return response()
                    ->json(['success' => false, 'msg' => 'No User Found']);
            }
        }
        else{
            return response()
                ->json(['success' => false, 'msg' => 'No Image Found']);
        }
    }

    //verify unique username
    public function verifyHanlder(request $request)
    {
        $handler = $request->user_handler;
        $user = DB::table('User')->where('user_handler', '=', $handler)->first();
        if($user != '')
        {
            $result = DB::table('Login_detail')->where('User_id', '=', $user->User_id)
                ->orderBy('date', 'DESC')
                ->first();
            if($result != '')
            {
                return response()->json(['success' => true, 'result' => 'verified', 'user_id' => (string)$user->User_id, 'Ad_ID' => $result->Ad_ID]);
            }
            else{
                return response()
                    ->json(['success' => true, 'result' => 'verified', 'user_id' => (string)$user->User_id, 'Ad_ID' => ""]);
            }
        }
        else{
            return response()
                ->json(['success' => false, 'result' => 'Unverified', 'user_id' => "", 'Ad_ID' => ""]);
        }
    }   
}