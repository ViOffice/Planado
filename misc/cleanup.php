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

// extract admin-id and room-id from given invite-id
$sqlque = "DELETE FROM " . $sqltabl . " WHERE time<" . $old . "";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "OK!";
} else {
    echo "ERROR:" . $sqlcon->error;
}

$conn->close();

?>
