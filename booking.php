<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$name=$_POST['name'];
$date=$_POST['date'];
$time=$_POST['time'];
$recev=$_POST['recev'];
$rectype=$_POST['rectype'];
$rand=$_POST['rand'];

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

// process input
$name = preg_replace('/[^A-Za-z0-9\ \-\_\.]/', "", $name); // clean up name
$date = preg_replace('/[^0-9\.\-]/', "", $date); // clean up date
$time = preg_replace('/[^0-9APM\.\:]/', "", $time); // clean up time
$recev = preg_replace('/[^0-9]/', "", $recev); // clean up recurring
$rectype = preg_replace('/[^a-z0-9]/', "", $rectype); // clean up recurring
$rand = preg_replace('/[^0-9]/', "", $rand); // clean up random number
$tsta = strtotime("" . $date . " " . $time . "");
$ctim = time();
$recurring = $inrec0;
if ($recev > 0) {
    if ($rectype == "1daily") {
        $recurring = $inrec1 . ", " . $recev . "x";
    } elseif ($rectype == "1weekly") {
        $recurring = $inrec2 . ", " . $recev . "x";
    } elseif ($rectype == "2weekly") {
        $recurring = $inrec3 . ", " . $recev . "x";
    } elseif ($rectype == "4weekly") {
        $recurring = $inrec4 . ", " . $recev . "x";
    } elseif ($rectype == "1monthly") {
        $recurring = $inrec5 . ", " . $recev . "x";
    }
}

// create hashes
$string = $name . $tsta . $rand;
$fhash = md5($string);
$ihash = hexdec( substr($fhash, 0, 15) );  // first 16 are invite hash
$ahash = hexdec( substr($fhash, 15, 15) ); // last 16 are admin hash
$rhash = hexdec( substr(sha1($string), 0, 15) ); // room ID

// prepare output
$inv = "https://" . $idomain . "/inv.php?id=" . $ihash . "";
$adm = "https://" . $idomain . "/admin.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "https://" . $idomain . "/cal.php?name=" . rawurlencode($name) . "&time=" . $tsta . "&id=" . $ihash . "";
if ($recev > 0) {
    $cal = "" . $cal . "&rec=" . $recev . "&rtype=" . $rectype . "";
}

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// write to database (invite-id, admin-id, room-id, date, time)
$sqlque = "INSERT INTO " . $sqltabl . " (iid, aid, rid, time, recev, rectype)
        VALUES (" . $ihash . "," . $ahash . "," . $rhash . "," . $tsta . ","
        . $recev . ",'" . $rectype . "')";

// return HTML if creation was successful
if ($sqlcon->query($sqlque) === TRUE) {

// Load HTML functions
$html_path = $pwd . "lib/html.php";
include($html_path);

// Create HTML Content
$html_content="<h1>" . $headl . "</h2>
               <div class='justify-content-center'>
                 <table>
                   <tr>
                     <td class='th'><strong>" . $list1 . "</strong></td>
                     <td>" . $name . "</td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list2 . "</strong></td>
                     <td>" . $date . " (". $recurring . ")</td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list3 . "</strong></td>
                     <td>" . $time . "</td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list4 . "</strong></td>
                     <td><a href='" . $inv . "' target='_blank' class='highlight'>" . $ihash . "</a></td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list5 . "</strong></td>
                     <td><a href='" . $cal . "' class='highlight'>Download .ics</a></td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list6 . "</strong></td>
                     <td><a href=" . $adm . " target='_blank' class='highlight'>Admin-URL</a></td>
                   <tr>
                 </table>
                 <textarea style='display:none;' id='copythis'>" . $invtx . "\n"
                   . $list1 . " " . $name . "\n"
                   . $list2 . " " . $date . " (" . $recurring . ")\n"
                   . $list3 . " " . $time . "\n"
                   . $list4 . " " . $inv . "\n"
                   . $list5 . " " . $cal . "\n</textarea><br>
                 <input id='copyconfinfo' class='button' type='submit' value='" . $cpbtnpre . "' onclick='PrintCopied();'>
                 <a href='mailto:?subject=" . rawurlencode($invtx) . "&body=" . rawurlencode($list1) . "" . rawurlencode($name) . "%0D%0A" . rawurlencode($list2) . "" . rawurlencode($date) . "%20%28" . $recurring . "%29%0D%0A" . rawurlencode($list3) . "" . rawurlencode($time) . "%0D%0A" . rawurlencode($list4) . "" . rawurlencode($inv) . "%0D%0A" . rawurlencode($list5) . "" . rawurlencode($cal) . "'>
                   <input class='button' type='submit' value='" . $mailbtn . "'>
                 </a>
                 <!-- Load copy function -->
                 <script src='/static/js/copy.js'></script>
                 <!-- Add Event Listener for copy button -->
                 <script>document.querySelector('#copyconfinfo').addEventListener('click', CopyToClipboard);</script>
                 <!-- Change button value on click -->
                 <script>function PrintCopied() { const btn = document.querySelector('#copyconfinfo');btn.value = '" . $cpbtnpost . "'; }</script>
                 <!-- Add NoScript warning -->
                 <noscript>" . $nojs . "</noscript>
               </div>";
build_html($html_content, $book_title, $book_desc);

// return error otherwhise
} else {
  echo "Error: " . $sqlque . "<br>" . $sqlcon->error;
}

// Close database connection
$sqlcon->close();

?>
