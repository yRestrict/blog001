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

      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />


      <!-- CSS -->

      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/custom.css') }}" />


      @stack('stylesheets')
      @livewireStyles

   </head>
   <body>

    {{-- @include('exemple-menu')
    @include('exemple-page') --}}
    

    @yield('content')


      
      <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
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