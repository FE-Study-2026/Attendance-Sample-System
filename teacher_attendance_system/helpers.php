<?php
require_once __DIR__ . '/config.php';

function ensure_data_storage(): void {
    if (!is_dir(DATA_DIR)) { mkdir(DATA_DIR, 0755, true); }

    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([], JSON_PRETTY_PRINT));
    }
    if (!file_exists(ATTENDANCE_FILE)) {
        // CSV header
        $fh = fopen(ATTENDANCE_FILE, 'a');
        if (ftell($fh) === 0) {
            fputcsv($fh, ['date', 'period', 'teacher_id', 'full_name', 'department', 'recorded_at']);
        }
        fclose($fh);
    }
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never {
    $url = (BASE_URL ?: '') . $path;
    header("Location: {$url}");
    exit;
}

function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        // Basic hardening
        ini_set('session.use_strict_mode', '1');
        session_start();
    }
}

function csrf_token(): string {
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function require_csrf(): void {
    start_session();
    $token = $_POST['csrf'] ?? '';
    if (!$token || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
        http_response_code(400);
        echo "Invalid CSRF token.";
        exit;
    }
}

function load_users(): array {
    ensure_data_storage();
    $raw = file_get_contents(USERS_FILE);
    $data = json_decode($raw ?: '[]', true);
    return is_array($data) ? $data : [];
}

function save_users(array $users): void {
    ensure_data_storage();
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function find_user_by_username(string $username): ?array {
    $users = load_users();
    foreach ($users as $u) {
        if (strcasecmp($u['username'], $username) === 0) return $u;
    }
    return null;
}

function update_user(array $updated): void {
    $users = load_users();
    foreach ($users as $i => $u) {
        if ($u['id'] === $updated['id']) {
            $users[$i] = $updated;
            save_users($users);
            return;
        }
    }
}

function current_user(): ?array {
    start_session();
    if (empty($_SESSION['user_id'])) return null;
    $uid = $_SESSION['user_id'];
    $users = load_users();
    foreach ($users as $u) {
        if ($u['id'] === $uid) return $u;
    }
    return null;
}

function require_login(): array {
    $u = current_user();
    if (!$u) redirect('/public/login.php');
    return $u;
}

function flash_set(string $type, string $msg): void {
    start_session();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function flash_get(): ?array {
    start_session();
    if (empty($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function today_date(): string {
    return date('Y-m-d');
}

function period_for_now(): string {
    // AM if before 12:00, otherwise PM
    return ((int)date('H') < 12) ? 'AM' : 'PM';
}

function has_attendance(string $teacher_id, string $date, string $period): bool {
    ensure_data_storage();
    if (!file_exists(ATTENDANCE_FILE)) return false;

    $fh = fopen(ATTENDANCE_FILE, 'r');
    if (!$fh) return false;

    $header = fgetcsv($fh);
    while (($row = fgetcsv($fh)) !== false) {
        // date, period, teacher_id, full_name, department, recorded_at
        if (($row[0] ?? '') === $date && ($row[1] ?? '') === $period && ($row[2] ?? '') === $teacher_id) {
            fclose($fh);
            return true;
        }
    }
    fclose($fh);
    return false;
}

function record_attendance(array $user, string $date, string $period): bool {
    ensure_data_storage();

    if (has_attendance($user['id'], $date, $period)) {
        return false;
    }

    $fh = fopen(ATTENDANCE_FILE, 'a');
    if (!$fh) return false;

    $recorded_at = date('Y-m-d H:i:s');
    fputcsv($fh, [$date, $period, $user['id'], $user['full_name'], $user['department'], $recorded_at]);
    fclose($fh);
    return true;
}

function read_attendance_for_teacher(string $teacher_id, int $limit = 50): array {
    ensure_data_storage();
    $rows = [];
    $fh = fopen(ATTENDANCE_FILE, 'r');
    if (!$fh) return $rows;
    $header = fgetcsv($fh);
    while (($row = fgetcsv($fh)) !== false) {
        if (($row[2] ?? '') === $teacher_id) {
            $rows[] = [
                'date' => $row[0] ?? '',
                'period' => $row[1] ?? '',
                'recorded_at' => $row[5] ?? '',
            ];
        }
    }
    fclose($fh);
    // newest first (by recorded_at)
    usort($rows, fn($a,$b) => strcmp($b['recorded_at'], $a['recorded_at']));
    return array_slice($rows, 0, $limit);
}

function read_attendance_all(int $limit = 200): array {
    ensure_data_storage();
    $rows = [];
    $fh = fopen(ATTENDANCE_FILE, 'r');
    if (!$fh) return $rows;
    $header = fgetcsv($fh);
    while (($row = fgetcsv($fh)) !== false) {
        $rows[] = [
            'date' => $row[0] ?? '',
            'period' => $row[1] ?? '',
            'teacher_id' => $row[2] ?? '',
            'full_name' => $row[3] ?? '',
            'department' => $row[4] ?? '',
            'recorded_at' => $row[5] ?? '',
        ];
    }
    fclose($fh);
    usort($rows, fn($a,$b) => strcmp($b['recorded_at'], $a['recorded_at']));
    return array_slice($rows, 0, $limit);
}
