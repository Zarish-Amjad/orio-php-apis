<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//============Get ANN-ID=====================//
Route::post('/getAnnId','signup@getAnnId');
//============Sign Up=====================//
Route::post('/uploadimage','signup@uploadimage');
Route::post('/orioScret','signup@generateOrioScret');
//============get OrioSecret==============//
Route::post('/gethash','signup@gethash');
//============Sign In=====================//
Route::post('/login','login@facerecognition');
//============Verify User's Unique Name===//
Route::post('/verifyHandler','login@verifyHanlder');
//============Verify UserName===//
Route::post('/verifyUsername','permissions@verifyUsername');
//============Country State===============//
Route::get('/getCountries','countryState@getcountry');
Route::post('/getStates','countryState@getState');
//============Permissions=================//
Route::post('/permissions','permissions@updatePermissions');
//============Sympotms====================//
Route::post('/add/symptoms','symptoms@addSymptoms');
//===========Symptoms History=============//
Route::post('/getSymptomsHistory','getSymptomsHistory@getSymptomsHistory');
//===========Check In=====================//
Route::post('/user/Info','checkIn@userInfo');
Route::post('/checkin/history','checkIn@checkInHistory');
//===========Delete User==================//
Route::post('/user/delete','deleteUser@deleteUser');
//==========Main Screen ==================//
Route::get('/getTotalCount','mainScreenApis@getTotalRegisterCount');
Route::get('/getTotalcheckin','mainScreenApis@dailyCheckin');
Route::post('/infectedCountByStates','mainScreenApis@getInfectedCountByStates');
Route::post('/addPhoneNumber','mainScreenApis@addPhoneNo');
Route::post('/getPhoneNumber','mainScreenApis@getPhoneNo');
//===========Meta Data===================//
Route::post('/add/metadata','metadataController@insert');
Route::post('/verify/Ad_ID','metadataController@verifyAd_ID');
Route::post('/verify/lastlogin','metadataController@verifyLastLogin');
//===========generate keys===============//
Route::get('/getKeys','signup@generateKeys');
//===========get Question================//
Route::post('/getSecurityQuestion','QAcontroller@getSecurityQuestion');
//===========get User Handler================//
Route::post('/getUserHandler','QAcontroller@getUserHandler');
//===========OTC=========================//
Route::post('/generateOTC','OtcController@generateOTC');
//===========get Ad_id===================//
Route::post('/getAd_ID','metadataController@getAd_ID');
//////////// DIP Routs /////////////

//=====user====//
Route::post('/dip/user/info','dipUserController@registration');
Route::post('/dip/user/update','dipUserController@update');
Route::post('/dip/user/get','dipUserController@getInfo');
Route::post('/user/ListOfSubdelegates','dipUserController@ListOfSubdelegates');
Route::post('/user/subdelegates','dipUserController@LinktoSubdelegates');
Route::post('/addPhrase','phrase@getphrase');
Route::post('/recoverPhrase','phrase@recoverphrase');
Route::post('/addUserhandler','phrase@getuserhandler');
Route::post('/recoverAccount','phrase@recoverAccount');
Route::post('/getaddress','phrase@getaddress');
Route::post('/hashupdate','signup@generateOrioScret');
Route::post('/signupuser','signup@uploadimage');
Route::get('/numofdays','OtcController@generateOTC');

Route::get('/signuptest','signup@ENValue');
Route::get('/blockchain','signup@blockchain');


//====subdelegate===//
Route::post('/subdelegate/info','subdelegateController@registration');
Route::post('/subdelegate/update','subdelegateController@update');
Route::post('/subdelegate/ByCountry','subdelegateController@subdelegateByCountry');
Route::post('/subdelegate/get','subdelegateController@getInfo');
Route::post('/subdelegate/ListOfUsers','subdelegateController@ListOfUsers');
Route::post('/subdelegate/unverifiedList','subdelegateController@unverifiedList');
Route::post('/subdelegate/verifiedList','subdelegateController@verifiedList');
Route::post('/subdelegate/unverifiedCount','subdelegateController@unverifiedCount');
Route::post('/subdelegate/verify','subdelegateController@verify');
//====masterdelegate====//
Route::post('/masterdelegate/info','masterdelegateController@registration');
Route::post('/masterdelegate/update','masterdelegateController@update');
Route::post('/masterdelegate/get','masterdelegateController@getInfo');
Route::post('/masterdelegate/unverifiedList','masterdelegateController@unverifiedList');
Route::post('/masterdelegate/unverifiedCount','masterdelegateController@unverifiedCount');
Route::post('/masterdelegate/verify','masterdelegateController@verify');
Route::post('/masterdelegate/verifiedList','masterdelegateController@verifiedList');
Route::get('/generatePhrase','phrase@generatePhrase');
///-----testing-----
Route::get('/get','testing@test');