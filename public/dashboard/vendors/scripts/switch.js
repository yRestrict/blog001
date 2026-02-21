
      // Livewire v3 - Escutando eventos do browser
      document.addEventListener('livewire:init', () => {
         Livewire.on('showToastr', (event) => {
               // Configurações do Toastr (opcional)
               toastr.options = {
                  "closeButton": true,
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "timeOut": "2500",
                  "extendedTimeOut": "1000"
               };
               
               // Dispara a notificação
               toastr[event.type](event.message);
         });
      });









    