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
if ($tsta == "") {
    $tsta = strtotime("" . $date . " " . $time . "");
} else {
    $date = date('Y-m-d', $tsta);
    $time = date('H:i', $tsta);
}

// prepare output
$inv = "/inv.php?id=" . $ihash . "";
$adm = "/admin.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "/cal.php?name=" . $ename . "&time=" . $tsta . "&id=" . $ihash . "";

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// extract admin-id and time-stamp from given invite-id
$sqlque = "SELECT aid, time FROM " . $sqltabl . " WHERE iid=" . $ihash . "";
$sqlres = $sqlcon->query($sqlque)->fetch_assoc();

if ($sqlres["aid"] == $ahash) {

    // if no time is given, we probably want to query the admin...
    if ($tsta == "") {

        // What were the old time & date values?
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
                           <input class='button' type='submit' value='" . $indb1 . "'>
                           <input type='hidden' name='id' value=" . $ihash . ">
                           <input type='hidden' name='admin' value=" . $ahash . ">
                         </form> 
                     </div>";
        build_html($html_content);

    } else {

        // update database (time-stamp)
        $sqlque = "UPDATE " . $sqltabl . "SET time='" . $tsta . "'
            WHERE iid=" . $ihash . "";

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
                     <td><a href=" . $adm . " target='_blank' class='highlight'>Admin-URL</a></td>
                   <tr>
                 </table>
               </div>";
            build_html($html_content);


                <div class='justify-content-center'>
                    <p>" . $adminupok . "</p><br>
                    <img src='/static/img/waiting.gif' alt='Waiting...' width='100px'>
                </div>";
    build_html($html_content);

