-- เพิ่มตารางสำหรับเก็บข้อมูล Admin และ Token กู้คืนรหัสผ่าน
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    reset_token VARCHAR(100) DEFAULT NULL,
    token_expiry DATETIME DEFAULT NULL
);

-- เพิ่มข้อมูล Admin เริ่มต้นสำหรับการทดสอบ (Username: admin / Password: password / Email: admin@econ.cmu.ac.th)
INSERT INTO admins (username, password, email) 
VALUES ('admin', 'password', 'admin@econ.cmu.ac.th')
ON DUPLICATE KEY UPDATE username=username;