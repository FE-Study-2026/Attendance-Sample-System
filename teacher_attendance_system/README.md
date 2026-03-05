# Teacher Attendance System (PHP, File-based)

A simple **PHP** attendance system for **Computer Science** teachers.

## Features
- Username and password login
- Registration (**CS Department only**)
- AM/PM attendance recording (once per period per day)
- File-based storage:
  - Users: `data/users.json`
  - Attendance logs: `data/attendance.csv`

## Requirements
- PHP 8.x (works on PHP 7.4+ but recommended 8+)
- Local server (XAMPP/WAMP/Laragon) or PHP built-in server

## Install (Easy)
1. Extract the zip folder.
2. Put the folder inside your web server directory:
   - XAMPP: `C:\xampp\htdocs\teacher_attendance_system`
3. Visit in browser:
   - `http://localhost/teacher_attendance_system/public/`

## Run using PHP built-in server (Optional)
From the project root:
```bash
php -S localhost:8000 -t public
```
Then open:
- `http://localhost:8000`

## Notes
- Registration is restricted to the **Computer Science** department:
  - `ALLOWED_DEPARTMENT` is defined in `config.php`
- To enable the admin page:
  1. Register an account
  2. Open `data/users.json`
  3. Set your user to `"is_admin": true`
  4. Visit: `/public/admin.php`

## Folder Structure
- `public/` - website pages
- `data/` - file storage (JSON/CSV) **do not delete**
- `helpers.php` - file operations and utilities
- `config.php` - settings
