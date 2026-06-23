@extends('layouts.app')

@section('title', 'ตั้งรหัสผ่านใหม่สำหรับ Admin - คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2>ตั้งรหัสผ่านใหม่สำหรับ Admin</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px; text-align: center;">
        กรุณากรอกรหัสผ่านใหม่สำหรับบัญชีผู้ดูแลระบบของคุณ
    </p>

    @if($errors->has('error'))
        <div class="message-error">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="message-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.reset.submit') }}">
        @csrf

        <div class="form-group">
            <label for="new_password">รหัสผ่านใหม่ (New Password)</label>
            <input type="password" id="new_password" class="form-control" name="new_password" required minlength="4" placeholder="รหัสผ่านใหม่อย่างน้อย 4 ตัวอักษร">
        </div>

        <div class="form-group">
            <label for="confirm_password">ยืนยันรหัสผ่านใหม่ (Confirm New Password)</label>
            <input type="password" id="confirm_password" class="form-control" name="confirm_password" required minlength="4" placeholder="กรอกรหัสผ่านใหม่อีกครั้ง">
        </div>

        <button type="submit" class="btn-submit mt-3">บันทึกและเปลี่ยนรหัสผ่าน</button>
    </form>
@endsection
