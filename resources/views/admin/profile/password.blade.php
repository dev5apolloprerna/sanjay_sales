@extends('admin.layout')
@section('title', 'Change Password')
@section('content')
<div class="page-head"><h1>Change Password</h1></div>
<form class="card" style="max-width:480px" method="POST" action="{{ route('admin.password.update') }}" novalidate>
    @csrf @method('PUT')
    <x-field name="current_password" type="password" label="Current Password" required class="pw-f" />
    <x-field name="password" type="password" label="New Password" required hint="Minimum 6 characters" class="pw-f" />
    <x-field name="password_confirmation" type="password" label="Confirm New Password" required class="pw-f" />
    <div class="field"><label class="chk"><input type="checkbox" data-toggle-pw=".pw-f"> Show passwords</label></div>
    <button class="btn" type="submit">Change Password</button>
</form>
@endsection
