 /* -----------------------------------
           dark-mode
    ----------------------------------- */
    const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    const logoDark = document.querySelector('.logo-dark');
    const logoWhite = document.querySelector('.logo-white');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);

        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
            document.body.classList.toggle("dark");
            logoDark.classList.add('display-none');
           logoWhite.classList.add('display-block'); 
        }
    }

    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            document.body.classList.add('dark');
            logoDark.classList.add('display-none');
            logoWhite.classList.add('display-block');  
        }
        else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
            document.body.classList.remove('dark');
            logoDark.classList.remove('display-none');
            logoWhite.classList.remove('display-block');
        }
    }

    toggleSwitch.addEventListener('change', switchTheme, false);


    const searchToggle = document.querySelector('.search-toggle');
        const searchOverlay = document.querySelector('.search-overlay');
        const searchClose = document.querySelector('.search-close');
        const searchInput = searchOverlay ? searchOverlay.querySelector('input') : null;
        
        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', function() {
                searchOverlay.classList.remove('d-none');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 10);
                }
            });
            
            if (searchClose) {
                searchClose.addEventListener('click', function() {
                    searchOverlay.classList.add('d-none');
                });
            }
            
            // Fechar overlay ao clicar fora do modal
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    searchOverlay.classList.add('d-none');
                }
            });
            
            // Fechar overlay com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !searchOverlay.classList.contains('d-none')) {
                    searchOverlay.classList.add('d-none');
                }
            });
        }

        // ===============================
        // TOGGLE PASSWORD (PROFISSIONAL)
        // ===============================
        document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.togglePassword').forEach(btn => {
            const inputGroup = btn.closest('.input-group');
            
            if (!inputGroup) {
                console.warn('Input group não encontrado para o botão:', btn);
                return;
            }
            
            const input = inputGroup.querySelector('.js-password');
            const icon = btn.querySelector('i');
            
            if (!input || !icon) {
                console.warn('Input ou ícone não encontrado para o botão:', btn);
                return;
            }
            
            btn.style.display = 'none';
            
            input.addEventListener('input', function () {
                btn.style.display = this.value.length > 0 ? 'flex' : 'none';
            });
            
            btn.addEventListener('click', function () {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
            
            if (input.value.length > 0) {
                btn.style.display = 'flex';
            }
        });
    });

      let cropper;
      const imagePreview = document.getElementById('CropImagePreview'); // CropImagePreview
      const fileInput = document.getElementById('ProfilePicture');
      const cropModal = $('#cropModal');
      const saveBtn = $('#crop_button');

      function showCropModal(event) {
         const file = event.target.files[0];
         if (!file) return;

         // VALIDAÇÃO REAL
         const allowed = ['image/jpeg', 'image/png', 'image/webp'];
         if (!allowed.includes(file.type)) {
            toastr.error('Formato inválido. Use JPG, PNG ou WEBP.');
            fileInput.value = '';
            return;
         }

         if (file.size > 2 * 1024 * 1024) {
            toastr.error('Imagem maior que 2MB.');
            fileInput.value = '';
            return;
         }

         // Abre o crop
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










    