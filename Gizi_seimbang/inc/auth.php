<?php
// inc/auth.php

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('pages/login.php');
    }
}

function get_current_user_data()
{
    global $pdo;
    if (!is_logged_in())
        return null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function register_user($data)
{
    global $pdo;

    $name = esc($data['name']);
    $email = esc($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Check email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        return ['status' => false, 'message' => 'Email sudah terdaftar.'];
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $password])) {
        return ['status' => true, 'message' => 'Registrasi berhasil. Silakan login.'];
    }
    return ['status' => false, 'message' => 'Gagal mendaftar.'];
}

function login_user($email, $password)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }
    return false;
}

function logout_user()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    session_destroy();
}
?>