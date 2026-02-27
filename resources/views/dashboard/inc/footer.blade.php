<style>
    /* ── CONFIGURAÇÕES GERAIS MIR ────────────────────────────────── */
    :root {
        --mir-primary: #6366f1; /* O azul indigo dos seus botões */
        --mir-primary-dark: #4f46e5;
        --mir-text-main: #1a1d23;
        --mir-text-sub: #9ca3af;
        --mir-border: #e9ecef;
        --mir-bg-white: #ffffff;
        --mir-shadow: 0 1px 4px rgba(0,0,0,.05);
    }

    /* ── CSS DO FOOTER ─────────────────────────────────────────── */
    .mir-footer {
        background: var(--mir-bg-white);
        padding: 20px;
        border-radius: 10px;
        border: 1px solid var(--mir-border);
        box-shadow: var(--mir-shadow);
        text-align: center;
        color: var(--mir-text-sub);
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
    }

    .mir-footer a {
        color: var(--mir-primary);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .mir-footer a:hover {
        color: var(--mir-primary-dark);
        text-decoration: underline;
    }
    </style>

<div class="mir-footer">DeskApp - Bootstrap 4 Admin Template By 
    <a href="https://github.com/dropways" target="_blank">Laara</a>
</div>