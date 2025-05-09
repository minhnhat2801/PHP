<?php
session_start();
include 'admin/database.php'; // Kết nối database
$db = new Database();    
$conn = $db->conn;

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = ($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = 'customer'; // Mặc định là customer

    $sql = "INSERT INTO users (username, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $email, $password, $phone, $address, $role);
    
    if ($stmt->execute()) {
        header("Location: login.php?success=Đăng ký thành công! Vui lòng đăng nhập.");
        exit();
    } else {
        echo "<script>alert('Lỗi đăng ký!');</script>";
    }
    $stmt->close();
}

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id']; // Lưu user_id vào session
        $_SESSION['role'] = $user['role']; // Lưu role vào session

        if ($user['role'] === 'admin') {
            header("Location: admin/admin_dashboard.php"); // Chuyển hướng admin
        } else {
            $redirect_url = $_SESSION['redirect_url'] ?? 'index.php';
            unset($_SESSION['redirect_url']); // Xóa sau khi dùng
            header("Location: $redirect_url");
        }
        exit();
    } else {
        echo "<script>alert('Email hoặc mật khẩu không đúng!');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập & Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-container { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h3 class="text-center">Đăng nhập</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mật khẩu:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Đăng nhập</button>
                <p class="text-center mt-2">Chưa có tài khoản? <a href="#" onclick="showRegister()">Đăng ký</a></p>
            </form>
        </div>
        
        <div class="form-container" style="display: none;" id="registerForm">
            <h3 class="text-center">Đăng ký</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Tên người dùng:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mật khẩu:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Số điện thoại:</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Địa chỉ:</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Đăng ký</button>
                <p class="text-center mt-2">Đã có tài khoản? <a href="#" onclick="showLogin()">Đăng nhập</a></p>
            </form>
        </div>
    </div>
    
    <script>
        function showRegister() {
            document.querySelector('.form-container').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
        }
        function showLogin() {
            document.querySelector('.form-container').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
        }
    </script>
</body>
</html>