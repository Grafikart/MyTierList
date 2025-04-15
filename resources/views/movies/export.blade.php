<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tierlist de visionnage</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if($css ?? null)
        <style>
            {!! $css !!}
        </style>
    @else
        @viteReactRefresh
        @vite(['resources/js/app.tsx'])
    @endif
</head>
<body class="font-sans antialiased px-4 my-10">

<div class="flex flex-col gap-2 justify-between sm:items-center sm:flex-row">
    <div>
        <h1 class="text-2xl font-bold mb-1">Ma Tier list de films / séries</h1>

        <p class="text-slate-500 text-md">
            Liste totalement subjective basée sur mes goûts.
        </p>
    </div>
    <div class="flex gap-2 items-end text-slate-500">
        @foreach($years as $year)
            <a href="#{{ $year }}" class="year text-3xl font-semibold aria-current:text-slate-100"
               @if($year === $current_year) aria-current="true" @endif>{{ $year }}</a>
        @endforeach
    </div>
</div>

<div class="my-4">
    <div class="flex flex-col gap-4">
        @foreach($tiers as $tier)
            <div class="flex flex-col sm:flex-row rounded-md p-4 gap-4 {{ $tier['color'] }}">
                <div class="w-[200px] flex gap-4 sm:block">
                    <div class="text-2xl font-bold">{{ $tier['letter']}}</div>
                    <div>
                        <div class="text-sm font-bold">{{ $tier['title']}}</div>
                        <div class="text-xs">{{ $tier['description']}}</div>
                    </div>
                </div>
                <div class="grid w-full flex-wrap gap-4" style="grid-template-columns: repeat(auto-fit, 170px)">
                    @foreach(($movies[$tier['letter']] ?? []) as $k => $movie)
                        @php
                            $poster = sprintf("https://simkl.in/posters/%s_c.webp", $movie->poster);
                            $url = sprintf("https://www.imdb.com/title/%s/", $movie->imdb_id);
                        @endphp
                        <a
                            title="{{ $movie->title }}"
                            href="{{ $url }}"
                            target="_blank"
                            style="transition-delay: {{ min($k * 0.05, 0.5) }}s"
                            class="movie block aspect-170/250 overflow-hidden rounded-sm shadow-md aria-hidden:hidden transition-discrete aria-hidden:opacity-0 transition duration-300 starting:opacity-0 starting:translate-x-4"
                            data-year="{{ $movie->created_at->year }}"
                            @if($movie->created_at->year !== $current_year)
                                aria-hidden="true"
                            @endif
                        >
                            <img alt="{{ $movie->title }}" src={{ $poster }} alt="{{ $movie->title }}" width="170"
                                 height="250" class="w-full" />
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    const onHashChange = () => {
        if (!window.location.hash) {
            return;
        }
            const year = window.location.hash.substring(1);
            document.querySelectorAll('.year').forEach(el => el.textContent === year ? el.setAttribute('aria-current', 'true') : el.removeAttribute('aria-current'))
            document.querySelectorAll('.movie:not([aria-hidden])').forEach(el => el.setAttribute('aria-hidden', 'true'))
            document.querySelectorAll(`.movie[data-year="${year}"]`).forEach(el => el.removeAttribute('aria-hidden'))
    }
    onHashChange()
    window.addEventListener('hashchange', onHashChange);
</script>
</body>
</html>
