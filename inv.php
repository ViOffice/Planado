<?php

// read input
$ihash=$_GET['id'];
$ahash=$_GET['admin'];
$error=$_GET['error'];

// session information (time)
$ctim=strtotime("now");

// Load required configs
include('conf/common.php');

// detect language
include('lib/language.php');
$lang=detect_language();

// Load i18n strings
include('conf/i18n.php');

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

if ($ihash > 0) {

    // check whether admin-id is legit (if provided)
    if ($ahash > 0) {
            
            // extract admin-id and room-id from given invite-id
            $sqlque = "SELECT aid, rid FROM " . $sqltabl . " WHERE iid=" . $ihash . "";
            $sqlres = $sqlcon->query($sqlque)->fetch_assoc();
            
            // is given admin ID legit?
            if ($sqlres["aid"] == $ahash) {
                header("Location: https://" . $jdomain . "/" . $sqlres["rid"] . "");
                exit();
                // if not, reload site with invite ID only.
            } else {
                header("Location: /inv.php?id=" . $ihash . "");
                exit();
            }

            // if no admin ID is given, check whether the conference already started.
    } else {

        // extract conference date, time and room-id from given invite-id
        $sqlque = "SELECT time, rid FROM " . $sqltabl . " WHERE iid=" . $ihash . "";
        $sqlres = $sqlcon->query($sqlque)->fetch_assoc();

        // if room exists
        if ($sqlres) {
            // if room is already open
            if ($ctim >= $sqlres["time"]) {
                header("Location: https://" . $jdomain . "/" . $sqlres["rid"] . "");
                exit();
            } else {
                // Load HTML functions
                include('lib/html.php');

                // Create HTML Content
                $html_content="<meta http-equiv='refresh' content='30' > 
                               <h1>" . $waith . "</h1>
                               <div class='justify-content-center'>
                                 <p>" . $waitt . "</p><br>
                                 <img src='/static/img/waiting.gif' alt='Waiting...' width='100px'>
                               </div>";
                build_html($html_content);
            }
        } else {
            // if room does not exist
            header("Location: /inv.php?error=true");
            exit();
        }
    }
} else {
    // Load HTML functions
    include('lib/html.php');
    
    // Create HTML Content
    $html_content="<meta http-equiv='refresh' content='30' >
                   <h1>" . $noidh . "</h1>
                   <div class='justify-content-center'>
                     <form action='/inv.php'> 
                       <input type='number' id='id' name='id'><br>
                     </form> 
                   </div>";
    build_html($html_content);
}

?>
