<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$room=$_GET['room'];

// Load required configs
include('conf/common.php');

// detect language
include('lib/language.php');
$lang=detect_language();

// Load i18n strings
include('conf/i18n.php');

// Do we Want to show the privacy policy?
if ($privp == TRUE) {

    // Was a room-ID provided?
    if ($room > 0) {

        // Load HTML functions
        include('lib/html.php');

        // Create HTML Content
        $html_content="<h1>" . $privh . "</h1>
                       <div class='justify-content-center'>
                         <p>" . $privt . " <a href=" . $privl . " target='_blank'>" . $privh . "</a></p><br>
                         <a href=https://" . $jdomain . "/" . $room . ">
                           <input class='button' type='submit' value='" . $privb . "'>
                         </a><br><br>
                       </div>";
        build_html($html_content, $priv_title, $priv_desc);
    } else {
        header("Location: /inv.php?error=true");
    }
    
} else {
    header("Location: " . $jdomain . "/" . $room . "");
}

?>
