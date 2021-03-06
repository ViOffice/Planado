<?php
// SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
//
// SPDX-License-Identifier: AGPL-3.0-only

// read input
$name=$_GET['name'];
$time=$_GET['time'];
$id=$_GET['id'];
$recev=$_GET['rec'];
$rectype=$_GET['rtype'];

// Load required configs
list($pwd) = preg_replace('/\/[^\/]+$/', "/", get_included_files());
$conf_path = $pwd . "conf/common.php";
include($conf_path);

// process input
$stime=date('His', $time); // start time
$sdate=date('Ymd', $time); // start date
$edate=date('Ymd', strtotime('+1 hours', $time)); // end date (1 hour later)
$etime=date('His', strtotime('+1 hours', $time)); // end time (1 hour later)
$url="https://" . $idomain . "/inv.php?id=" . $id . "";
$ical="Event_" . $time . "_" . $id . ".ics";

// current time
$ctime=date('His');
$cdate=date('Ymd');

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
$tmp="SUMMARY:Meeting: " . $name . "\n";
echo $tmp;
$tmp="DESCRIPTION:Meeting: " . $name . "\\n\\n ". $url . "\n";
echo $tmp;
echo "CLASS:PUBLIC\n";
$tmp="DTSTART:" . $sdate . "T" . $stime . "\n";
echo $tmp;
$tmp="DTEND:" . $edate . "T" . $etime . "\n";
echo $tmp;
$tmp="DTSTAMP:" . $cdate . "T" . $ctime . "\n";
echo $tmp;
if ($recev > 0) {
    $recinte = preg_replace('/[^0-9]/', "", $rectype);
    $rectype = preg_replace('/[^a-z]/', "", $rectype);
    $tmp="RRULE:FREQ=" . strtoupper($rectype) . ";INTERVAL=" . $recinte . ";COUNT=" . ($recev + 1) . "\n";
    echo $tmp;
}
echo "END:VEVENT\n";

// Calendar Section
echo "END:VCALENDAR\n";

?>
