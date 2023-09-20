<?php
// show error reporting
error_reporting(E_ALL);
 
// set default time-zone
date_default_timezone_set('Asia/Kolkata');
 
// variables ulsed for jwt
$key = "gallery_key";
$iss = "http://photo-gallery.org";
$aud = "http://photo-gallery.com";
$iat = 1356999524;
$nbf = 1357000000;
?>
