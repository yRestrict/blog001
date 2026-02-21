{{-- NÃO USAR MAIS --}}
@error('instagram_username')
    <small class="text-danger d-block mt-1">{{ $message }}</small>                 
@enderror

<small class="text-muted d-block mt-1">
    <i class="fa fa-info-circle"></i> 
    Digite seu @usuario (ex: @meuperfil)
</small>

   
   
Em qualquer view -
@livewire('admin.social-links')

Ou com parâmetros 
@livewire('admin.social-links', ['userId' => $user->id])
E ajuste o componente para aceitar:
phppublic $userId;

public function mount($userId = null)
{
    $user = $userId ? User::find($userId) : Auth::user();
    // ... resto do código
} 