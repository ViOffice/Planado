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

// detect language
include('lib/language.php');
$lang=detect_language();

// process input
$ftime=date('H:i:s', $time);
$fdate=date('Y-m-d', $time);
$tzone=date('e', $time);
$url="https://" . $idomain . "/inv.php?id=" . $id . "";
$ical="Event_" . $date . "_" . $time . "_" . $id . ".ics";

// Define Filetype
header("Content-Type: text/Calendar");
header("Content-Disposition: inline; filename=$ical");

// Calendar Section
echo "BEGIN:VCALENDAR\n";
echo "PRODID:-//Mozilla.org/NONSGML Mozilla Calendar V1.1//EN\n";
echo "VERSION:2.0\n";
echo "BEGIN:VTIMEZONE\n";
echo "TZID:" . $tzone . "\n"; // arguably, this needs to be changed
//echo "BEGIN:DAYLIGHT\n";
//echo "TZOFFSETFROM:+0100\n";
//echo "TZOFFSETTO:+0200\n";
//echo "TZNAME:CEST\n";
//echo "DTSTART:19700329T020000\n";
//echo "RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3\n";
//echo "END:DAYLIGHT\n";
//echo "BEGIN:STANDARD\n";
//echo "TZOFFSETFROM:+0200\n";
//echo "TZOFFSETTO:+0100\n";
//echo "TZNAME:CET\n";
//echo "DTSTART:19701025T030000\n";
//echo "RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10\n";
//echo "END:STANDARD\n";
echo "END:VTIMEZONE\n";

// Event Section
echo "BEGIN:VEVENT\n";
$tmp="CREATED:" . $fdate . "T" . $ftime . "Z\n";
echo $tmp;
$tmp="LAST-MODIFIED:" . $fdate . "T" . $ftime . "Z\n";
echo $tmp;
//echo "UID:xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\n";
$tmp="SUMMARY:Meeting: " . $name . "\n";
echo $tmp;
$tmp="DESCRIPTION:Meeting: " . $name . "\\n\\n ". $url . "\n";
echo $tmp;
$tmp="DTSTART:" . $fdate . "T" . $ftime . "Z\n";
echo $tmp;
$tmp="DTEND:" . $fdate . "T" . $ftime . "Z\n";
echo $tmp;

// Alarm Section
//echo "BEGIN:VALARM\n";
//echo "ACTION:DISPLAY\n";
//echo "TRIGGER;VALUE=DURATION:-PT86400S\n";

echo "END:VALARM\n";
echo "END:VEVENT\n";

?>
