<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// Load required configs
list($pwd) = preg_replace('/\/[^\/]+$/', "/", get_included_files());
$conf = $pwd . "../conf/common.php";
include($conf);

// define old timestamp:
$old=time() - (24 * 60 * 60 * $delete_after_days);

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $sqlcon->connect_error);
}

// extract invite-id and recurse-index for all "old" entries
$sqlque = "SELECT iid, rid, recev, rectype, time FROM " . $sqltabl . " WHERE time<" . $old . "";
//$sqlres = $sqlcon->query($sqlque)->fetch_assoc();

// loop through all entries
while ($res = $sqlcon->query($sqlque)->fetch_assoc()) {
    // update timestamps for recurring events
    if ($res['recev'] > 0) {
        $recinte = preg_replace('/[^0-9]/', "", $res['rectype']);
        if ($recinte == "") {
            $recinte = 1;
        }
        $res['rectype'] = preg_replace('/[^a-z]/', "", $res['rectype']);
        if ($res['rectype'] == "daily") {
            $typestep = 1 * $recinte;
        } elseif ($res['rectype'] == "weekly") {
            $typestep = 7 * $recinte;
        } elseif ($res['rectype'] == "monthly") {
            $typestep = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y")) * $recinte;
        } else {
            $typestep = 0;
        }
        // timestamp of next event
        $next = $res['time'] + ($typestep * 24 * 60 * 60);
        // Update recurrances
        $recurrings = $res['recev'] - 1;
        // Update Room-ID
        $string = $res['rid'] . $next . mt_rand();
        $newroom = hexdec( substr(sha1($string), 0, 15) );
        // Update database
        $sqlque2 = "UPDATE " . $sqltabl . " SET recev=" . $recurrings . ", time=" . $next . ", rid=" . $newroom . " WHERE iid=" . $res['iid'];
        if ($sqlcon->query($sqlque2) == TRUE) {
            echo "OK! (Update)\n";
        } else {
            echo "ERROR: " . $sqlcon->error . "\n";
        }
    }
    // delete old non-recurring events
    if ($res['recev'] <= 0) {
        $sqlque2 = "DELETE FROM " . $sqltabl . " WHERE iid=" . $res['iid'] . "";
        if ($sqlcon->query($sqlque2) == TRUE) {
            echo "OK (Delete)!\n";
        } else {
            echo "ERROR: " . $sqlcon->error . "\n";
        }
    }
}

// close database connection
$sqlcon->close();

?>
