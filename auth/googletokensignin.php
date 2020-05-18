<?php
$idtoken = $_POST['idtoken'];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://oauth2.googleapis.com/tokeninfo?id_token='.$idtoken,
]);
// Send the request & save response to $resp
$resp = curl_exec($curl);
//echo $resp;
// Close request to clear up some resources
curl_close($curl);
//echo $resp;

$userdata = json_decode($resp, TRUE);
session_start();
//echo $resp;

////////////////
//session_start();
//print_r( $_POST );
//print_r( $_SESSION );


//header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$_SESSION['_session_google_user_sub'] = $userdata['sub'];
$_SESSION['_session_google_user_email'] = $userdata['email'];
$_SESSION['_session_google_user_picture'] = $userdata['picture'];
$_SESSION['_session_google_user_given_name'] = $userdata['given_name'];
$_SESSION['_session_google_user_family_name'] = $userdata['family_name'];
$_SESSION['_session_google_user_locale'] = $userdata['locale'];

echo $resp;

include("../cms/engine.php");
//$userdata = json_decode($resp, TRUE);
//header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');

//$_SESSION['_session_post_count'] = 1;
$f = fetch('
insert into `PlaygroundUsers` 
( `google_user_email`, `google_user_family_name`, `google_user_given_name`, `google_user_locale`, `google_user_picture`, `google_user_sub`) 
values 
( :_session_google_user_email, :_session_google_user_family_name, :_session_google_user_given_name, :_session_google_user_locale, :_session_google_user_picture, :_session_google_user_sub)
', $_POST);
print_r( $f );