@extends('layouts.app')

@section('title', 'Admin Dashboard - ระบบจัดการข้อมูล คณะเศรษฐศาสตร์ มช.')
@section('max-width', '1050px')

@section('extra-styles')
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            gap: 12px;
        }

        .admin-title {
            color: #1e3c72;
            font-weight: 700;
            margin-bottom: 0;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
            justify-content: flex-start;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 18px;
            border-radius: var(--border-radius-md);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #bd2130;
            transform: translateY(-1px);
        }


        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 35px;
        }

        .metric-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 24px;
            border-radius: var(--border-radius-md);
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .metric-card h3 {
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.85;
            margin-bottom: 10px;
        }

        .metric-value {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.1;
        }

        .courses-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px 24px;
            border-radius: var(--border-radius-md);
        }

        .courses-card h3 {
            color: #1e3c72;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 600;
        }

        .course-list {
            list-style: none;
        }

        .course-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
            gap: 8px;
        }

        .course-item:last-child {
            border-bottom: none;
        }

        .course-count {
            background: rgba(30, 60, 114, 0.1);
            color: #1e3c72;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 700;
            flex-shrink: 0;
        }


        .course-manage-section {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 5px solid var(--primary-color);
            padding: 25px;
            border-radius: var(--border-radius-md);
            margin-bottom: 35px;
        }

        .course-manage-title {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .course-add-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .course-add-input-wrap {
            flex: 1;
            min-width: 220px;
        }

        .course-add-btn {
            width: auto;
            margin-top: 0;
            padding: 14px 20px;
            white-space: nowrap;
        }

        .course-manage-list {
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0 15px;
        }


        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius-md);
        }

        .actions-column {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .btn-delete {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-delete:hover {
            background: #c62828;
            color: white;
        }


        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 18px;
            margin: 0;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-label {
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        .filter-date {
            width: auto;
            padding: 8px 12px;
        }

        .filter-btn {
            width: auto;
            padding: 8px 16px;
            margin: 0;
        }


        @media (max-width: 900px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .metric-value {
                font-size: 36px;
            }
        }


        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                margin-bottom: 20px;
            }

            .admin-title {
                font-size: 18px;
                text-align: center;
                justify-content: center;
            }

            .logout-btn {
                width: 100%;
                text-align: center;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 24px;
            }

            .metric-card {
                padding: 20px;
            }

            .metric-value {
                font-size: 32px;
            }

            .courses-card {
                padding: 16px 18px;
            }

            .course-manage-section {
                padding: 18px 16px;
            }

            .course-manage-title {
                font-size: 16px;
            }

            .course-add-form {
                flex-direction: column;
                gap: 10px;
            }

            .course-add-input-wrap {
                min-width: 0;
                width: 100%;
            }

            .course-add-btn {
                width: 100%;
            }

            .section-header {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .section-title {
                font-size: 16px;
            }

            .filter-form {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .filter-label {
                font-size: 13px;
            }

            .filter-date {
                width: 100%;
            }

            .filter-btn {
                width: 100%;
            }
        }


        @media (max-width: 600px) {
            .metric-card h3 {
                font-size: 13px;
            }

            .metric-value {
                font-size: 28px;
            }

            .course-item {
                font-size: 13px;
            }


            .table-container table,
            .table-container thead,
            .table-container tbody,
            .table-container th,
            .table-container td,
            .table-container tr {
                display: block;
            }

            .table-container thead {
                display: none;
            }

            .table-container tbody tr {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                margin-bottom: 12px;
                padding: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .table-container td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 7px 4px;
                border-bottom: 1px dashed #f1f5f9;
                font-size: 13px;
                text-align: left;
                background: transparent;
            }

            .table-container td:last-child {
                border-bottom: none;
                padding-top: 10px;
            }

            .table-container td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #64748b;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                flex-shrink: 0;
                margin-right: 10px;
                min-width: 80px;
            }

            .actions-column {
                justify-content: flex-end;
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="admin-header">
        <h2 class="admin-title">ระบบจัดการสำหรับผู้ดูแลระบบ (Admin Panel)</h2>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">ออกจากระบบ</button>
        </form>
    </div>

    @if(session('success'))
        <div class="message-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="message-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <h3>ผู้ลงทะเบียนทั้งหมด</h3>
            <div class="metric-value">{{ $total_users }} <span
                    style="font-size: 18px; font-weight: normal; opacity: 0.8;">คน</span></div>
        </div>
        <div class="courses-card">
            <h3>สรุปยอดผู้ลงทะเบียนตามรายหลักสูตร</h3>
            <ul class="course-list">
                @forelse($course_summary as $summary)
                    <li class="course-item">
                        <span>{{ $summary->course }}</span>
                        <span class="course-count">{{ $summary->total }} คน</span>
                    </li>
                @empty
                    <li class="course-item" style="color: var(--text-muted);">ยังไม่มีข้อมูลผู้ลงทะเบียน</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Course Management Section -->
    <div class="course-manage-section">
        <h3 class="course-manage-title">จัดการหลักสูตร (เพิ่ม/ลบ หลักสูตร)</h3>

        <form method="POST" action="{{ route('admin.course.store') }}" class="course-add-form">
            @csrf
            <div class="course-add-input-wrap">
                <input type="text" class="form-control" name="name" placeholder="ระบุชื่อหลักสูตรใหม่..." required>
            </div>
            <button type="submit" class="btn-submit course-add-btn">เพิ่มหลักสูตร</button>
        </form>

        <ul class="course-list course-manage-list">
            @forelse($all_courses as $c)
                <li class="course-item" style="align-items: center;">
                    <span style="font-weight: 500;">{{ $c->name }}</span>
                    <form method="POST" action="{{ route('admin.course.delete', $c->id) }}"
                        onsubmit="return confirm('คุณต้องการลบหลักสูตรนี้ใช่หรือไม่?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" style="border: 1px solid #ffcdd2; cursor: pointer;">ลบ</button>
                    </form>
                </li>
            @empty
                <li class="course-item" style="color: var(--text-muted); padding: 15px 0;">ยังไม่มีหลักสูตรในระบบ</li>
            @endforelse
        </ul>
    </div>


    <!-- Registration List Table -->
    <div class="section-header">
        <h3 class="section-title">รายชื่อผู้ลงทะเบียนเข้าอบรมทั้งหมด</h3>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-form">
            <label for="filter_date" class="filter-label">วันที่อบรม:</label>
            <input type="date" id="filter_date" name="date" value="{{ request('date') }}" class="form-control filter-date">
            <button type="submit" class="btn-submit filter-btn">ค้นหา</button>
            @if(request('date'))
                <a href="{{ route('admin.dashboard') }}" class="btn-cancel filter-btn">ล้างทั้งหมด</a>
            @endif
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทร</th>
                    <th>หลักสูตร</th>
                    <th style="text-align: center;">วันที่อบรม</th>
                    <th style="text-align: center;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td data-label="ชื่อ-นามสกุล" style="font-weight: 600;">{{ $u->fullname }}</td>
                        <td data-label="อีเมล">{{ $u->email }}</td>
                        <td data-label="เบอร์โทร">{{ $u->Tel }}</td>
                        <td data-label="หลักสูตร" style="color: #1e3c72;">{{ $u->course }}</td>
                        <td data-label="วันที่อบรม" style="text-align: center;">
                            {{ \Carbon\Carbon::parse($u->class_date)->format('d/m/Y') }}</td>
                        <td data-label="การจัดการ" style="text-align: center;">
                            <div class="actions-column">
                                <a href="{{ route('admin.delete', ['id' => $u->id]) }}" class="btn-delete"
                                    onclick="return confirm('คุณต้องการลบข้อมูลผู้สมัคร {{ $u->fullname }} ใช่หรือไม่?')">ลบข้อมูล</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="color: var(--text-muted); padding: 30px 16px;">
                            ยังไม่มีรายชื่อผู้ลงทะเบียนในระบบค่ะ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('extra-scripts')
@endsection