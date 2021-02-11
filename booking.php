<?php

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
$ename = preg_replace('/\ /', '%20', $name); // HTML encode
$tsta = strtotime("" . $date . " " . $time . ":00");
$ctim = time();

// create hashes
$string = $name . $tsta . $ctim;
$fhash = md5($string);
$ihash = hexdec( substr($fhash, 0, 15) );  // first 16 are invite hash
$ahash = hexdec( substr($fhash, 15, 15) ); // last 16 are admin hash
$rhash = hexdec( substr(sha1($string), 0, 15) ); // room ID

// prepare output
$inv = "/inv.php?id=" . $ihash . "";
$adm = "/inv.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "/cal.php?name=" . $ename . "&time=" . $tsta . "&id=" . $ihash . "";

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
                     <td><a href=" . $inv . " target='_blank' class='highlight'>" . $ihash . "</a></td>
                   </tr>
                   <tr>
                     <td class='th'><strong>" . $list5 . "</strong></td>
                     <td><a href=" . $cal . " class='highlight'>Download .ics</a></td>
                   </tr>
                 </table><br><br>
                 <p>" . $admin . "<a href=" . $adm . " target='_blank' class='highlight'>Admin</a>.</p>
               </div>";
build_html($html_content);

// return error otherwhise
} else {
  echo "Error: " . $sqlque . "<br>" . $sqlcon->error;
}

?>
