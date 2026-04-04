<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

        <title>Página não encontrada</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/style.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/icon-font.min.css') }}" />
    </head>
    <body>
        <x-header/>

        <div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
            <div style="text-align: center; max-width: 480px;">

                {{-- Ícone --}}
                <div style="font-size: 4rem; margin-bottom: 24px; opacity: .25;">
                    <i class="las la-file-alt"></i>
                </div>

                {{-- Código --}}
                <div style="font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #9ca3af; margin-bottom: 12px;">
                    404
                </div>

                {{-- Título --}}
                <h1 style="font-size: 1.5rem; font-weight: 700; color: #1a1d23; margin-bottom: 12px; font-family: 'Inter', sans-serif;">
                    Este conteúdo não está disponível
                </h1>

                {{-- Descrição --}}
                <p style="font-size: .95rem; color: #6b7280; line-height: 1.6; margin-bottom: 32px;">
                    O conteúdo que você está tentando acessar não existe ou não está mais disponível.
                </p>

                {{-- Botões --}}
                <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('frontend.home') }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:#1a1d23;color:#fff;border-radius:8px;font-size:.875rem;font-weight:600;text-decoration:none;transition:opacity .15s;"
                       onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <i class="las la-home" style="font-size:1rem;"></i>
                        Voltar ao início
                    </a>
                    <a href="javascript:history.back()"
                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:transparent;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-size:.875rem;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="las la-arrow-left" style="font-size:1rem;"></i>
                        Voltar
                    </a>
                </div>

            </div>
        </div>

        <x-footer/>

        <script src="{{ asset('frontend/vendors/scripts/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('frontend/vendors/scripts/popper.min.js') }}"></script>
        <script src="{{ asset('frontend/vendors/scripts/bootstrap.min.js') }}"></script>
        <script src="{{ asset('frontend/vendors/scripts/switch.js') }}"></script>
        <script src="{{ asset('frontend/vendors/scripts/main.js') }}"></script>
    </body>
</html>