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

// Create HTML Content
$html_content="<h1>" . $indh1 . "</h1>
               <div class='justify-content-center'>
                 <form action='booking.php'> 
                   <label for='name'><strong>" . $indt1 . "</strong></label><br>
                   <input type='text' id='name' name='name'><br><br>
                   <label for='date'><strong>" . $indt2 . "</strong></label><br>
                   <input type='date' id='date' name='date'><br><br>
                   <label for='time'><strong>" . $indt3 . "</strong></label><br>
                   <input type='time' id='time' name='time'><br><br>
                   <input class='button' type='submit' value='" . $indb1 . "'>
                 </form> 
                 <h2>" . $indh2 ."</h2>
                 <a href=https://" . $jdomain . " target='_blank'>
                   <input class='button' type='submit' value='" . $indb2 . "'>
                 </a><br><br>
             </div>";
build_html($html_content);

?>
