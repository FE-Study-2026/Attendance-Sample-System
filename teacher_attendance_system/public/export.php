<?php
require_once __DIR__ . '/../helpers.php';
$u = require_login();

$rows = read_attendance_for_teacher($u['id'], 10000);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="attendance_' . $u['id'] . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['date', 'period', 'recorded_at']);

foreach ($rows as $r) {
    fputcsv($out, [$r['date'], $r['period'], $r['recorded_at']]);
}
fclose($out);
exit;
