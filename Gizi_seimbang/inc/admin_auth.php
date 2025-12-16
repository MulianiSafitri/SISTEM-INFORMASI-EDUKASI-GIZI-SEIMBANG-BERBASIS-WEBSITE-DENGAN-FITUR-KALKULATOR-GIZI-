<?php
// inc/admin_auth.php

function is_admin_logged_in()
{
    return isset($_SESSION['admin_id']);
}

function require_admin_login()
{
    if (!is_admin_logged_in()) {
        flash('error', 'Silakan login sebagai admin.');
        redirect('admin/index.php'); // Assuming login form is on index if not logged in
    }
}

function login_admin($username, $password)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        return true;
    }
    return false;
}

function logout_admin()
{
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
}
?>