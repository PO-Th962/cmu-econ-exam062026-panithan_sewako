@extends('layouts.app')

@section('title', 'ยืนยันรหัส PIN - Admin Portal คณะเศรษฐศาสตร์ มช.')

@section('content')
    <h2>ยืนยันรหัส PIN 6 หลัก</h2>
    <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 14px; text-align: center;">
        กรุณากรอกอีเมลและรหัส PIN 6 หลักที่คุณได้รับทางอีเมล เพื่อยืนยันการตั้งรหัสผ่านใหม่
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

    <form method="POST" action="{{ route('admin.verify_pin.submit') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">อีเมลของคุณ</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required readonly>
        </div>

        <div class="form-group">
            <label for="pin">รหัส PIN 6 หลัก</label>
            <input type="text" id="pin" class="form-control" name="pin" required maxlength="6" pattern="\d{6}" placeholder="123456" style="letter-spacing: 5px; font-size: 20px; text-align: center;">
            <small style="color: var(--text-muted); display: block; margin-top: 5px; text-align: center;">*รหัสผ่านมีอายุ 15 นาที</small>
        </div>

        <button type="submit" class="btn-submit mt-3">ตรวจสอบรหัส PIN</button>

        <div class="text-center mt-3">
            <a href="{{ route('admin.forget') }}" class="link-accent" style="color: var(--text-muted);">ขอรหัส PIN ใหม่</a>
        </div>
    </form>
@endsection
