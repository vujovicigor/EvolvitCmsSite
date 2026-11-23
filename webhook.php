<?php
// GitHub push webhook: `git pull`. Requires the shared secret configured in GitHub
// (Settings > Webhooks > Secret) to be stored in the file named by EVOLVIT_WEBHOOK_SECRET_FILE (cms/config.php).
require_once(__DIR__.'/cms/security.php');
$f = defined('EVOLVIT_WEBHOOK_SECRET_FILE') ? EVOLVIT_WEBHOOK_SECRET_FILE : '';
$secret = ($f !== '' && is_readable($f)) ? trim(file_get_contents($f)) : '';
$body = file_get_contents('php://input');
$sig = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';
if ($secret === '' || !hash_equals('sha256='.hash_hmac('sha256', $body, $secret), $sig)) {
    http_response_code(403);
    exit;
}
echo exec('git -C '.escapeshellarg(__DIR__).' pull --ff-only 2>&1');
