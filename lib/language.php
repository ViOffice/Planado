<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

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
