<?php
require_once __DIR__ . '/../helpers.php';
start_session();
session_unset();
session_destroy();
redirect('/public/login.php');
