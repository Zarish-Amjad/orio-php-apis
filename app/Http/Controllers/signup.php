<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use phpseclib\Crypt\RSA;
use Illuminate\Http\Request;
use DB;
//use DateTime;
//App\Http\Controllers\DateTime;
//App\Http\Controllers\DateTimeZone;
use Carbon\Carbon;
//App\Http\Controllers\curl_init();

class signup extends Controller
{
    public function uploadimage(Request $request)
    {
        $rsa = new RSA();
        $date = date('d-m-Y');
        if ($request->hasFile('image')) {
            $last = DB::table('User')->latest('User_id')->first();
            $latest_id = $last->User_id + 1;

            $hash = md5_file($request->image->path()); //creating md5 hash of image
            $imageName = time() . '.' . $request->image->extension();
            $ext = pathinfo($imageName, PATHINFO_EXTENSION);
            $request->image->move('/var/www/html/api_orio/Face_recog_v2/temp', $imageName);
            $face_registration_check = shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/simple_auth_test2.py /var/www/html/api_orio/Face_recog_v2/temp/" . $imageName . " '" . $latest_id . "' 2>&1");
            Log::info($face_registration_check);
            if (!empty($face_registration_check)) {
                if (trim($face_registration_check) == "No User Found" || trim($face_registration_check) == "Face Not Registered") {
                    $secret = shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/MPC_Algo/shamir_secret.py " . $hash);
                    //creating 512 bit key
                    $key = $rsa->createKey(512);
                    $pubKey = str_replace([
                        '-----BEGIN PUBLIC KEY-----',
                        '-----END PUBLIC KEY-----',
                        "\r\n",
                        "\n",
                    ], [
                        '',
                        '',
                        "\n",
                        ''
                    ], $key['publickey']);
                    //-----generating orio secret-----
                    $orioScret = $hash . $pubKey;
                    //-----setting encryption modes---
                    $rsa->setEncryptionMode('CRYPT_RSA_ENCRYPTION_OAEP');
                    $rsa->setHash("sha1");
                    $rsa->setMGFHash("sha1");
                    //-----loading publickey---------
                    $rsa->loadKey($request->publicKey);
                    //-----encrypting orio secret----
                    $EncryptedOrioScret = $rsa->encrypt($orioScret);
                    //-----uploading file------------


                    //-----Unique user_handler-------
                    $users = DB::table('User')->where('username', '=', $request->userName)->get();

                    if (count($users) == 0) {
                        $uniqueName = '@' . preg_replace('/\s+/', "", strtolower($request->userName)) . '1';
                    } else {
                        $count = count($users) + 1;
                        $uniqueName = '@' . preg_replace('/\s+/', "", strtolower($request->userName)) . $count;
                    }
                    //----checking uniquness of unique user handler----
                    $checkuniqueness = DB::table('User')->where('user_handler', '=', $uniqueName)->get();
                    if (count($checkuniqueness) > 0) {
                        $nameArr = explode(preg_replace('/\s+/', "", strtolower($checkuniqueness[0]->userName)), $checkuniqueness[0]->user_handler);
                        $num = (int)$nameArr[1];
                        $num = $num + 1;
                        $uniqueName = '@' . preg_replace('/\s+/', "", strtolower($request->userName)) . $num;
                    }

                    //----adding data to db-----------
                    $result = DB::table('User')->insertGetId(
                        ['Image_path' => $imageName, 'hash' => base64_encode($EncryptedOrioScret), 'userName' => $request->userName, 'user_handler' => $uniqueName, 'created_at' => $date]
                    );

                    //-----getting question _id------
                    $question_arr = json_decode($request->question, true);
                    foreach ($question_arr as $key => $value) {
                        if ($value != '') {
                            $answer_encrypt  = Crypt::encryptString($value);
                            $checkquestion = DB::table('questions')->where('question', '=', $key)->first();
                            if ($checkquestion == '') {
                                $question = DB::table('questions')->insertGetId(
                                    ['question' => $key]
                                );
                            } else {
                                $question = $checkquestion->id;
                            }

                            if ($question != '') {
                                DB::table('answers')->insert(
                                    ['user_id' => $result, 'question_id' => $question, 'answer' => $answer_encrypt]
                                );
                            }
                        }
                    }
                    if ($result !== '') {
                        //----updating image name-----
                        $update = DB::table('User')
                            ->where('User_id', $result)
                            ->where('Image_path', $imageName)
                            ->update(['Image_path' => $result . "." . $ext]);
                        if ($update) {

                            //$url = "3.140.132.65:3001/register";
                            $url = "http://3.140.132.65:3083/v1/user/register";
                            // 'secret' => $request->secret
                            $data = ['userName' => $uniqueName, 'publicKey' => $request->publicKey, 'secret' => $secret];
                            Log::info(json_encode($data));
                            $res = getPOSTRequest($url, json_encode($data));
                            $res = json_decode($res, true);
                            Log::info($res);
                            if (isset($res['status'])) {
                                if ($res['status'] == 'success') {
                                    $update = DB::table('User')->where('User_id', $result)->update(['mongo_id' => $res['result']['_id']]);
                                    Log::info($result);
                                    Log::info($ext);
                                    //$request->image->move('/var/www/html/api_orio/Face_recog_v2/Registered', $result.".".$ext);
                                    copy('/var/www/html/api_orio/Face_recog_v2/temp/' . $imageName, '/var/www/html/api_orio/Face_recog_v2/Registered/' . $result . "." . $ext);
                                    $this->deleteTempFiles();
                                    return response()->json([
                                        'success' => true,
                                        'user_id' => $result,
                                        'orio_secret' => base64_encode($EncryptedOrioScret),
                                        'user_handler' => $uniqueName,
                                        'blockchain address' => $res['result']['userAddress'],
                                        'walletId' => $res['result']['id'],
                                        '_btc' => $res['result']['btc'],
                                        '_avax' => $res['result']['avax'],
                                        '_xlm' => $res['result']['xlm'],
                                        '_eth' => $res['result']['eth'],
                                        'secret' => $res['result']['secret'],
                                        'token' => $res['token'],

                                    ]);
                                } else {
                                    $this->deleteTempFiles();
                                    Log::info("python3 /var/www/html/api_orio/Face_recog_v2/delete_encoding.py ". $result . " 2>&1");
                                    shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/delete_encoding.py ". $result . " 2>&1");
                                    return response()->json([
                                        'success' => false,
                                        'message' => $res['message'],
                                    ]);
                                }
                            } else {
                                $this->deleteTempFiles();
                                Log::info("python3 /var/www/html/api_orio/Face_recog_v2/delete_encoding.py ". $result . " 2>&1");
                                shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/delete_encoding.py ". $result . " 2>&1");
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Blockchain error',
                                ]);
                            }
                        } else {
                            $this->deleteTempFiles();
                            shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/delete_encoding.py ". $result . " 2>&1");
                            return response()->json([
                                'success' => false,
                                'message' => 'Something went wrong1',
                            ]);
                        }
                    } else {
                        $this->deleteTempFiles();
                        return response()->json([
                            'success' => false,
                            'message' => 'Something went wrong2',
                        ]);
                    }
                } else if (trim($face_registration_check) == "No Face Found") {
                    $this->deleteTempFiles();
                    return response()->json([
                        'success' => false,
                        'message' => 'No face found',
                    ]);
                } else {
                    $this->deleteTempFiles();
                    return response()->json([
                        'success' => false,
                        'message' => 'Face already registered',
                    ]);
                }
            } else {
                $this->deleteTempFiles();
                return response()->json([
                    'success' => false,
                    'message' => 'responded null',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went Wrong',
            ]);
        }
    }

    public function generateOrioScret(Request $request)
    {

        //$date = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $date = date('d-m-y');
        //echo "$date";
        $user_id = (int)$request->user_id;
        $user = DB::table('User')->where('User_id', $user_id)->first();

        $hash = md5_file('/var/www/html/api_orio/Face_recog_v2/Registered/' . $user->Image_path);
        //----RSA initialization---
        $rsa = new RSA();
        //----creating 512 bit key--
        $key = $rsa->createKey(512);
        $pubKey = str_replace([
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            "\r\n",
            "\n",
        ], [
            '',
            '',
            "\n",
            ''
        ], $key['publickey']);

        //-----generating orio secret-----
        $orioScret = $hash . $pubKey;
        //-----setting encryption modes---
        $rsa->setEncryptionMode('CRYPT_RSA_ENCRYPTION_OAEP');
        $rsa->setHash("sha1");
        $rsa->setMGFHash("sha1");
        //-----loading publickey---------
        $rsa->loadKey($request->publicKey);
        //-----encrypting orio secret----
        $EncryptedOrioScret = $rsa->encrypt($orioScret);

        if ($EncryptedOrioScret !== '') {
            $user = DB::table('User')->where('user_id', '=', $request->user_id)->first();

            //----updating hash----------
            $update = DB::table('User')
                ->where('User_id', $user_id)
                ->update(['hash' => base64_encode($EncryptedOrioScret), 'created_at' => $date]);

            $timestamp = Carbon::now()->timestamp; //current timestamp
            $Otc =  $timestamp . ',' . md5($EncryptedOrioScret); //generating otc

            $url = "http://3.140.132.65:3083/v1/user/update_address"; //get mongo_id & public key
            $data = ['userId' => $user->mongo_id, 'publicKey' => str_replace(' ', '+', $request->publicKey)];

            $res = json_decode(getPOSTRequest($url, json_encode($data)), true);
            Log::info($res);
            if (!empty($res)) {
                return response()->json([
                    'success' => true,
                    'orio_secret' => base64_encode($EncryptedOrioScret),
                    'otc' => $Otc,
                    'created_at' => $date,
                    'encrypted' => isset($res["encrypted"]) ? $res->encrypted : null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Blockchain Error'
                ]);
            }
        } else {

            return response()->json([
                'fail' => 'Something went wrong',
            ]);
        }
    }
    public function deleteTempFiles()
    {
        $files = glob('/var/www/html/api_orio/Face_recog_v2/temp/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
        return true;
    }
    public function gethash(request $request)
    {
        $user_id = (int)$request->user_id;
        $user = DB::table('User')->where('User_id', $user_id)->first();
        if ($user != null) {
            return response()->json([
                'success' => true,
                'orio_secret' => $user->hash,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid User_id',
            ]);
        }
    }
    public function generateKeys()
    {

        Log::info("hi");
        $rsa = new RSA();
        $key = $rsa->createKey(2048);
        $pubKey = str_replace([
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            "\r\n",
            "\n",
        ], [
            '',
            '',
            "\n",
            ''
        ], $key['publickey']);
        $privateKey = str_replace([
            '-----BEGIN RSA PRIVATE KEY-----',
            '-----END RSA PRIVATE KEY-----',
            "\r\n",
            "\n",
        ], [
            '',
            '',
            "\n",
            ''
        ], $key['privatekey']);
        if ($privateKey != null && $pubKey != null) {
            return response()->json([
                'success' => true,
                'publicKey' => $pubKey,
                'privateKey' => $privateKey,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'something went wrong'
            ]);
        }
    }

    //public function getAnnId(Request $request){

    //}
}
