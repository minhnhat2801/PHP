<?php
class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function checkSession() {
        self::init();
        if (!isset($_SESSION['admin_login'])) {
            header("Location: login.php");
            exit();
        }
    }

    public static function destroy() {
        session_destroy();
    }
}
?>
