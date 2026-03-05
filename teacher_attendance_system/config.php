<?php
// Teacher Attendance System (File-based)
// Change APP_SECRET to a long random string for production.
define('APP_NAME', 'Teacher Attendance System');
define('APP_SECRET', 'change-this-to-a-long-random-string');
define('BASE_URL', ''); // e.g., '/teacher-attendance' if hosted in a subfolder

// Data paths (keep these outside public access if possible)
define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');
define('ATTENDANCE_FILE', DATA_DIR . '/attendance.csv');

// Registration restriction
define('ALLOWED_DEPARTMENT', 'Computer Science'); // CS Department only

// Timezone (Philippines)
date_default_timezone_set('Asia/Manila');
