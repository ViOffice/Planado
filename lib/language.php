<?php
//
// Src: https://stackoverflow.com/a/64424827


// Define language function
function detect_language() {
    foreach (preg_split('/[;,]/', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $sub) {
        if (substr($sub, 0, 2) == 'q=') continue;
        if (strpos($sub, '-') !== false) $sub = explode('-', $sub)[0];
        if (in_array(strtolower($sub), supported_languages)) return $sub;
    }
    return 'en';
}

?>
