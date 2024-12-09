<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify 2FA</title>
</head>
<body>
    <form action="{{ route('verify-2fa') }}" method="POST">
        @csrf
        <input type="text" name="code" placeholder="Enter 2FA Code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
