@extends('emails.layout')

@section('title', 'New registration')
@section('heading', 'New User Registration')

@section('content')
  <p style="margin:0 0 16px;">A new visitor account has been registered on {{ config('app.name') }}.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fdf4ff;border:1px solid #f5d0f3;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Name:</strong> {{ $user->name }}</p>
        <p style="margin:8px 0 0;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin:8px 0 0;"><strong>Phone:</strong> {{ $user->phone ?? '—' }}</p>
        <p style="margin:8px 0 0;"><strong>Registered:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}</p>
      </td>
    </tr>
  </table>

  <p style="margin:0;">
    <a href="{{ route('admin.users.show', $user) }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      View user in admin panel
    </a>
  </p>
@endsection
