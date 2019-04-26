<p>Dear, {{ $user->name }}</p>

<p>If you have requested a password reset, please click the link below, to change your password</p>
<a href="{{ $resetLink }}">{{ $resetLink }}</a>
<p>If you have not requested this, please ignore this email, and no further action will take place.</p>