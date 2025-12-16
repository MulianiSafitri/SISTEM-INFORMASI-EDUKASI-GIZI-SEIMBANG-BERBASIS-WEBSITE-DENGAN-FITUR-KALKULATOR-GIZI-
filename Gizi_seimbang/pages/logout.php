<?php
require_once '../inc/functions.php';
require_once '../inc/auth.php';

logout_user();
flash('success', 'Anda telah logout.');
redirect('pages/login.php');
?>