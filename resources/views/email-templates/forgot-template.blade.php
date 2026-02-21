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
                <table width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="background-color: #2563eb; padding: 30px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">{{ __('messages.reset_password_title') }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px; color: #333333; line-height: 1.6;">
                            <h4 style="margin-top: 0;">Olá, {{ $user->username }}</h4>
                            <p>Você solicitou a redefinição de sua senha. Clique no botão abaixo para prosseguir:</p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $actionLink }}" target="_blank" style="background-color: #2563eb; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Redefinir Senha</a>
                            </div>

                            <p style="font-size: 14px; color: #666;">Este link é válido por <strong>5 minutos</strong>.</p>
                            <p style="font-size: 14px; color: #666; border-top: 1px solid #eee; padding-top: 20px;">Se você não solicitou isso, ignore este e-mail.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px; color: #999999; font-size: 12px;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>