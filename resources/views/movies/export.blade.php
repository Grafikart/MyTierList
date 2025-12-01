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
        <h1 class="text-2xl font-bold mb-1">
            Ma Tier list de films / séries
        </h1>

        <p class="text-slate-500 text-md flex gap-2 items-center">
            Liste totalement subjective basée sur mes goûts.
            <a href="https://simkl.com/movies/all/all-types/all-countries/completed/all-years/release-date/user-6747973/" target="_blank" class="hover:text-white flex items-center">
                (SIMKL&nbsp;<svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h6v6m-11 5L21 3m-3 10v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>)
            </a>
        </p>
    </div>
    <div>
        <div class="text-slate-500 text-end">Année de visionnage</div>
        <div class="flex gap-2 items-baseline text-slate-500">
            @foreach($years as $year)
                <a href="#{{ $year }}" class="year text-3xl font-semibold aria-current:text-slate-100"
                   @if($year === $current_year) aria-current="true" @endif>{{ $year }}</a>
            @endforeach
        </div>
    </div>
</div>

{{--
<div class="my-4">
    @foreach($movies as $k => $movie)
        @php
            $poster = sprintf("https://simkl.in/posters/%s_c.webp", $movie->poster);
            $url = sprintf("https://www.imdb.com/title/%s/", $movie->imdb_id);
            $tier = $tiers->where('letter', $movie->tier)->first();
        @endphp
        <a
            title="{{ $movie->title }}"
            href="{{ $url }}"
            target="_blank"
            class="-mt-[1px] flex items-center gap-4 text-lg py-2 border-y-1 border-y-slate-700 border-dashed"
        >
            <img
                alt="{{ $movie->title }}"
                src={{ $poster }}
                alt="{{ $movie->title }}"
                style="border-color: var({{ str_replace('bg-','--color-', $tier['color'] ?? '') }})"
                width="170"
                height="250"
                class="w-20 border-4 rounded-sm"
            />
            <div class="{{ $tier['color'] ?? '' }} text-md leading-none py-1 px-2 rounded-md font-semibold -mr-2">
                {{ $movie->tier }}
            </div>
            {{ $movie->title }}
        </a>
    @endforeach
</div>
--}}

<div class="flex flex-col gap-4 my-4">
        @foreach($tiers as $tier)
            <div class="flex flex-col sm:flex-row rounded-md p-4 gap-4 {{ $tier['color'] }}">
                <div class="w-[200px] flex gap-4 sm:block">
                    <div class="text-2xl font-bold">{{ $tier['letter']}}</div>
                    <div>
                        <div class="text-sm font-bold">{{ $tier['title']}}</div>
                        <div class="text-xs">{{ $tier['description']}}</div>
                    </div>
                </div>
                <div class="grid w-full gap-2 sm:gap-4" style="grid-template-columns: repeat(auto-fill, min(170px, calc((100vw - 5rem - 20px) * 0.5)))">
                    @foreach(($movies_by_tiers[$tier['letter']] ?? []) as $k => $movie)
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
