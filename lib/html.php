<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

function build_html($content, $title, $description) {

    // Define common HTML --------------------------------------------------- //

    $html_head="<html>
                  <head>
                    <meta charset='utf-8'>
                    <title>" . $title . "</title>
                    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
                    <meta name='description' content='" . $description . "'>
                    <link rel='stylesheet' href='static/css/default.css'>
                    <link rel='stylesheet' href='static/css/custom.css'>
                    <link rel='shortcut icon' href='static/img/favicon.ico' type='image/x-icon'>
                    <script src='static/js/custom.js'></script>
                  </head>";

    $html_prec="  <body id='body'>
                    <div id='content'>";

    // -- Website Content comes here -- //

    $html_posc="    </div><!--content-->
                  </body>
                </html>";

    // Print HTML ----------------------------------------------------------- //

    echo $html_head;
    echo $html_prec;
    echo $content;
    echo $html_posc;
};

?>
