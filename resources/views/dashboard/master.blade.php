<!DOCTYPE html>
<html>
   <head>
      <!-- Basic Page Info -->
      <meta charset="utf-8" />
      <title>@yield('pageTitle')</title>
      
      <!-- Site favicon -->
      <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('dashboard/vendors/images/apple-touch-icon.png') }}" />
      <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('dashboard/vendors/images/favicon-32x32.png') }}" />
      <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('dashboard/vendors/images/favicon-16x16.png') }}" />

      <!-- Mobile Specific Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

      <!-- Google Font -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

      <!-- CSS -->
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/core.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/icon-font.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/style.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/toastr.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/cropper.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/custom.css') }}" />


      @stack('stylesheets')
      @livewireStyles

   </head>
   <body>
      {{-- NavBar --}}
      @include("dashboard.inc.navbar")

      {{-- Config Color  --}}
      <div class="right-sidebar">
         <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
               Layout Settings
               <span class="btn-block font-weight-400 font-12"
                  >User Interface Settings</span
                  >
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
               <i class="icon-copy ion-close-round"></i>
            </div>
         </div>
         <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
               <h4 class="weight-600 font-18 pb-10">Header Background</h4>
               <div class="sidebar-btn-group pb-30 mb-10">
                  <a
                     href="javascript:void(0);"
                     class="btn btn-outline-primary header-white active"
                     >White</a
                     >
                  <a
                     href="javascript:void(0);"
                     class="btn btn-outline-primary header-dark"
                     >Dark</a
                     >
               </div>
               <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
               <div class="sidebar-btn-group pb-30 mb-10">
                  <a
                     href="javascript:void(0);"
                     class="btn btn-outline-primary sidebar-light"
                     >White</a
                     >
                  <a
                     href="javascript:void(0);"
                     class="btn btn-outline-primary sidebar-dark active"
                     >Dark</a
                     >
               </div>
               <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
               <div class="sidebar-radio-group pb-10 mb-10">
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebaricon-1"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-1"
                        checked=""
                        />
                     <label class="custom-control-label" for="sidebaricon-1"
                        ><i class="fa fa-angle-down"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebaricon-2"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-2"
                        />
                     <label class="custom-control-label" for="sidebaricon-2"
                        ><i class="ion-plus-round"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebaricon-3"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-3"
                        />
                     <label class="custom-control-label" for="sidebaricon-3"
                        ><i class="fa fa-angle-double-right"></i
                        ></label>
                  </div>
               </div>
               <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
               <div class="sidebar-radio-group pb-30 mb-10">
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-1"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-1"
                        checked=""
                        />
                     <label class="custom-control-label" for="sidebariconlist-1"
                        ><i class="ion-minus-round"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-2"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-2"
                        />
                     <label class="custom-control-label" for="sidebariconlist-2"
                        ><i class="fa fa-circle-o" aria-hidden="true"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-3"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-3"
                        />
                     <label class="custom-control-label" for="sidebariconlist-3"
                        ><i class="dw dw-check"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-4"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-4"
                        checked=""
                        />
                     <label class="custom-control-label" for="sidebariconlist-4"
                        ><i class="icon-copy dw dw-next-2"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-5"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-5"
                        />
                     <label class="custom-control-label" for="sidebariconlist-5"
                        ><i class="dw dw-fast-forward-1"></i
                        ></label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input
                        type="radio"
                        id="sidebariconlist-6"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-6"
                        />
                     <label class="custom-control-label" for="sidebariconlist-6"
                        ><i class="dw dw-next"></i
                        ></label>
                  </div>
               </div>
               <div class="reset-options pt-30 text-center">
                  <button class="btn btn-danger" id="reset-settings">
                  Reset Settings
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      {{-- Right Sidebar --}}
      @include("dashboard.inc.right-sidebar")

      <div class="mobile-menu-overlay"></div>

      {{-- Conteudo --}}
      <div class="main-container">
         <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
               <div class="">
                  @yield('content')
               </div>
            </div>
            {{-- Footer --}}
            @include("dashboard.inc.footer")
         </div>
      </div>

      <!-- js -->
      <script src="{{ asset('dashboard/vendors/scripts/core.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/script.min.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/process.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/layout-settings.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/toastr.min.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/cropper.min.js') }}"></script>
      <script src="{{ asset('dashboard/vendors/scripts/switch.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
      

      <script>
      let cropper;
      const imagePreview = document.getElementById('CropImagePreview'); 
      const fileInput = document.getElementById('ProfilePicture');
      const cropModal = $('#cropModal');
      const saveBtn = $('#crop_button');

      function showCropModal(event) {
         const file = event.target.files[0];
         if (!file) return;

         const allowed = ['image/JPG', 'image/jpeg', 'image/png'];
         if (!allowed.includes(file.type)) {
            toastr.error('Formato inválido. Use JPG, JPEG, PNG');
            fileInput.value = '';
            return;
         }

         if (file.size > 2 * 1024 * 1024) {
            toastr.error('Imagem maior que 2MB.');
            fileInput.value = '';
            return;
         }

         const reader = new FileReader();
         reader.onload = function (e) {
            imagePreview.src = e.target.result;
            cropModal.modal('show');

         };
         reader.readAsDataURL(file);
      }

      // Cropper
      cropModal.on('shown.bs.modal', function () {
         cropper = new Cropper(imagePreview, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1
         });
      }).on('hidden.bs.modal', function () {
         cropper.destroy();
         cropper = null;
         fileInput.value = '';
      });

      // Envio
      saveBtn.on('click', function () {
         const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });

         canvas.toBlob(function (blob) {
            const formData = new FormData();
            formData.append('ProfilePicture', blob, 'avatar.png');
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                  url: "{{ route('admin.update_profile_picture') }}",
                  method: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  beforeSend: function () {
                     saveBtn.prop('disabled', true).html('Salvando...');
                  },
                  success: function (response) {
                     if (response.status == 1) {
                        cropModal.modal('hide');
                        toastr.success(response.msg);
                        Livewire.dispatch('UpdateProfileInfo');
                     } else {
                        toastr.error(response.msg);
                     }
                  },
                  complete: function () {
                     saveBtn.prop('disabled', false).text('Cortar e Salvar');
                  }
            });
         }, 'image/png');
      });
      </script>
      @stack('scripts')
      @livewireScripts
      <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
   </body>
</html>