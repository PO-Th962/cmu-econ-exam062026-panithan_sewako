<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ระบบลงทะเบียนอบรม - คณะเศรษฐศาสตร์ มช.')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Sarabun:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #9E76B4;
            --accent-color: #286428;
            --danger-color: #e53e3e;
            --success-color: #38a169;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            
            --text-main: #2b3a4a;
            --text-muted: #64748b;
            --shadow-premium: 0 20px 40px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 25px 50px rgba(0, 0, 0, 0.15);
            --border-radius-lg: 16px;
            --border-radius-md: 10px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Sarabun', 'Outfit', sans-serif;
            background-color: #f4effa;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            overflow-x: hidden;
        }

        /* Glassmorphic Container */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-premium);
            width: 100%;
            max-width: @yield('max-width', '550px');
            padding: 40px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
        }

        h2 {
            font-family: 'Sarabun', sans-serif;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 24px;
            letter-spacing: -0.5px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: var(--border-radius-md);
            font-size: 15px;
            font-family: 'Sarabun', sans-serif;
            background-color: rgba(255, 255, 255, 0.7);
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(158, 118, 180, 0.15);
        }

        /* Buttons */
        .btn-submit {
            background: var(--primary-color);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: var(--border-radius-md);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 15px rgba(158, 118, 180, 0.3);
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(158, 118, 180, 0.4);
            opacity: 0.95;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            background: #cbd5e1;
            color: #475569;
            padding: 14px 24px;
            border: none;
            border-radius: var(--border-radius-md);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #94a3b8;
            color: #1e293b;
        }

        /* Messages */
        .message-success {
            padding: 16px;
            margin-bottom: 25px;
            border-radius: var(--border-radius-md);
            background: #d4edda;
            color: #155724;
            border: 1px solid rgba(212, 237, 218, 0.5);
            font-size: 15px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(21, 87, 36, 0.05);
        }

        .message-error {
            padding: 16px;
            margin-bottom: 25px;
            border-radius: var(--border-radius-md);
            background: #f8d7da;
            color: #721c24;
            border: 1px solid rgba(248, 215, 218, 0.5);
            font-size: 15px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(114, 28, 36, 0.05);
        }

        a.link-accent {
            color: var(--primary-color);
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        a.link-accent:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 15px;
        }

        /* Footer styling */
        footer {
            margin-top: 40px;
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
        }

        /* Tables & Lists */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--border-radius-md);
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
        }

        th {
            background: var(--primary-color);
            color: white;
            padding: 16px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 14px;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        @media (max-width: 600px) {
            .glass-card {
                padding: 24px;
            }
            body {
                padding: 20px 10px;
            }
        }
    </style>
    @yield('extra-styles')
</head>
<body>

    <div class="glass-card">
        @yield('content')
    </div>

    <footer>
        &copy; {{ date('Y') }} คณะเศรษฐศาสตร์ มหาวิทยาลัยเชียงใหม่. All rights reserved.
    </footer>

    @yield('extra-scripts')
</body>
</html>
