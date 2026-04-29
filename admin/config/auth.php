<?php
/**
 * Authentication Helper
 * Raymaizing Admin Panel
 */

session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    require_once __DIR__ . '/database.php';
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, username, email, full_name, role, avatar FROM admin_users WHERE id = ? AND is_active = 1");
    $stmt->execute([$_SESSION['admin_user_id']]);
    return $stmt->fetch();
}

function hasRole($roles = []) {
    $user = getCurrentUser();
    if (!$user) return false;
    return in_array($user['role'], (array)$roles);
}

function login($username, $password) {
    require_once __DIR__ . '/database.php';
    $pdo = getDB();
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE (username = ? OR email = ?) AND is_active = 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        
        // Update last login
        $stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Log activity
        logActivity($user['id'], 'login', 'User logged in');
        
        return true;
    }
    
    return false;
}

function logout() {
    if (isLoggedIn()) {
        logActivity($_SESSION['admin_user_id'], 'logout', 'User logged out');
    }
    session_destroy();
    header('Location: /admin/login.php');
    exit;
}

function logActivity($userId, $action, $description = null) {
    require_once __DIR__ . '/database.php';
    $pdo = getDB();
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $action, $description, $ip]);
}
