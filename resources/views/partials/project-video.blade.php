@php
    /**
     * Vegigvezeto video egy projekthez.
     *
     * A video sosem tolt be magatol: a poszter egy sima <img>, a <video> elem
     * csak akkor kerul a DOM-ba, amikor a latogato ratesz a lejatszasra. Amig
     * nincs feltoltve mp4, ugyanez a keret "hamarosan" allapotot mutat, igy a
     * szekcio soha nem lyukad ki.
     */
    $hasVideo = ! empty($video['src']);
@endphp

<figure class="walkthrough {{ $hasVideo ? '' : 'walkthrough-pending' }}" @if ($hasVideo) data-walkthrough @endif>
    <div class="walkthrough-frame">
        <div class="walkthrough-chrome" aria-hidden="true">
            <span class="walkthrough-dot"></span>
            <span class="walkthrough-dot"></span>
            <span class="walkthrough-dot"></span>
            <span class="walkthrough-bar">{{ mb_strtolower($video['name']) }}.hu</span>
        </div>

        <div class="walkthrough-stage" data-walkthrough-stage>
            @if ($video['poster'])
                <img
                    class="walkthrough-poster"
                    src="{{ asset($video['poster']) }}"
                    alt="{{ __(':name — walkthrough preview', ['name' => $video['name']]) }}"
                    {!! \App\Support\Media::sizeAttrs($video['poster']) !!}
                    loading="lazy"
                    decoding="async"
                >
            @endif

            @if ($hasVideo)
                {{-- A forras data-* attributumban ul, hogy a bongeszo egyetlen
                     bajtot se toltsen le, amig nincs ra kattintva. --}}
                <button
                    type="button"
                    class="walkthrough-play"
                    data-walkthrough-play
                    data-src="{{ asset($video['src']) }}"
                    data-mime="{{ $video['mime'] }}"
                    aria-label="{{ __('Play the :name walkthrough', ['name' => $video['name']]) }}"
                >
                    <span class="walkthrough-play-icon" aria-hidden="true"></span>
                    <span class="walkthrough-play-label">
                        {{ __('Watch walkthrough') }}
                        @if ($video['duration'])
                            <span class="walkthrough-play-duration">{{ $video['duration'] }}</span>
                        @endif
                    </span>
                </button>
            @else
                <span class="status status-pending walkthrough-pending-badge">
                    <span class="status-dot" aria-hidden="true"></span>
                    {{ __('Walkthrough recording soon') }}
                </span>
            @endif
        </div>
    </div>

    @if ($video['caption'])
        <figcaption class="t7 walkthrough-caption">{{ $video['caption'] }}</figcaption>
    @endif
</figure>
