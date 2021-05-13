<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$room=$_GET['room'];

// Load required configs
list($pwd) = preg_replace('/\/[^\/]+$/', "/", get_included_files());
$conf_path = $pwd . "conf/common.php";
include($conf_path);

// detect language
$lang_path = $pwd . "lib/language.php";
include($lang_path);
$lang=detect_language();

// Load i18n strings
$i18n_path = $pwd . "conf/i18n.php";
include($i18n_path);
 
// Load HTML functions
$html_path = $pwd . "lib/html.php";
include($html_path);

// Do we Want to show the privacy policy?
if ($privp == TRUE) {

    // Was a room-ID provided?
    if ($room > 0) {

        // Create HTML Content
        $html_content="<h1>" . $privh . "</h1>
                       <div class='justify-content-center'>
                         <p>" . $privt . " <a href=" . $privl . " target='_blank' class='highlight'>" . $privh . "</a></p><br>
                         <a href='https://" . $jdomain . "/" . $room . "'>
                           <input class='button' type='submit' value='" . $privb . "'>
                         </a><br><br>
                       </div>";
        if ($cleanup == "iframe") {
            $html_content=$html_content . "<iframe src='misc/cleanup.php' style='width:0;height:0;border:0;display:none;'></iframe>";
        }
        build_html($html_content, $priv_title, $priv_desc);
    } else {
        header("Location: /inv.php?error=true");
    }
    
} else {
    header("Location: https://" . $jdomain . "/" . $room . "");
}

?>
