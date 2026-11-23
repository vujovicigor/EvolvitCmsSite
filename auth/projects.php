<?php
// Playground project list of the signed-in user (+ refresh of the signed ownership cookie).
require_once(dirname(__DIR__).'/cms/security.php');
evolvit_session_start();
evolvit_require_post();
evolvit_require_same_origin();
header('Content-Type: application/json');
include(dirname(__DIR__).'/cms/engine.php');
require_once(__DIR__.'/pgowner.php');
echo json_encode(evolvit_pg_projects_and_cookie());
