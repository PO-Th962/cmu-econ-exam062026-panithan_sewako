<?php
include '../db.php';
$message = '';
$validToken = false;

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>ไม่พบรหัส Token สำหรับการเข้าถึงหน้านี้</div>";
} else {
    // ตรวจสอบ Token ว่าตรงในระบบ และเวลาปัจจุบันยังไม่เกินเวลาหมดอายุ (NOW())
    $stmt = $conn->prepare("SELECT * FROM admins WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);
    $admin = $stmt->fetch();

    if ($admin) {
        $validToken = true; // Token ถูกต้อง แสดงฟอร์มเปลี่ยนรหัสได้
    } else {
        $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>Token ไม่ถูกต้อง หรือ ลิงก์หมดอายุไปแล้ว (เกิน 15 นาที)</div>";
    }
}

// เมื่อมีการกดบันทึกรหัสผ่านใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password === $confirm_password) {
        // อัปเดตรหัสผ่านใหม่ และทำการล้าง Token ออกทันที (Set เป็น NULL) เพื่อความปลอดภัยไม่ให้ใช้ซ้ำได้อีก
        $updateStmt = $conn->prepare("UPDATE admins SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
        $updateStmt->execute([$new_password, $token]);

        $message = "<div class='message' style='background:#d4edda; color:#155724;'>เปลี่ยนรหัสผ่านใหม่สำเร็จแล้วค่ะ! <a href='login.php'>คลิกที่นี่เพื่อล็อกอินใหม่</a></div>";
        $validToken = false; // ปิดฟอร์มลงไป
    } else {
        $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตั้งรหัสผ่านใหม่ - Admin</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="container" style="max-width: 450px; margin-top: 100px;">
    <h2> ตั้งรหัสผ่านใหม่สำหรับ Admin</h2>
    
    <?php echo $message; ?>

    <?php if ($validToken): ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>รหัสผ่านใหม่</label>
            <input type="password" class="form-control" name="new_password" required minlength="4">
        </div>
        <div class="form-group">
            <label>ยืนยันรหัสผ่านใหม่</label>
            <input type="password" class="form-control" name="confirm_password" required minlength="4">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-submit" style="width: 100%;">บันทึกและเปลี่ยนรหัสผ่าน</button>
        </div>
    </form>
    <?php endif; ?>
</div>
</body>
</html>