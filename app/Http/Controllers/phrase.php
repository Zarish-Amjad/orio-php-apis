<?php
namespace App\Http\Controllers;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class phrase extends Controller
{
    public function getphrase(Request $request) {
        
        $user_id = (int) $request->user_id;
        $phrase = $request->phrase;
        $getuser = DB::table('User')->where('User_id', '=', $user_id)->first();
        if($getuser != '') 
        {
            $get_phrases = DB::table('phrase')->get();
            $status= false;
            foreach($get_phrases as $get_phrase)
            { 
                if($get_phrase->user_id==$user_id || Crypt::decryptString($get_phrase->phrase) == $phrase)
                { 
                    $status= true;       
                } 
            }   
		 
            if($status == false)
            {
                $phrase_encrypt = Crypt::encryptString($phrase);
                $save_user_id = DB::table('phrase')->insert(['user_id'=>$user_id,"phrase"=>$phrase_encrypt]);
                if($save_user_id) 
                {          
                    return response()->json([
                            'success' => true
                            ]);
                } 
                else{
                    return response()->json([
                            'success' => false
                            ]);
                }   
            }
            else{
                return response()->json([
                    'success' => false,
                    'msg'   => 'Phrase or ID already exists'
            ]);
            }
        }
        else{
            return response()->json([
                        'success' => false
            ]);
        }
    }

    public function getuserhandler(Request $request) {

        $phrase = $request->phrase;
        $get_users = DB::table('phrase')->get();
            foreach ($get_users as $get_user) 
            {	
                if(Crypt::decryptString($get_user->phrase) == $phrase) 
                {
                    $get_userid = $get_user->user_id;
                    $get_userhandler = DB::table('User')->select('user_handler')->where('User_id' , $get_userid)->first();
                    if($get_userid) 
                    {          
                    return response()->json([
                        'success' => true,
                        'user_id' => $get_userid,
                        'user_handler' =>  $get_userhandler->user_handler
                    ]);
                    } 

                    else{
                    return response()->json([
                        'success' => false
                    ]);
                    }
                }
            }
            return response()->json([
                'success' => false
            ]);

    }
    public function  generatePhrase(Request $request) {

        $data = json_decode(file_get_contents('https://raw.githubusercontent.com/dwyl/english-words/master/words_dictionary.json'),true);
        $storePhrase = [];
        $keys = array_keys($data);
        $rand = array_rand($keys,12);
        $phrase = $keys[$rand[0]];
        for($i = 0; $i< 12 ; $i++)
        {
            $storePhrase[$i] = $keys[$rand[$i]];
        }
        return response()->json([
            'phrase' => $storePhrase
        ]);
    }

	public function  recoverPhrase(Request $request) {

       	$user_id = (int) $request->user_id;
   	    $getuser = DB::table('User')->where('User_id', '=', $user_id)->first();
        if($getuser != '') 
        {    
            $get_phrases = DB::table('phrase')->where('user_id', '=', $user_id)->first();

		if($get_phrases != '')
        {
        	return response()->json([
                        'success' => true,
				        'phrase' => Crypt::decryptString($get_phrases->phrase)
                        ]);
        }
        else{
            return response()->json([
                        'success' => false
                        ]);
            }   
        }
        else{
                return response()->json([
                    'success' => false
            ]);
            }
    }
    public function  recoverAccount(Request $request) {

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move('/var/www/html/api_orio/Face_recog_v2/temp', $imageName);
            $id = shell_exec("python3 /var/www/html/api_orio/Face_recog_v2/account_recovery.py /var/www/html/api_orio/Face_recog_v2/temp/" . $imageName . " 2>&1");
            if(!empty($id)){
                $user = DB::table('User')->where('user_id', '=', $id)->first();
                if(!empty($user)){
                    $answer_data = DB::table('answers')->where('user_id', '=', $id)->first();
                    
                    if(!empty($answer_data)){
                        $question_detail = DB::table('questions')->where('id', '=', $answer_data->question_id)->first();
                    }
                    $login_details = DB::table('Login_detail')->where('user_id', '=', $id)->first();
                    $this->deleteTempFiles();
                    return response()->json([
                        'success' => true,
                        'user_handler'  => isset($user->user_handler)?$user->user_handler: '',
                        'question'      => isset($question_detail->question)?$question_detail->question: '',
                        'answer'        => isset($answer_data->answer)?Crypt::decryptString($answer_data->answer): '',
                        'Ad_ID'         =>  isset($login_details->Ad_ID)?$login_details->Ad_ID: '',
                        'time'          =>  isset($login_details->time)?$login_details->time: '',
                        'date'          =>  isset($login_details->date)?$login_details->date: '',
                        'location'      =>  isset($login_details->location)?$login_details->location: '',
                        'model'         =>  isset($login_details->model)?$login_details->model: '',
                    ]);

                }else {
                    $this->deleteTempFiles();
                    return response()->json([
                        'success' => false,
                        'message' => 'No user found',
                    ]);
                }
            }else {
                $this->deleteTempFiles();
                return response()->json([
                    'success' => false,
                    'message' => 'Something went Wrong',
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something went Wrong',
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
}