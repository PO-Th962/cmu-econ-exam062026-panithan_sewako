<?php
ob_start(); 
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['Tel'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $class_date = trim($_POST['class_date'] ?? '');
    $pdpa_consent = isset($_POST['pdpa_consent']) ? 1 : 0; 

    if ($name != '' && $email != '' && $tel != '' && $course != '' && $class_date != '') {
        try {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, Tel, course, class_date, pdpa_consent) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $tel, $course, $class_date, $pdpa_consent]);
            $message = "<span style='color:green;'>ลงทะเบียนสำเร็จเรียบร้อยแล้วค่ะ!</span>";
        } catch (PDOException $e) { $message = "Error: " . $e->getMessage(); }
    } else {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบลงทะเบียนอบรม - คณะเศรษฐศาสตร์ มช.</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="login.php" style="background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">สำหรับ Admin</a>
    </div>

    <h2>ลงทะเบียนเข้าร่วมการอบรม</h2>
    
    <?php if($message): ?> <div class="message"><?php echo $message; ?></div> <?php endif; ?>

    <form method="POST" action="index.php">
        <div class="form-group">
            <label>ชื่อ-นามสกุล</label>
            <input type="text" class="form-control" name="fullname" required>
        </div>
        <div class="form-group">
            <label>อีเมล</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label>หมายเลขโทรศัพท์</label>
            <input type="text" class="form-control" name="Tel" required>
        </div>
        <div class="form-group">
            <label>หลักสูตรที่สนใจ</label>
            <select class="form-control" name="course" required>
                <option value="">-- กรุณาเลือกหลักสูตร --</option>
                <option value="การวิเคราะห์ข้อมูลด้วย Excel">การวิเคราะห์ข้อมูลด้วย Excel</option>
                <option value="การเขียนโปรแกรมด้วย Python">การเขียนโปรแกรมด้วย Python</option>
                <option value="การสร้าง Dashboard ด้วย Power BI">การสร้าง Dashboard ด้วย Power BI</option>
            </select>
        <div class="form-group">
            <label>เลือกวันที่เข้าอบรม</label>
            <input type="date" class="form-control" name="class_date" required>
        </div>

        <div class="form-group" style="background: #fff3cd; padding: 10px; border-radius: 5px;">
            <label style="font-weight: normal; font-size: 14px; cursor: pointer;">
                <input type="checkbox" name="pdpa_consent" value="1" required>
                ฉันยินยอมให้ประมวลผลข้อมูลส่วนบุคคลตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA)
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit" style="width: 100%;">ส่งข้อมูลลงทะเบียน</button>
        </div>
    </form>
</div>
</body>
</html>
<?php ob_end_flush(); ?>