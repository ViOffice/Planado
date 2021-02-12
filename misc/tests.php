<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$tests=$_GET['tests'];

// Database test
if ($tests == "db" || $tests == "all") {

    // Load required configs
    include('conf/common.php');

    // connect to database
    $sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
    if ($sqlcon->connect_error) {
       die("ERROR: " . $conn->connect_error);
    } else {
       echo "OK!\n";
    }

} else {
    die("Test not implemented yet. Try '?tests=all'\n");
}

?>
