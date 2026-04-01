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

      <!-- Font Awesome 6 -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

      <!-- CSS -->
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/core.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/icon-font.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/style.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/toastr.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/cropper.min.css') }}" />
      <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/styles/custom.css') }}?v={{ filemtime(public_path('dashboard/vendors/styles/custom.css')) }}" />


      @stack('stylesheets')
      @livewireStyles

   </head>
   <body>
      {{-- Aplicar tema ANTES do primeiro render para evitar flash --}}
      <script>
         (function(){
            try {
               var o = JSON.parse(localStorage.getItem('optionsObject'));
               if (o) {
                  if (o.headerBackground) document.body.classList.add(o.headerBackground);
                  if (o.navigationBackground) document.body.classList.add(o.navigationBackground);
               }
            } catch(e) {}
         })();
      </script>
      {{-- NavBar --}}
      @include("dashboard.inc.navbar")

      {{-- Left Sidebar --}}
      @include("dashboard.inc.right-sidebar")

      <div class="mobile-menu-overlay"></div>

      {{-- Conteudo --}}
      <div class="main-container">
         <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px" style="margin-bottom: 1rem;">
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

      {{-- Tooltip inteligente global (design system mir-) --}}
      <script>
      (function() {
          var GAP = 8;
          var tip = document.createElement('div');
          tip.className = 'mir-tooltip';
          var arrow = document.createElement('div');
          arrow.className = 'mir-tooltip-arrow top';
          var textNode = document.createTextNode('');
          tip.appendChild(textNode);
          tip.appendChild(arrow);
          document.body.appendChild(tip);

          function show(el) {
              var text = el.getAttribute('data-tooltip');
              if (!text) return;
              textNode.textContent = text;
              tip.style.visibility = 'hidden';
              tip.classList.add('visible');
              var tw = tip.offsetWidth, th = tip.offsetHeight;
              var rect = el.getBoundingClientRect();
              var vw = window.innerWidth, vh = window.innerHeight;
              var top, left, dir;
              if (rect.top - th - GAP > 4) { top = rect.top - th - GAP; left = rect.left + rect.width/2 - tw/2; dir = 'top'; }
              else if (rect.bottom + th + GAP < vh - 4) { top = rect.bottom + GAP; left = rect.left + rect.width/2 - tw/2; dir = 'bottom'; }
              else if (rect.left - tw - GAP > 4) { top = rect.top + rect.height/2 - th/2; left = rect.left - tw - GAP; dir = 'left'; }
              else { top = rect.top + rect.height/2 - th/2; left = rect.right + GAP; dir = 'right'; }
              if (left < 4) left = 4;
              if (left + tw > vw - 4) left = vw - tw - 4;
              if (top < 4) top = 4;
              if (top + th > vh - 4) top = vh - th - 4;
              tip.style.top = top + 'px';
              tip.style.left = left + 'px';
              tip.style.visibility = 'visible';
              arrow.className = 'mir-tooltip-arrow ' + dir;
              arrow.removeAttribute('style');
              if (dir === 'top' || dir === 'bottom') {
                  var ax = rect.left + rect.width/2 - left;
                  ax = Math.max(10, Math.min(tw - 10, ax));
                  arrow.style.left = ax + 'px';
              }
          }

          function hide() { tip.classList.remove('visible'); tip.style.visibility = 'hidden'; }

          document.addEventListener('mouseover', function(e) { var el = e.target.closest('[data-tooltip]'); if (el) show(el); });
          document.addEventListener('mouseout', function(e) { var el = e.target.closest('[data-tooltip]'); if (el) hide(); });
      })();
      </script>
   </body>
</html>