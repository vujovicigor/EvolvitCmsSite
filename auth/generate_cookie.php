<?php
// This refers to the previous code block.
require "safecrypto.php"; 
require "key.php"; 

// Do this once then store it somehow:
//$key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
$from_db = array("news", "news1", "news2", "news3");

$message = serialize( $from_db );

$ciphertext = safeEncrypt($message, $key);
$plaintext = safeDecrypt($ciphertext, $key);
$duration = 30000000;
setcookie('playground', $plaintext, time()+$duration, '/', '.evolvitcms.com');

var_dump($ciphertext);
var_dump($plaintext);

