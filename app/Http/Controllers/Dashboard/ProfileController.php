<?php 

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{
    public function profileView(Request $request)
    {
        $data = ['pageTitle' => 'Profile'];
        return view('dashboard.profile.index', $data);
    }

    public function UpdateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProfilePicture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ProfilePicture.required' => 'Selecione uma foto.',
            'ProfilePicture.image' => 'O arquivo deve ser uma imagem.',
            'ProfilePicture.mimes' => 'Formatos aceitos: JPG, JPEG, PNG.',
            'ProfilePicture.max' => 'A foto não pode ter mais de 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => $validator->errors()->first()]);
        }


        $user = User::findOrFail(Auth::id());
        $path = 'uploads/author/';
        $file = $request->file('ProfilePicture');
        
        $filename = 'IMG_' . $user->id . '_' . uniqid() . '.png';

        if ($file->move(public_path($path), $filename)) {
            $oldPicture = $user->getRawOriginal('picture');
            if ($oldPicture && $oldPicture != 'default.png' && File::exists(public_path($path . $oldPicture))) {
                File::delete(public_path($path . $oldPicture));
            }

            // Atualiza o banco
            $user->update(['picture' => $filename]);

            return response()->json([
                'status' => 1, 
                'msg' => 'Sua foto de perfil foi atualizada!'
            ]);
        }

        return response()->json(['status' => 0, 'msg' => 'Erro ao salvar o arquivo.']);
    }
}
    