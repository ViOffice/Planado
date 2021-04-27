<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$ahash=$_GET['admin'];
$ihash=$_GET['id'];
$tsta=$_GET['tsta'];
$name=$_GET['name'];
$time=$_GET['time'];
$date=$_GET['date'];

// Load required configs
include('conf/common.php');

// detect language
include('lib/language.php');
$lang=detect_language();

// Load i18n strings
include('conf/i18n.php');

// Load HTML functions
include('lib/html.php');

// process input
$name = preg_replace('/[^A-Za-z0-9\ \-\_\.]/', "", $name); // clean up name
$ename = preg_replace('/\ /', '%20', $name); // HTML encode
if ($date != "" || $time != "") {
    $tsta = strtotime("" . $date . " " . $time . "");
} else {
    $tsta = "";
}
if ($ihash == "") $ihash = 0;
if ($ahash == "") $ahash = 0;

// prepare output
$inv = "/inv.php?id=" . $ihash . "";
$adm = "/admin.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "/cal.php?name=" . $ename . "&time=" . $tsta . "&id=" . $ihash . "";

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $sqlcon->connect_error);
}

// extract admin-id and time-stamp from given invite-id
$sqlque = "SELECT aid, time FROM " . $sqltabl . " WHERE iid=" . $ihash . "";
$sqlres = $sqlcon->query($sqlque)->fetch_assoc();

// if provided admin-id corresponds to the provided invite-id, we either show a
// form to change the time-stamp of thevent or change the DB-entry
if ($sqlres["aid"] == $ahash && $sqlres["aid"] != "") {

    // if no time is given, we probably want to query the admin...
    if ($tsta == "") {

        // What were the old time and date values?
        $odate = date('Y-m-d', $sqlres["time"]);
        $otime = date('H:i', $sqlres["time"]);

        // Create HTML Content
        $html_content="<h1>" . $adminh . "</h1>
                       <div class='justify-content-center'>
                        <p>" . $ihash . "</p><br>
                         <form action='admin.php'> 
                           <label for='name'><strong>" . $indt1 . "</strong></label><br>
                           <input type='text' id='name' name='name'><br><br>
                           <label for='date'><strong>" . $indt2 . "</strong></label><br>
                           <input type='date' id='date' name='date' value='" . $odate . "' ><br><br>
                           <label for='time'><strong>" . $indt3 . "</strong></label><br>
                           <input type='time' id='time' name='time' value='" . $otime . "'><br><br>
                           <input class='button' type='submit' value='" . $adminb1 . "'>
                           <input type='hidden' name='id' value=" . $ihash . ">
                           <input type='hidden' name='admin' value=" . $ahash . ">
                         </form> 
                     </div>";
        build_html($html_content, $admin_title, $admin_desc);

    // if time-stamp is given, we should update the data base entry and report
    // back the updated information to the admin.
    } else {

        // update database (time-stamp)
        $sqlque = "UPDATE " . $sqltabl . " SET time=" . $tsta . " WHERE iid=" . $ihash . "";

        // return HTML if update was successful
        if ($sqlcon->query($sqlque) === TRUE) {

            // Create HTML Content
            $html_content="<h1>" . $adminh . "</h1>
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
                   <tr>
                     <td class='th'><strong>" . $list6 . "</strong></td>
                     <td><a href=" . $adm . " target='_blank' class='highlight'>" . $ahash . "</a></td>
                   <tr>
                 </table>
               </div>";
            build_html($html_content, $admin_title, $admin_desc);

        } // FIXME: what happens on fail?
        echo "error 1...";
     }
     echo "error 2...";
} else {
    // Create HTML Content
    $html_content="<h1>" . $noidh . "</h1>
                   <div class='justify-content-center'>
                     <form action='/admin.php'> 
                       <label for='id'><strong>" . $list4 . "</strong></label><br>
                       <input type='text' id='id' name='id'><br><br>
                       <label for='admin'><strong>" . $list6 . "</strong></label><br>
                       <input type='text' id='admin' name='admin'><br><br>
                     </form> 
                   </div>";
    build_html($html_content, $admin_title, $admin_desc);
}

?>
