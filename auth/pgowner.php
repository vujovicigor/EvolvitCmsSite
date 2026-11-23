<?php
/*
 * Issues the `pg_owner` cookie read by the playground (cms/bootstrap.php in EvolvitCmsPlayground):
 * base64url(json{ids, exp}) . "." . base64url(HMAC-SHA256), signed with the secret shared by both installs.
 * The cookie is HttpOnly and scoped to the playground base domain, so it can not be forged in JS.
 */
if (!defined('EVOLVIT_PLAYGROUND_BASE_DOMAIN')) define('EVOLVIT_PLAYGROUND_BASE_DOMAIN', 'evolvitcms.com');
if (!defined('EVOLVIT_PLAYGROUND_SECRET_FILE')) define('EVOLVIT_PLAYGROUND_SECRET_FILE', '');

function evolvit_b64url($s) { return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); }

/** Returns the projects of the signed-in Google user and refreshes the ownership cookie. */
function evolvit_pg_projects_and_cookie() {
    if (empty($_SESSION['_session_google_user_email'])) return array();
    $projects = fetch('select p.* from PlaygroundProjects p where p.`google_user_email` = :_session_google_user_email', array());
    $f = EVOLVIT_PLAYGROUND_SECRET_FILE;
    $secret = ($f !== '' && is_readable($f)) ? trim(file_get_contents($f)) : '';
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower(preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'])) : '';
    $base = strtolower(EVOLVIT_PLAYGROUND_BASE_DOMAIN);
    if (strlen($secret) >= 32 && ($host === $base || substr($host, -strlen($base) - 1) === '.'.$base)) {
        $ids = array();
        foreach ($projects as $p) $ids[] = (string)(int)$p['id'];
        $exp = time() + 30 * 86400;
        $payload = evolvit_b64url(json_encode(array('ids' => $ids, 'exp' => $exp)));
        $value = $payload.'.'.evolvit_b64url(hash_hmac('sha256', $payload, $secret, true));
        setcookie('pg_owner', $value, array('expires' => $exp, 'path' => '/', 'domain' => $base,
            'secure' => evolvit_is_https(), 'httponly' => true, 'samesite' => 'Lax'));
    }
    return $projects;
}
