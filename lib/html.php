<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

function build_html($content) {

    // Echo common HTML ------------------------------------------------------- //
    echo "<html>
            <head>
			  <meta charset='utf-8'>
			  <title>" . $website_title . "</title>
			  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
			  <meta name='description' content=" . $website_description . ">
			  <link rel='stylesheet' href='static/css/default.css'>
			  <link rel='stylesheet' href='static/css/custom.css'>
			  <link rel='shortcut icon' href='static/img/favicon.ico' type='image/x-icon'>
			  <script src='static/js/custom.js'></script>
			</head>
			<body id='body'>
			  <div id='content'>" . $content . "</div>
			</body>
          </html>";

};

?>
