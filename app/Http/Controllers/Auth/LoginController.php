<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserStatus;
use App\Models\Setting;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.pages.login', [
            'pageTitle' => __('messages.login_title') ,
            'siteSetting' => Setting::first()
        ]);
    }

    public function login(Request $request) 
    {
        // 1. Determina se é e-mail ou username
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 2. Define a mensagem personalizada baseada no tipo de campo
        $errorMessage = ($fieldType === 'email') 
            ? __('auth.login_not_found_email') 
            : __('auth.login_not_found_username');

        // 3. Validação robusta
        $request->validate([
            'login_id' => "required|exists:users,$fieldType", 
            'password' => 'required|min:5',
        ], [
            'login_id.required' => __('auth.login_required'),
            'login_id.exists'   => $errorMessage, 
            'password.required' => __('auth.password_required'),
            'password.min'      => __('auth.password_min', ['min' => 5]),
        ]);

        // 4. Tentativa de login
        $credentials = [$fieldType => $request->login_id, 'password' => $request->password];
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Verificação de Status 
            if (Auth::user()->status !== UserStatus::Active) {
                $status = Auth::user()->status->value;
                Auth::logout();
                
                $message = __('auth.account_' . $status) 
                    ?? __('auth.account_status_error', ['status' => $status]);
                
                return redirect()->route('admin.login')->with('fail', $message);
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        // Se chegar aqui, o usuário existe (passou na validação), mas a senha está errada
        return redirect()->route('admin.login')
            ->withInput($request->only('login_id'))
            ->with('fail', __('auth.incorrect_password'));
    }
}