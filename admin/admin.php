<?php
session_start();
ob_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include 'db.php';


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}


class TrainingCalculator {
    private $totalApplicants;
    private $maxCapacityPerDay = 35;

    public function __construct($applicants) {
        $this->totalApplicants = max(0, intval($applicants));
    }

    public function calculateDays() {
        if ($this->totalApplicants === 0) return 0;
        return ceil($this->totalApplicants / $this->maxCapacityPerDay);
    }

    public function getChartData() {
        $days = $this->calculateDays();
        $remaining = $this->totalApplicants;
        $dataset = [];
        for ($i = 1; $i <= $days; $i++) {
            if ($remaining >= $this->maxCapacityPerDay) {
                $dataset["วันที่ $i"] = $this->maxCapacityPerDay;
                $remaining -= $this->maxCapacityPerDay;
            } else {
                if ($remaining > 0) { $dataset["วันที่ $i"] = $remaining; $remaining = 0; }
            }
        }
        return $dataset;
    }
}

// เรียกตรรกะคำนวณเมื่อมีการกดส่งฟอร์มคำนวณ
$applicantsInput = ''; $requiredDays = 0; $chartData = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc_action'])) {
    $applicantsInput = $_POST['applicants'] ?? '';
    $calc = new TrainingCalculator($applicantsInput);
    $requiredDays = $calc->calculateDays();
    $chartData = $calc->getChartData();
}

// ส่วนลบข้อมูลของ Admin
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: admin.php?msg=deleted");
    exit;
}

// ดึงข้อมูลวิเคราะห์ทำ Dashboard
$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$course_summary = $conn->query("SELECT course, COUNT(*) as total FROM users GROUP BY course")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - ระบบจัดการข้อมูล</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container" style="max-width: 1000px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2> ระบบจัดการสำหรับผู้ดูแลระบบ (Admin)</h2>
        <a href="admin.php?action=logout" style="background:#dc3545; color:white; padding:8px 15px; text-decoration:none; border-radius:5px; font-size:14px;">ออกจากระบบ</a>
    </div>

    <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 30px; border-left: 5px solid #1a73e8;">
        <h3 style="color: #1a73e8; margin-bottom: 10px;">Dashboard สรุปยอดผู้ลงทะเบียน</h3>
        <p>จำนวนผู้ลงทะเบียนทั้งหมดในระบบ: <strong><?php echo $total_users; ?></strong> คน</p>
        <ul style="margin-top: 10px; margin-left: 20px;">
            <?php foreach($course_summary as $summary): ?>
                <li><?php echo htmlspecialchars($summary['course']); ?> : <strong><?php echo $summary['total']; ?></strong> คน</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div style="background: #f1f8e9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 5px solid #7cb342;">
        <h3 style="color: #7cb342; margin-bottom: 15px;"> โปรแกรมคำนวณวันจัดอบรมขั้นต่ำ (รองรับสูงสุด 35 คน/วัน)</h3>
        <form method="POST" action="admin.php" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="calc_action" value="1">
            <div style="flex: 1;">
                <input type="number" class="form-control" name="applicants" placeholder="กรอกจำนวนผู้สมัครทั้งหมด" required value="<?php echo htmlspecialchars($applicantsInput); ?>">
            </div>
            <button type="submit" class="btn-submit" style="background:#7cb342; padding: 10px 20px; margin-top:0;">คำนวณวัน</button>
            <a href="admin.php" class="btn-cancel" style="padding: 10px 20px;">ล้างข้อมูล</a>
        </form>

        <?php if (!empty($applicantsInput)): ?>
            <div style="margin-top: 15px; font-size: 16px; font-weight: bold; color: #33691e;">
                 ผลการคำนวณ: ต้องใช้เวลาอบรมขั้นต่ำทั้งหมด <u><?php echo $requiredDays; ?></u> วัน
            </div>
            <div style="max-width: 300px; margin: 20px auto 0 auto; height: 300px;">
                <canvas id="adminPieChart"></canvas>
            </div>
        <?php endif; ?>
    </div>

    <h3>รายชื่อผู้ลงทะเบียนเข้าอบรมทั้งหมด</h3>
    <table style="margin-top: 15px;">
        <thead>
            <tr>
                <th>ชื่อ-นามสกุล</th>
                <th>อีเมล</th>
                <th>เบอร์โทร</th>
                <th>หลักสูตร</th>
                <th>วันที่เข้าอบรม</th>
                <th class="center">การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['fullname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['Tel'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['course'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($u['class_date'] ?? ''); ?></td>
                <td class="actions center">
                    <a href="admin.php?action=delete&id=<?php echo $u['id']; ?>" class="delete" onclick="return confirm('ยืนยันการลบข้อมูล?')">ลบข้อมูล</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
<?php if (!empty($chartData)): ?>
    const ctx = document.getElementById('adminPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($chartData)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($chartData)); ?>,
                backgroundColor: ['#1a73e8', '#34a853', '#fbbc05', '#ea4335', '#9c27b0'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
<?php endif; ?>
</script>
</body>
</html>