<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f6f8; padding: 20px;">
        <tr>
            <td align="center">
                <table width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td align="center" style="background-color: #10b981; padding: 30px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px;">Senha Alterada com Sucesso</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; color: #333333; line-height: 1.6;">
                            <p>Olá, <strong>{{ $user->username }}</strong>,</p>
                            <p>Este é um e-mail de confirmação para informar que a senha da sua conta foi alterada recentemente.</p>
                            <p><strong>Detalhes da Conta:</strong><br>
                               Usuário: {{ $user->username }}<br>
                               E-mail: {{ $user->email }}
                            </p>
                            <div style="background-color: #fff9c4; padding: 15px; border-left: 4px solid #fbc02d; margin: 20px 0;">
                                <p style="margin: 0; font-size: 14px;">Se você <strong>não</strong> realizou esta alteração, entre em contato com nosso suporte imediatamente para proteger sua conta.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px; color: #999999; font-size: 12px;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>