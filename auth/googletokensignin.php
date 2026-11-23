<?php
require_once(dirname(__DIR__).'/cms/security.php');
evolvit_session_start();
evolvit_require_post();
evolvit_require_same_origin();
header('Content-Type: application/json');

$claims = evolvit_google_verify_token(isset($_POST['idtoken']) ? $_POST['idtoken'] : '');
if ($claims === null) evolvit_json_error('Invalid token', 401);
evolvit_google_login($claims);

include(dirname(__DIR__).'/cms/engine.php');
require_once(__DIR__.'/pgowner.php');

fetch('
insert into `PlaygroundUsers`
( `google_user_email`, `google_user_family_name`, `google_user_given_name`, `google_user_locale`, `google_user_picture`, `google_user_sub`)
values
( :_session_google_user_email, :_session_google_user_family_name, :_session_google_user_given_name, :_session_google_user_locale, :_session_google_user_picture, :_session_google_user_sub)
ON CONFLICT(google_user_email) DO NOTHING
', array());

echo json_encode(evolvit_pg_projects_and_cookie());
