@extends('admin.layout')
@section('title', 'My Profile')
@section('content')
<div class="page-head"><h1>My Profile</h1></div>
<form class="card form-card" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" novalidate>
    @csrf @method('PUT')
    <div class="grid2">
        <x-upload name="avatar" label="Profile Photo" :current="$admin->avatar_url" hint="JPG / PNG, max 2MB" />
        <x-field name="name" label="Full Name" :value="$admin->name" required />
        <x-field name="email" type="email" label="Email Address" :value="$admin->email" required hint="This is also your login email." />
        <x-field name="phone" label="Phone" :value="$admin->phone" />
    </div>
    <button class="btn" type="submit">Update Profile</button>
</form>
@endsection
