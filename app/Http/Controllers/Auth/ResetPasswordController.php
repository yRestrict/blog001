<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class ResetPasswordController extends Controller
{
    /*  Tempo de expiração em minutos */
     
    private const TOKEN_EXPIRATION_MINUTES = 5;
    /*
     * Mostra o formulário de reset. 
     * Verifica logo se o token é válido para não deixar o user ver a página à toa.
     */
    public function showResetForm(Request $request, $token = null)
    {
        $check_token = DB::table('password_reset_tokens')
                         ->where('token', $token)
                         ->first();

        if (!$check_token || Carbon::parse($check_token->created_at)->addMinutes(self::TOKEN_EXPIRATION_MINUTES)->isPast()) {
            return redirect()->route('admin.forgot')->with('fail', __('auth.token_expired'));
        }

        return view('auth.pages.reset', [
            'pageTitle' => __('messages.reset_password_title'),
            'token' => $token,
            'siteSetting' => Setting::first()
        ]);
    }

    /**
     * Processa a alteração da senha
     */
    public function resetPassword(Request $request)
    {
        // 1. Validação com mensagens customizadas
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|min:5|confirmed',
        ], [
            'new_password.required' => __('auth.new_password_required'),
            'new_password.min' => __('auth.new_password_min', ['min' => 5]),
            'new_password.confirmed' => __('auth.new_password_confirmed'),
        ]);

        // 2. Verifica se o token ainda existe no banco
        $dbToken = DB::table('password_reset_tokens')
                     ->where('token', $request->token)
                     ->first();

        // Verificação de expiração também no processamento 
        if (!$dbToken || Carbon::parse($dbToken->created_at)->addMinutes(self::TOKEN_EXPIRATION_MINUTES)->isPast()) {
            return redirect()->route('admin.forgot')->with('fail', __('auth.token_expired'));
        }

        // 3. Busca o usuário
        $user = User::where('email', $dbToken->email)->first();

        if (!$user) {
            return redirect()->route('admin.forgot')->with('fail', __('auth.user_not_found'));
        }

        // 4. Atualiza a senha
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // 5. Envia e-mail de confirmação
        $this->sendConfirmationEmail($user, $request->new_password);

        // 6. Elimina o token usado
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return redirect()->route('admin.login')->with('success', __('auth.password_reset_success'));
    }

    private function sendConfirmationEmail($user, $newPassword)
    {
        $data = ['user' => $user, 'new_password' => $newPassword];
        $mail_body = view('email-templates.password-changes-template', $data)->render();

        $mailConfig = [
            'recipient_address' => $user->email,
            'recipient_name'    => $user->username,
            'subject'           => __('auth.password_changed_email_subject'),
            'body'              => $mail_body,
        ];

        Mail::to($user->email)->send(new PasswordChangedMail($user));
    }
}