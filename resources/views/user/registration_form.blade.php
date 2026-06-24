@extends('layouts.app')

@section('title', 'ลงทะเบียนเข้าร่วมอบรม - คณะเศรษฐศาสตร์ มช.')

@section('extra-styles')
<style>
    .user-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(42, 82, 152, 0.08);
        border: 1px solid rgba(42, 82, 152, 0.15);
        padding: 12px 20px;
        border-radius: var(--border-radius-md);
        margin-bottom: 25px;
        font-size: 14px;
        gap: 12px;
    }
    .user-info {
        font-weight: 500;
        color: #1e3c72;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }
    .logout-link {
        color: #dc3545;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
        white-space: nowrap;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        font-size: inherit;
        flex-shrink: 0;
    }
    .logout-link:hover {
        color: #bd2130;
        text-decoration: underline;
    }
    .pdpa-box {
        background: #fff8e1;
        border: 1px solid #ffe082;
        padding: 15px;
        border-radius: var(--border-radius-md);
        margin-bottom: 25px;
        font-size: 14px;
        color: #5d4037;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .pdpa-box input[type="checkbox"] {
        margin-top: 3px;
        transform: scale(1.1);
        cursor: pointer;
        flex-shrink: 0;
    }
    .pdpa-label {
        cursor: pointer;
        line-height: 1.5;
        font-weight: normal;
        color: #5d4037;
        font-size: 13px;
    }

    /* ── Responsive ─────────────────────────── */
    @media (max-width: 600px) {
        .user-bar {
            padding: 10px 14px;
            font-size: 13px;
        }
        .pdpa-box {
            font-size: 13px;
            padding: 12px;
        }
        .pdpa-label {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .user-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .user-info {
            white-space: normal;
            overflow: visible;
        }
        .logout-link {
            font-size: 13px;
        }
    }
</style>
@endsection

@section('content')
    <div class="user-bar">
        <span class="user-info">บัญชีผู้ใช้: <strong>{{ session('user_name') }}</strong></span>
        <form method="POST" action="{{ route('user.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-link" style="background: none; border: none; padding: 0; cursor: pointer; font-size: inherit;">ออกจากระบบ</button>
        </form>
    </div>

    <h2>ลงทะเบียนเข้าร่วมการอบรม</h2>
    <p class="text-center" style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px;">กรุณากรอกข้อมูลจริงเพื่อลงทะเบียนเข้าร่วมหลักสูตรอบรมวิชาการ</p>

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

    <form method="POST" action="{{ route('registration.submit') }}">
        @csrf
        
        <div class="form-group">
            <label for="fullname">ชื่อ-นามสกุล</label>
            <input type="text" id="fullname" class="form-control" name="fullname" value="{{ old('fullname') }}" required placeholder="นาย/นาง/นางสาว สมชาย ใจดี">
        </div>

        <div class="form-group">
            <label for="email">อีเมล (Email)</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="somchay@example.com">
        </div>

        <div class="form-group">
            <label for="Tel">หมายเลขโทรศัพท์</label>
            <input type="text" id="Tel" class="form-control" name="Tel" value="{{ old('Tel') }}" required placeholder="0812345678">
        </div>

        <div class="form-group">
            <label for="course">หลักสูตรที่สนใจเข้าอบรม</label>
            <select id="course" class="form-control" name="course" required>
                <option value="">-- กรุณาเลือกหลักสูตร --</option>
                @foreach($courses as $c)
                    <option value="{{ $c->name }}" {{ old('course') === $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="class_date">เลือกวันที่เข้าอบรม</label>
            <input type="date" id="class_date" class="form-control" name="class_date" value="{{ old('class_date') }}" required>
        </div>

        <div class="pdpa-box">
            <input type="checkbox" id="pdpa_consent" name="pdpa_consent" value="1" required {{ old('pdpa_consent') ? 'checked' : '' }}>
            <label for="pdpa_consent" class="pdpa-label">
                ฉันยินยอมให้ประมวลผลข้อมูลส่วนบุคคลตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA) คณะเศรษฐศาสตร์ มหาวิทยาลัยเชียงใหม่
            </label>
        </div>

        <button type="submit" class="btn-submit" style="width: 100%;">ส่งข้อมูลลงทะเบียน</button>
    </form>
@endsection
