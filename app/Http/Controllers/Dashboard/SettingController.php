<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function generalSettings(Request $request)
    {
        $data = [
            'pageTitle' => 'Configurações Gerais',
        ];
        return view('dashboard.setting.index', $data);
    }
}
