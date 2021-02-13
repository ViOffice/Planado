<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// Load required configs
include('conf/common.php');

// Connect to MySQL/MariaDB
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass);
// Check connection
if ($sqlcon->connect_error) {
    die("Connection failed: " . $sqlcon->connect_error);
} 

// Create database
$sqlque = "CREATE DATABASE " . $sqlname . "";
if ($sqlcon->query($sqlque) === TRUE) {
    echo "Database created successfully\n";
} else {
    die("Error creating database: " . $sqlcon->error);
}

$sqlcon->close();

// connect to database
$sqlcon = new mysqli($sqlhost, $sqluser, $sqlpass, $sqlname);
if ($sqlcon->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Table
$sqlque = "CREATE TABLE " . $sqltabl . " (
             iid BIGINT,
             aid BIGINT,
             rid BIGINT,
             time BIGINT)";

if ($sqlcon->query($sqlque) === TRUE) {
    echo "Table created successfully\n";
} else {
    die("Error creating table: " . $sqlcon->error);
}

$sqlcon->close();

?>
