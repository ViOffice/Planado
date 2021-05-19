<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// Load required configs
include('conf/common.php');

// Connect to MySQL/MariaDB
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
// Check connection
if ($sqlcon->connect_error) {
    die("Connection failed: " . $sqlcon->connect_error);
}

// 1. Make sure table exists
$sqlque = "CREATE TABLE IF NOT EXISTS " . $sqltabl . " (
    iid BIGINT,
    aid BIGINT,
    rid BIGINT,
    time BIGINT,
    recev BIGINT,
    rectype CHAR(16))";
if ($sqlcon->query($sqlque) === TRUE) {
    echo "Table: OK!\n";
} else {
    die("Table: " . $sqlcon->error);
}

// 2. Make sure all columns exist
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS iid BIGINT";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column iid: OK\n";
} else {
    die("Column iid: " . $sqlcon->error);
}
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS aid BIGINT";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column aid: OK\n";
} else {
    die("Column aid: " . $sqlcon->error);
}
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS rid BIGINT";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column rid: OK\n";
} else {
    die("Column rid: " . $sqlcon->error);
}
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS time BIGINT";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column time: OK\n";
} else {
    die("Column time: " . $sqlcon->error);
}
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS recev BIGINT";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column recev: OK\n";
} else {
    die("Column recev: " . $sqlcon->error);
}
$sqlque = "ALTER TABLE " . $sqltabl . " ADD COLUMN IF NOT EXISTS rectype CHAR(16)";
if ($sqlcon->query($sqlque) == TRUE) {
    echo "Column rectype: OK\n";
} else {
    die("Column rectype: " . $sqlcon->error);
}

// 3. Make sure rectypes have intervalls
$sqlque = "SELECT iid, rectype FROM " . $sqltabl;
// loop through all entries
while ($res = $sqlcon->query($sqlque)->fetch_assoc()) {
    // update timestamps for recurring events
    $interval = preg_replace('/[^0-9]/', "", $res['rectype']);
    if ($interval == "") {
        $res['rectype'] = $res['rectype'] . "1";
        $sqlque2 = "UPDATE " . $sqltabl . " SET rectype=" . $res['rectype'] . " WHERE iid=" . $res['iid'];
        if ($sqlcon->query($sqlque) == TRUE) {
            echo "Update rectype: OK\n";
        } else {
            die("Update rectype: " . $sqlcon->error);
        }
    }
}

$sqlcon->close();

?>
