<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$name=$_GET['name'];
$time=$_GET['time'];
$id=$_GET['id'];

// Load required configs
include('conf/common.php');

// process input
$ftime=date('His', $time);
$fdate=date('Ymd', $time);
$url="https://" . $idomain . "/inv.php?id=" . $id . "";
$ical="Event_" . $time . "_" . $id . ".ics";

// current time
$ctime=date('His');
$cdate=date('Ymd');
$ctizo=date('e');

// Define Filetype
header("Content-Type: text/Calendar");
header("Content-Disposition: inline; filename=$ical");

// Calendar Section
echo "BEGIN:VCALENDAR\n";
echo "VERSION:2.0\n";
$tmp="PRODID:Planado//" . $idomain . "\n";
echo $tmp;
echo "METHOD:PUBLISH\n";

// Event Section
echo "BEGIN:VEVENT\n";
$tmp="UID:" . $id . "@" . $idomain . "\n";
echo $tmp;
$tmp="LOCATION:" . $url . "\n";
echo $tmp;
$tmp="SUMMARY:Meeting\: " . $name . "\n";
echo $tmp;
$tmp="DESCRIPTION:Meeting\: " . $name . "\\n\\n ". $url . "\n";
echo $tmp;
echo "CLASS:PUBLIC\n";
$tmp="DTSTART:" . $fdate . "T" . $ftime . "\n";
echo $tmp;
$tmp="DTEND:" . $fdate . "T" . $ftime . "\n";
echo $tmp;
$tmp="DTSTAMP:" . $cdate . "T" . $ctime . "\n";
echo $tmp;
echo "END:VEVENT\n";

// Calendar Section
echo "END:VCALENDAR\n";

?>
