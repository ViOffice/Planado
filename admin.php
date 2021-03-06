<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$ahash=$_GET['admin'];
if ($ahash == "") {
    $ahash=$_POST['admin'];
}
$ihash=$_GET['id'];
if ($ihash == "") {
    $ihash=$_POST['id'];
}
$tsta=$_POST['tsta'];
$name=$_POST['name'];
$time=$_POST['time'];
$date=$_POST['date'];
$recev=$_POST['recev'];
$rectype=$_POST['rectype'];

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

// Load HTML functions
$html_path = $pwd . "lib/html.php";
include($html_path);

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

// prepare output
$inv = "/inv.php?id=" . $ihash . "";
$adm = "/admin.php?id=" . $ihash . "&admin=" . $ahash . "";
$cal = "/cal.php?name=" . $ename . "&time=" . $tsta . "&id=" . $ihash . "";
if ($recev > 0) {
    $cal = "" . $cal . "&rec=" . $recev . "&rtype=" . $rectype . "";
}

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $sqlcon->connect_error);
}

// extract admin-id and time-stamp from given invite-id
$sqlque = "SELECT aid, time, recev, rectype FROM " . $sqltabl . " WHERE iid=" . $ihash . "";
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
                         <form action='admin.php' method='POST'> 
                           <label for='name'><strong>" . $indt1 . "</strong></label><br>
                           <input type='text' id='name' name='name'><br><br>
                           <label for='date'><strong>" . $indt2 . "</strong></label><br>
                           <input type='date' id='date' name='date' value='" . $odate . "' ><br><br>
                           <label for='time'><strong>" . $indt3 . "</strong></label><br>
                           <input type='time' id='time' name='time' value='" . $otime . "'><br><br>
                           <label for='recev'><strong>" . $indt4 . "</strong></label><br>
                           <input type='number' id='recev' name='recev' value='" . $sqlres['recev'] . "' style='width:5em;'>
                           <select name='rectype' value='" . $sqlres['rectype'] . "'>
                             <option value='1daily'>" . $inrec1 . "</option>
                             <option value='1weekly'>" . $inrec2 . "</option>
                             <option value='2weekly'>" . $inrec3 . "</option>
                             <option value='4weekly'>" . $inrec4 . "</option>
                             <option value='1monthly'>" . $inrec5 . "</option>
                           </select><br><br>
                           <input class='button' type='submit' value='" . $adminb . "'>
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
                     <td>" . $date . " (" . $recurring . ")</td>
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
                 <textarea style='display:none;' id='copythis'>" . $list1 . " " . $name . "\n"
                   . $list2 . " " . $date . "\n"
                   . $list3 . " " . $time . "\n" 
                   . $list4 . " http://" . $idomain . "" . $inv . "\n"
                   . $list5 . " http://" . $idomain . "" . $cal . "</textarea><br>
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
            build_html($html_content, $admin_title, $admin_desc);

        } // FIXME: what happens on fail?
     }
} else {
    // Create HTML Content
    $html_content="<h1>" . $noidh . "</h1>
                   <div class='justify-content-center'>
                     <form action='/admin.php' method='POST'> 
                       <label for='id'><strong>" . $list4 . "</strong></label><br>
                       <input type='text' id='id' name='id'><br><br>
                       <label for='admin'><strong>" . $list6 . "</strong></label><br>
                       <input type='text' id='admin' name='admin'><br><br>
                     </form> 
                   </div>";
    build_html($html_content, $admin_title, $admin_desc);
}

// Close database connection
$sqlcon->close();

?>
