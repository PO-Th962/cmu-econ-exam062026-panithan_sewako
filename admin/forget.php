<?php
include '../db.php';
$message = '';
$simulatedEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    // ตรวจสอบว่ามีอีเมลนี้ในระบบไหม
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin) {
        // 1. สร้าง Token แบบสุ่มที่มีความปลอดภัยสูง
        $token = bin2hex(random_bytes(32));
        // 2. ตั้งเวลาหมดอายุของลิงก์กู้คืน (เช่น อีก 15 นาทีข้างหน้า)
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // 3. บันทึก Token และเวลาหมดอายุลง Database ของ Admin คนนั้น
        $updateStmt = $conn->prepare("UPDATE admins SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $updateStmt->execute([$token, $expiry, $email]);

        // 4. สร้างลิงก์ที่จะแนบไปในอีเมล
        $resetLink = "http://localhost:8080/admin/reset.php?token=" . $token;

        $message = "<div class='message' style='background:#d4edda; color:#155724;'>ระบบได้ตรวจสอบตัวตนสำเร็จแล้ว! กรุณาตรวจสอบอีเมลจำลองด้านล่าง</div>";
        
        // กล่องข้อความจำลองอีเมลส่งเข้าเครื่องผู้ใช้จริง
        $simulatedEmail = "
            <div style='background: #fff; border: 2px dashed #1a73e8; padding: 20px; margin-top: 20px; border-radius: 8px;'>
                <h4 style='color: #1a73e8; margin-top: 0;'>📧 [กล่องจำลองระบบส่งอีเมลเข้าสู่ Inbox]</h4>
                <p><strong>จาก:</strong> security-system@econ.cmu.ac.th</p>
                <p><strong>ถึง:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>หัวข้อ:</strong> ยืนยันคำขอเปลี่ยนรหัสผ่านระบบ Admin</p>
                <hr style='border:0; border-top:1px solid #ccc; margin:10px 0;'>
                <p>เรียนผู้ดูแลระบบ, มีคำขอเปลี่ยนรหัสผ่านเข้ามาในระบบ หากคุณเป็นผู้ส่งคำขอนี้ กรุณาคลิกลิงก์ด้านล่างเพื่อตั้งรหัสผ่านใหม่ภายใน 15 นาที:</p>
                <p style='text-align:center; margin:20px 0;'>
                    <a href='" . $resetLink . "' style='background:#1a73e8; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>คลิกที่นี่เพื่อตั้งรหัสผ่านใหม่</a>
                </p>
                <small style='color:red;'>*ลิงก์นี้จะหมดอายุในเวลา: " . $expiry . "</small>
            </div>";
    } else {
        $message = "<div class='message' style='background:#f8d7da; color:#721c24;'>ไม่พบที่อยู่อีเมลนี้ในระบบผู้ดูแลระบบ!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลืมรหัสผ่าน - Admin</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2>🔑 กู้คืนรหัสผ่านผู้ดูแลระบบ</h2>
    <p style="color:#6c757d; margin-bottom: 20px;">กรุณากรอกอีเมลแอดมินที่ลงทะเบียนไว้ ระบบจะส่งลิงก์ยืนยันตัวตนเพื่อเปลี่ยนรหัสผ่าน</p>
    
    <?php echo $message; ?>

    <form method="POST" action="forgot.php">
        <div class="form-group">
            <label>อีเมลของ Admin (สำหรับทดสอบกรอก: admin@econ.cmu.ac.th)</label>
            <input type="email" class="form-control" name="email" required placeholder="example@domain.com">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-submit" style="width: 100%;">ส่งลิงก์กู้คืนรหัสผ่าน</button>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="login.php" style="color: #6c757d; font-size: 14px; text-decoration: none;">ย้อนกลับไปหน้า Login</a>
        </div>
    </form>

    <?php echo $simulatedEmail; ?>
</div>
</body>
</html>