<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', config('app.name'))</title>
</head>
<body style="margin:0;padding:0;background:#fce7f3;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1a0a1f;line-height:1.6;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fce7f3;padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(190,24,93,0.1);">
          <tr>
            <td style="background:linear-gradient(135deg,#2a0a32 0%,#701a75 100%);padding:24px 28px;">
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td style="width:56px;vertical-align:middle;">
                    <img src="{{ asset('images/Logo.png') }}" alt="{{ config('app.name') }}" width="48" height="48" style="display:block;border-radius:8px;" />
                  </td>
                  <td style="vertical-align:middle;padding-left:12px;">
                    <p style="margin:0;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;color:#e879f9;">{{ config('app.name') }}</p>
                    <h1 style="margin:8px 0 0;font-size:22px;line-height:1.3;color:#ffffff;">@yield('heading')</h1>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              @yield('content')
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 24px;">
              <p style="margin:0;font-size:13px;color:#6b4a6e;">
                This is an automated message from {{ config('app.name') }} · Municipality of Aborlan. Please do not reply to this email.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
