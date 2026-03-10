<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 mb-5 mb-lg-0">
                    <div class="footer-brand mb-3">
                        @if ($settings && $settings->site_logo_light)
                            <img src="{{ asset('uploads/logo/' . $settings->site_logo_light) }}"
                                alt="{{ $settings->site_title ?? config('app.name') }}"
                                class="logo-dark"/>
                            <img src="{{ asset('uploads/logo/' . $settings->site_logo_dark) }}"
                                alt="{{ $settings->site_title ?? config('app.name') }}"
                                class="logo-white display-none"/>
                        @else
                            <span class="fw-bold">{{ config('app.name') }}</span>
                        @endif
                            
                    </div>

                    @if($settings?->site_description)
                        <p class="footer-desc">{{ $settings->site_description }}</p>
                    @endif

                    <div class="footer-contact">
                        @if($settings?->site_email)
                            <a href="mailto:{{ $settings->site_email }}" class="footer-contact-item">
                                <i class="fas fa-envelope"></i>
                                {{ $settings->site_email }}
                            </a>
                        @endif
                        @if($settings?->site_phone)
                            <a href="tel:{{ $settings->site_phone }}" class="footer-contact-item">
                                <i class="fas fa-phone"></i>
                                {{ $settings->site_phone }}
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Coluna 2: Categorias mais populares --}}
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <h6 class="footer-heading">Categorias Populares</h6>
                    @if($categories->isNotEmpty())
                        <ul class="footer-links">
                            @foreach($categories->take(4) as $category)
                                <li>
                                    <a href="{{ route('frontend.category', $category->slug) }}">
                                        {{ $category->name }}
                                        <span class="footer-link-count">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="footer-empty">Nenhuma categoria encontrada.</p>
                    @endif
                </div>

                {{-- Coluna 3: Redes Sociais --}}
                <div class="col-lg-4 col-md-12">
                    <h6 class="footer-heading">Siga-nos</h6>
                    <div class="footer-social">
                        @if(!empty($settings?->site_social_links['facebook_url']))
                            <a href="{{ $settings->site_social_links['facebook_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                        @endif
                        @if(!empty($settings?->site_social_links['twitter_url']))
                            <a href="{{ $settings->site_social_links['twitter_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-x-twitter"></i>
                                <span>Twitter / X</span>
                            </a>
                        @endif
                        @if(!empty($settings?->site_social_links['instagram_url']))
                            <a href="{{ $settings->site_social_links['instagram_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                        @endif
                        @if(!empty($settings?->site_social_links['linkedin_url']))
                            <a href="{{ $settings->site_social_links['linkedin_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                        @endif
                        @if(!empty($settings?->site_social_links['youtube_url']))
                            <a href="{{ $settings->site_social_links['youtube_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                        @endif
                        @if(!empty($settings?->site_social_links['youtube_url']))
                            <a href="{{ $settings->site_social_links['youtube_url'] }}" target="_blank" class="social-link">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                        @endif
                        @if(
                            empty($settings?->site_social_links['facebook_url']) &&
                            empty($settings?->site_social_links['twitter_url']) &&
                            empty($settings?->site_social_links['instagram_url']) &&
                            empty($settings?->site_social_links['linkedin_url']) &&
                            empty($settings?->site_social_links['youtube_url']) &&
                            empty($settings?->site_social_links['whatsapp'])
                        )
                            <p class="footer-empty">Nenhuma rede social configurada.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom">
        <div class="container">
            <p class="footer-copy">
                &copy; {{ date('Y') }} {{ $settings->site_title ?? config('app.name') }}. Todos os direitos reservados.
            </p>
        </div>
    </div>
</footer>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
.site-footer {
    background: #141414;
    border-top: 1px solid rgba(255,255,255,0.06);
    color: #888;
    font-size: 14px;
}
.footer-top { padding: 70px 0 50px; }

/* Brand */
.footer-brand { margin-bottom: 16px; }
.footer-logo { max-height: 38px; width: auto; }
.footer-site-name {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.5px;
}

/* Descrição */
.footer-desc {
    color: #666;
    line-height: 1.75;
    margin-bottom: 20px;
    max-width: 340px;
}

/* Contato */
.footer-contact { display: flex; flex-direction: column; gap: 10px; }
.footer-contact-item {
    color: #666;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    transition: color .2s;
}
.footer-contact-item i { color: #007bff; width: 14px; text-align: center; }
.footer-contact-item:hover { color: #007bff; }

/* Heading colunas */
.footer-heading {
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

/* Links categorias */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.footer-links a {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: color .2s, padding-left .2s;
}
.footer-links a:hover { color: #007bff; padding-left: 4px; }
.footer-link-count {
    font-size: 11px;
    background: rgba(255,255,255,0.06);
    color: #555;
    padding: 2px 7px;
    border-radius: 20px;
    font-weight: 600;
}

/* Redes sociais */
.footer-social { display: flex; flex-direction: column; gap: 10px; }
.social-link {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #666;
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 10px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    font-size: 14px;
    transition: background .2s, color .2s, border-color .2s;
}
.social-link i { width: 18px; text-align: center; color: #007bff; font-size: 15px; }
.social-link:hover {
    background: rgba(209,159,104,0.1);
    border-color: rgba(209,159,104,0.3);
    color: #007bff;
}

/* Empty */
.footer-empty { color: #444; font-size: 13px; font-style: italic; }

/* Bottom bar */
.footer-bottom { border-top: 1px solid rgba(255,255,255,0.05); padding: 20px 0; }
.footer-copy { color: #3a3a3a; font-size: 13px; margin: 0; text-align: center; }
</style>