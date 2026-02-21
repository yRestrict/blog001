<div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <input type="file" id="ProfilePicture" class="d-none" accept="image/jpeg,image/jpg,image/png,image/webp" onchange="showCropModal(event)">    
                    <a href="javascript:void(0)" 
                    onclick="document.getElementById('ProfilePicture').click();" 
                    class="edit-avatar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    
                    <img src="{{ $user->picture }}" alt="" class="avatar-photo" id="user_profile_img">
                </div>

                {{-- Modal --}}
                <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cropModalLabel">Recortar Foto de Perfil</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="img-container">
                                    <img id="CropImagePreview" style="max-width: 100%; display: block;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" id="crop_button">Cortar e Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="text-center h5 mb-0">{{ $user->name }}</h5>
                <p class="text-center text-muted font-14">
                    {{ $user->email }}
                </p>
                <div class="profile-social">
                    <h5 class="mb-20 h5 text-blue text-center">Social Links</h5>
                    <ul class="clearfix d-flex justify-content-center align-items-center gap-3">
                        {{-- Facebook --}}
                        @if($user->socialLinks?->facebook_url)
                        <li>
                            <a href="{{ $user->socialLinks->facebook_url }}" target="_blank" class="btn" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        @endif

                        {{-- Twitter --}}
                        @if($user->socialLinks?->twitter_url)
                        <li>
                            <a href="{{ $user->socialLinks->twitter_url }}" target="_blank" class="btn" data-bgcolor="#1da1f2" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(29, 161, 242);">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        @endif

                        {{-- Instagram --}}
                        @if($user->socialLinks?->instagram_url)
                        <li>
                            <a href="{{ $user->socialLinks->instagram_url }}" target="_blank" class="btn" data-bgcolor="#f46f30" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(244, 111, 48);">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                        @endif

                        {{-- YouTube --}}
                        @if($user->socialLinks?->youtube_url)
                        <li>
                            <a href="{{ $user->socialLinks->youtube_url }}" target="_blank" class="btn" data-bgcolor="#FF0000" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(255, 0, 0);">
                                <i class="fa fa-youtube"></i>
                            </a>
                        </li>
                        @endif

                        {{-- WhatsApp --}}
                        @if($user->socialLinks?->whatsapp_url)
                        <li>
                            <a href="{{ $user->socialLinks->whatsapp_url }}" target="_blank" class="btn" data-bgcolor="#25D366" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(37, 211, 102);">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                        </li>
                        @endif

                        {{-- Steam --}}
                        @if($user->socialLinks?->steam_url)
                        <li>
                            <a href="{{ $user->socialLinks->steam_url }}" target="_blank" class="btn" data-bgcolor="#171a21" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(23, 26, 33);">
                                <i class="fa fa-steam"></i>
                            </a>
                        </li>
                        @endif
                    </ul>

                    @if(!$user->socialLinks?->hasAnyLink())
                    <p class="text-muted text-center">
                        <small>Nenhuma rede social cadastrada ainda.</small>
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click="selectTab('personal_details')" class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab"
                                    href="#personal_details" role="tab">Personal details</a>
                            </li>

                            <li class="nav-item">
                                <a wire:click="selectTab('update_password')" class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab"
                                    href="#update_password" role="tab">Update password</a>
                            </li>

                            <li class="nav-item">
                                <a wire:click="selectTab('social_link')"class="nav-link {{ $tab == 'social_link' ? 'active' : '' }}" data-toggle="tab"
                                    href="#social_links" role="tab">Social Links</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'show active' : '' }}"
                                id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit.prevent="updatePersonalDetails">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Full name</label>
                                                    <input type="text" class="form-control" wire:model.defer="name" placeholder="Enter Full Name">

                                                    @error('name')
                                                    <div class="mb-2">
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>                      
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="text" class="form-control" wire:model.defer="email" placeholder="Enter Email" disabled>

                                                    @error('email')
                                                    <div class="mb-2">
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>                      
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Username</label>
                                                    <input type="text" class="form-control" wire:model.defer="username" placeholder="Enter Username">

                                                    @error('username')
                                                    <div class="mb-2">
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>                      
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Bio</label>
                                                    <textarea wire:model.defer="bio"  cols="4" rows="4" class="form-control" placeholder="Type your bio"></textarea>
                                                    @error('bio')
                                                    <div class="mb-2">
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>                      
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'show active' : '' }}"
                            id="update_password" role="tabpanel">

                            <div class="pd-20">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card shadow-sm">
                                            <div class="card-header">
                                                <h5 class="mb-1">
                                                    <i class="fa fa-lock mr-2 text-primary""></i> Security
                                                </h5>
                                                <small class="text-muted">
                                                    Change your account password
                                                </small>
                                            </div>

                                            <div class="card-body">
                                                <form wire:submit.prevent="updatePassword">

                                                    {{-- Current Password--}}
                                                    <div class="form-group">
                                                        <label>Current Password</label>
                                                        <div class="input-group mb-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text border-end-0" >
                                                                    <i class="fa fa-key"></i>
                                                                </span>
                                                            </div>
                                                            <input type="password" class="form-control @error('currentPassword') is-invalid @enderror"
                                                                wire:model.defer="currentPassword"
                                                                placeholder="Enter current password">
                                                        </div>
                                                        @error('currentPassword')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    
                                                    {{-- New Password--}}
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <div class="input-group mb-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-lock"></i>
                                                                </span>
                                                            </div>
                                                            <input type="password" class="form-control"
                                                                wire:model.defer="newPassword"
                                                                placeholder="Enter new password">
                                                        </div>
                                                        @error('newPassword')
                                                        <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    {{-- Confirm Password--}}
                                                    <div class="form-group">
                                                        <label>Confirm Password</label>
                                                        <div class="input-group mb-1">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-check"></i>
                                                                </span>
                                                            </div>
                                                            <input type="password" class="form-control"
                                                                wire:model.defer="newPassword_confirmation"
                                                                placeholder="Confirm new password">
                                                        </div>
                                                        @error('newPassword_confirmation')
                                                        <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-save mr-1"></i> Update Password
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="tab-pane fade {{ $tab == 'social_link' ? 'show active' : '' }}"
                                id="social_links" role="tabpanel">

                                @livewire('admin.social-links')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


