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
$sqlque = "SELECT iid, recev, rectype, time FROM " . $sqltabl . " WHERE time<" . $old . "";
//$sqlres = $sqlcon->query($sqlque)->fetch_assoc();

// loop through all entries
if (is_array($sqlres)) {
    while ($res = $sqlcon->query($sqlque)->fetch_assoc()) {
        // update timestamps for recurring events
        if ($res['recev'] > 0) {
            if ($res['rectype'] == "daily") {
                $typestep = 1;
            } elseif ($res['rectype'] == "weekly") {
                $typestep = 7;
            } elseif ($res['rectype'] == "monthly") {
                $typestep = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
            } else {
                $typestep = 0;
            }

            // timestamp of next event
            $next = $res['time'] + ($typestep * 24 * 60 * 60);
            // Update recurrances
            $reccurrings = $res['recev'] - 1;
            // Update database
            $sqlque = "UPDATE " . $sqltabl . " SET recev=" . $recurrings . ", time=" . $next . " WHERE iid=" . $res['iid'];
            if ($sqlcon->query($sqlque) == TRUE) {
                echo "OK!\n";
            } else {
                echo "ERROR: " . $sqlcon->error . "\n";
            }
        }
        // delete old non-recurring events
        if ($res['recev'] <= 0) {
            $sqlque = "DELETE FROM " . $sqltabl . " WHERE iid=" . $res['iid'] . "";
            if ($sqlcon->query($sqlque) == TRUE) {
                echo "OK!\n";
            } else {
                echo "ERROR: " . $sqlcon->error . "\n";
            }
        }
    }
} else {
    echo "Nothing to do\n";
}

// close database connection
$sqlcon->close();

?>
