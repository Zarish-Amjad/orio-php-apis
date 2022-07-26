<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class QAcontroller extends Controller {

    public function getSecurityQuestion(Request $request) {
        $user_id = (int) $request->user_id;
        $getDetail = DB::table('answers')->where('user_id', '=', $user_id)->first();
        if ($getDetail != '') {
            $getQuestion = DB::table('questions')->where('id', '=', $getDetail->question_id)->first();
            if ($getQuestion != '') {
                $answer_decrypt = Crypt::decryptString($getDetail->answer);
                return response()->json([
                            'success' => true,
                            'question' => $getQuestion->question,
                            'answer' => $answer_decrypt
                ]);
            } else {
                return response()->json([
                            'success' => false,
                            'msg' => 'Something went wrong',
                ]);
            }
        } else {
            return response()->json([
                        'success' => false,
                        'msg' => 'Invalid User_id',
            ]);
        }
    }

    public function getUserHandler(Request $request) {
        $question = DB::table('questions')->where('question', '=', $request->question)->get();
        if (count($question)) {
            $answers = DB::table('answers')->where('question_id', '=', $question[0]->id)->get();
        }
        if (isset($answers) && count($answers)) {
            $user_array = [];
            foreach ($answers as $answer) {
                if (Crypt::decryptString($answer->answer) == $request->answer) {
                    $user_array[] = DB::table('User')->select('username', 'user_handler')->where('User_id', '=', $answer->user_id)->get();
                }
            }
            if(count($user_array))
            {
                $return_users = [];
                foreach ($user_array as $user)
                {
                    if($user[0]->username == $request->username)
                    {
                        $return_users[] = $user[0];
                    }
                }
            }
        }
        if(isset($return_users) && count($return_users))
        {
            $return_data = ['status' => true, 'message' => count($return_users) . ' ' . 'records found'];
            $return_data['users'] = $return_users;
             return json_encode($return_data);
        }else{
             return json_encode(['success' => false, 'message' => 'Record Not found']);
        }
//        if (isset($enc_answer_req) && $enc_answer_req != null) {
//            $getUsers = DB::table('answers')
//                    ->select('User.username', 'User.user_handler', 'answers.answer')
//                    ->join('User', 'User.User_id', '=', 'answers.user_id')
//                    ->join('questions', 'questions.id', '=', 'answers.question_id')
//                    ->where('User.username', '=', $request->username)
//                    ->where('questions.question', '=', $request->question)
//                    ->where('answers.answer', '=', $enc_answer_req)
//                    ->get();
//            if (count($getUsers) > 0) {
//                $user_array = [];
//                $return_data = ['status' => true, 'message' => count($getUsers) . ' ' . 'records found'];
//                foreach ($getUsers as $key => $value) {
//                    $user_array[$key] = ['username' => $value->username, 'user_handler' => $value->user_handler];
//                }
////                $return_data['users'] = $user_array;
//                $return_data['users'] = json_encode($user_array);
//                return json_encode($return_data);
//            } else {
//                return json_encode(['status' => false, 'message' => 'Record Not found']);
//            }
//        } else {
//            return json_encode(['success' => false, 'message' => 'Record Not found']);
//        }
    }

}
