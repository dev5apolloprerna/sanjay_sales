<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – Sanjay Sales Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1">
</head>
<body>
<div class="auth">
    <div class="auth-art">
        <div><span class="tagline">Admin Panel · Since 1981</span></div>
        <div>
            <h2>Manage your catalogue, gallery &amp; content in one place.</h2>
            <p>Welding machines, cutting equipment, tools and consumables — keep products, categories, photos, videos and blogs up to date for your website.</p>
        </div>
        <small style="color:#9a9ca0">© {{ date('Y') }} Sanjay Sales, Ahmedabad</small>
    </div>
    <div class="auth-form">
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <img class="auth-logo" src="{{ asset('images/logo.png') }}" alt="Sanjay Sales">
            <h1 style="font-size:26px">Welcome back</h1>
            <p class="muted" style="margin-top:0;margin-bottom:24px">Sign in to the admin panel</p>
            @if(session('success'))<div class="alert ok">{{ session('success') }}</div>@endif
            @error('email')<div class="alert">{{ $message }}</div>@enderror
            <div class="field"><label>Email address</label>
                <input class="input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@sanjaysales.com"></div>
            <div class="field"><label>Password</label>
                <div class="pw"><input class="input" type="password" name="password" required placeholder="••••••••"><button type="button" data-toggle-pw>SHOW</button></div>
                @error('password')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="field"><label class="chk"><input type="checkbox" name="remember" value="1"> Remember me</label></div>
            <button class="btn" type="submit" style="width:100%;justify-content:center;padding:12px">Sign in</button>
        </form>
    </div>
</div>
<script src="{{ asset('js/admin.js') }}?v=1"></script>
</body>
</html>
