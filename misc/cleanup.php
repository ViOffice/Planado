<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// Load required configs
include('../conf/common.php');

// define old timestamp:
$old=time() - (24 * 60 * 60 * $delete_after_days);

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
   die("Connection failed: " . $sqlcon->connect_error);
}

// extract invite-id and recurse-index for all "old" entries
$sqlque = "SELECT iid, recev FROM " . $sqltabl . " WHERE time<" . $old . "";
$sqlres = $sqlcon->query($sqlque)->fetch_assoc();

// loop through all entries
foreach ($sqlres as $res) {
    $res['recev'] = $res['recev'] - 1;
    if ($res['recev'] < 0) {
        $sqlque = "DELETE FROM " . $sqltabl . " WHERE iid=" . $res['iid'] . "";
        if ($sqlcon->query($sqlque) == TRUE) {
            echo "OK!";
        } else {
            echo "ERROR:" . $sqlcon->error;
        }
    } else {
        $sqlque = "UPDATE " . $sqltabl . " SET recev=" . $res['recev'] . 
        ", time=" . time() + (24 * 60 * 60) . " WHERE iid=" . $res['iid'] .
        ""; // FIXME: also update riid
        if ($sqlcon->query($sqlque) == TRUE) {
            echo "OK!";
        } else {
            echo "ERROR:" . $sqlcon->error;
        }
    }
}

// close database connection
$conn->close();

?>
