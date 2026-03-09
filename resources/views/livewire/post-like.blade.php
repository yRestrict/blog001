<div class="post-reactions mt-4">
    <h5 class="post-reactions__title">O que achou desse post?</h5>
    <p class="post-reactions__subtitle">
        {{ $likesCount + $dislikesCount }} resposta(s)
    </p>

    <div class="post-reactions__buttons">

        {{-- ── Like ──────────────────────────────────────────────────── --}}
        <button wire:click="react('like')"
                class="post-reactions__btn {{ $userReaction === 'like' ? 'post-reactions__btn--active' : '' }}"
                title="Gostei">
            <span class="post-reactions__emoji">😍</span>
            <span class="post-reactions__label">Curti</span>
            @if($likesCount > 0)
                <span class="post-reactions__count">{{ $likesCount }}</span>
            @endif
        </button>

        {{-- ── Dislike ────────────────────────────────────────────────── --}}
        <button wire:click="react('dislike')"
                class="post-reactions__btn {{ $userReaction === 'dislike' ? 'post-reactions__btn--active' : '' }}"
                title="Não gostei">
            <span class="post-reactions__emoji">😢</span>
            <span class="post-reactions__label">Não Curti</span>
            @if($dislikesCount > 0)
                <span class="post-reactions__count">{{ $dislikesCount }}</span>
            @endif
        </button>

    </div>

<style>
.post-reactions {
    text-align: center;
    padding: 30px 0;
    border-top: 1px solid #E6E7E7;
    border-bottom: 1px solid #E6E7E7;
    margin: 30px 0;
}

.dark .post-reactions {
    border-color: rgba(153, 153, 153, 0.15);
}

.post-reactions__title {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 4px;
}

.post-reactions__subtitle {
    color: #888;
    font-size: 0.875rem;
    margin-bottom: 20px;
}

.post-reactions__buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.post-reactions__btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    background: none;
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 12px 24px;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 90px;
}

.post-reactions__btn:hover {
    border-color: #E6E7E7;
    transform: translateY(-3px);
}

.dark .post-reactions__btn:hover {
    border-color: rgba(153, 153, 153, 0.3);
}

.post-reactions__btn--active {
    border-color: #007bff !important;
    background: rgba(0, 123, 255, 0.05);
}

.dark .post-reactions__btn--active {
    background: rgba(0, 123, 255, 0.1);
}

.post-reactions__emoji {
    font-size: 2.2rem;
    line-height: 1;
    transition: transform 0.2s ease;
}

.post-reactions__btn:hover .post-reactions__emoji,
.post-reactions__btn--active .post-reactions__emoji {
    transform: scale(1.2);
}

.post-reactions__label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #555;
}

.dark .post-reactions__label {
    color: #aaa;
}

.post-reactions__count {
    font-size: 0.75rem;
    color: #007bff;
    font-weight: 700;
}
</style>
</div>