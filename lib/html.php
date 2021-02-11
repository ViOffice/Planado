<?php
function build_html($content) {

    // Define common HTML ------------------------------------------------------- //

    // Common HTML
    $l0001="<html>";

    // Header HTML
    $l0002="  <head>";
    $l0003="    <meta charset='utf-8'>";
    $l0004="    <title>" . $website_title . "</title>";
    $l0005="    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>";
    $l0006="    <meta name='description' content=" . $website_description . ">";
    $l0007="    <link rel='stylesheet' href='static/css/default.css'>";
    $l0008="    <link rel='stylesheet' href='static/css/custom.css'>";
    $l0009="    <link rel='shortcut icon' href='static/img/favicon.ico' type='image/x-icon'>";
    $l0010="    <script src='static/js/custom.js'></script>";
    $l0011="  </head>";
    
    // Common Body HTML
    $l0012="  <body id='body'>";
    $l0013="    <div id='content'>";
    // -- Website Content comes here -- //
    $l0014="    </div><!--content-->";
    $l0015="  </body>";
    
    // Common HTML
    $l0016="</html>";

    // Define HTML function ----------------------------------------------------- //

    // Head
    echo $l0001;
    echo $l0002;
    echo $l0003;
    echo $l0004;
    echo $l0005;
    echo $l0006;
    echo $l0007;
    echo $l0008;
    echo $l0009;
    echo $l0010;
    echo $l0011;
    // Body
    echo $l0012;
    echo $l0013;
    echo $content;
    echo $l0014;
    echo $l0015;
    echo $l0016;
};

?>
