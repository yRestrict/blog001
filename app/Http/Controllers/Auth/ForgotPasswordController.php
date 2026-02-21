<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; 
use App\Mail\ForgotPasswordMail;
use App\Models\Setting;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.pages.forgot-password', [
            'pageTitle' => __('messages.forgot_password_title'),
            'siteSetting' => Setting::first() 
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'email.exists' => __('auth.email_not_found'),
        ]);

        $token = base64_encode(Str::random(40));
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $user = User::where('email', $request->email)->first();
        $actionLink = route('admin.password_reset_form', ['token' => $token]);

        try {
            Mail::to($user->email)->send(new ForgotPasswordMail($user, $actionLink));
            
            return redirect()->back()->with('success', __('auth.reset_link_sent'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', __('auth.reset_link_error'));
        }
    }
}