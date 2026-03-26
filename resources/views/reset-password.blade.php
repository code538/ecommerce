<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="utf-8">
</head>
<body>
    <h2>Reset Password</h2>

    <form method="POST" action="{{ url('/api/reset-password') }}">
        @csrf

        <input type="hidden" name="token" value="{{ request('token') }}">
        <input type="hidden" name="email" value="{{ request('email') }}">

        <div>
            <label>New Password</label><br>
            <input type="password" name="password" required>
        </div>

        <br>

        <div>
            <label>Confirm Password</label><br>
            <input type="password" name="password_confirmation" required>
        </div>

        <br>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
