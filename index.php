<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// Load required configs
include('conf/common.php');

// detect language
include('lib/language.php');
$lang=detect_language();

// Load i18n strings
include('conf/i18n.php');

// Load HTML functions
include('lib/html.php');

// Create unique hash for current session
$rand = mt_rand(); // random random between 0 and mt_getrandmax()

// Create unique string for immediate room creation
$string="" . time() . $rand . "";
$roomid=hexdec( substr(md5($string), 0, 15) );

// Create HTML Content
$html_content="<h1>" . $indh1 . "</h1>
               <div class='justify-content-center'>
                 <form action='booking.php' method='POST'> 
                   <label for='name'><strong>" . $indt1 . "</strong></label><br>
                   <input type='text' id='name' name='name'><br><br>
                   <label for='date'><strong>" . $indt2 . "</strong></label><br>
                   <input type='date' id='date' name='date'><br><br>
                   <label for='time'><strong>" . $indt3 . "</strong></label><br>
                   <input type='time' id='time' name='time'><br><br>
                   <!--<label for='recev'><strong>" . $indt4 . "</strong></label><br>-->
                   <input type='hidden' id='recev' name='recev' value='0'><br><br>
                   <input type='hidden' name='rand' value='" . $rand . "'>
                   <input class='button' type='submit' value='" . $indb1 . "'>
                 </form> 
                 <h2>" . $indh2 ."</h2>
                 <a href='/out.php?room=" . $roomid . "' target='_blank'>
                   <input class='button' type='submit' value='" . $indb2 . "'>
                 </a><br><br>
             </div>";

if ($cleanup == "iframe") {
    $html_content=$html_content . "<iframe src='misc/cleanup.php' style='width:0;height:0;border:0;display:none;'></iframe>";
}

build_html($html_content, $ind_title, $ind_desc);

?>
