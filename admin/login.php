<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ตรวจสอบ Username และ Password (ในห้องสอบใช้แบบ Hardcode เพื่อความรวดเร็วได้ครับ)
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 400px; margin-top: 100px;">
    <h2> เข้าสู่ระบบ Admin</h2>
    <?php if($error): ?> <div class="message" style="background:#f8d7da; color:#721c24;"><?php echo $error; ?></div> <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-submit" style="width: 100%;">Login</button>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="index.php" style="color: #6c757d; font-size: 14px;">กลับหน้าลงทะเบียน</a>
        </div>
    </form>
</div>
</body>
</html>