<?php
require_once __DIR__ . '/../helpers.php';
$u = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/public/dashboard.php');
}
require_csrf();

$period = strtoupper(trim($_POST['period'] ?? ''));
if (!in_array($period, ['AM', 'PM'], true)) {
    flash_set('danger', 'Invalid period selected.');
    redirect('/public/dashboard.php');
}

$date = today_date();
$ok = record_attendance($u, $date, $period);

if ($ok) {
    flash_set('success', "Attendance recorded for {$date} ({$period}).");
} else {
    flash_set('warning', "You already recorded {$period} attendance for {$date}.");
}

redirect('/public/dashboard.php');
