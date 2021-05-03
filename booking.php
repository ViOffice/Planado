<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$name=$_GET['name'];
$date=$_GET['date'];
$time=$_GET['time'];

// Load required configs
include('conf/common.php');

// detect language
include('lib/language.php');
$lang=detect_language();

// Load i18n strings
include('conf/i18n.php');

// process input
$name = preg_replace('/[^A-Za-z0-9\ \-\_\.]/', "", $name); // clean up name
$date = preg_replace('/[^0-9\.\-]/', "", $date); // clean up date
$time = preg_replace('/[^0-9APM\.\:/', "", $time); // clean up time
$tsta = strtotime("" . $date . " " . $time . "");
$ctim = time();

// create hashes
$string = $name . $tsta . $ctim;
$fhash = md5($string);
$ihash = hexdec( substr($fhash, 0, 15) );  // first 16 are invite hash
$ahash = hexdec( substr($fhash, 15, 15) ); // last 16 are admin hash
$rhash = hexdec( substr(sha1($string), 0, 15) ); // room ID

// prepare output
$inv = "https://" . $idomain . "/inv.php?id=" . $ihash . "";
$adm = "https://" . $idomain . "/admin.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "https://" . $idomain . "/cal.php?name=" . urlencode($name) . "&time=" . $tsta . "&id=" . $ihash . "";

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// write to database (invite-id, admin-id, room-id, date, time)
$sqlque = "INSERT INTO " . $sqltabl . " (iid, aid, rid, time)
        VALUES (" . $ihash . "," . $ahash . "," . $rhash . "," . $tsta . ")";

// return HTML if creation was successful
if ($sqlcon->query($sqlque) === TRUE) {

// Load HTML functions
include('lib/html.php');

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
                     <td>" . $date . "</td>
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
                   . $list2 . " " . $date . "\n"
                   . $list3 . " " . $time . "\n"
                   . $list4 . " " . $inv . "\n"
                   . $list5 . " " . $cal . "\n</textarea><br>
                 <input id='copyconfinfo' class='button' type='submit' value='" . $cpbtnpre . "' onclick='PrintCopied();'>
                 <a href='mailto:?subject=" . urlencode($invtx) . "&body=" . urlencode($list1) . "" . urlencode($name) . "\n" . urlencode($list2) . "" . urlencode($date) . "\n" . urlencode($list3) . "" . urlencode($time) . "\n" . urlencode($list4) . "" . urlencode($inv) . "\n" . urlencode($list5) . "" . urlencode($cal) . ""'>
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

?>

