<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Films vue en {{ date('Y') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        {!! $css !!}
    </style>
</head>
<body class="font-sans antialiased px-4 my-10">

    <h1 class="text-2xl font-bold mb-1">Films vus entre 2024 et {{ date('Y') }} </h1>

    <p class="text-slate-500 text-md">
        Liste totalement subjective basée sur mes goûts.
    </p>

    <div class="my-4">
        <div class="flex flex-col gap-4">
            @foreach($tiers as $tier)
                <div class="flex flex-col sm:flex-row rounded-md p-4 gap-4 {{ $tier['color'] }} ">
                    <div class="w-[200px] flex gap-4 sm:block">
                        <div class="text-2xl font-bold">{{ $tier['letter']}}</div>
                        <div>
                            <div class="text-sm font-bold">{{ $tier['title']}}</div>
                            <div class="text-xs">{{ $tier['description']}}</div>
                        </div>
                    </div>
                    <div class="grid w-full flex-wrap gap-4" style="grid-template-columns: repeat(auto-fit, 170px)">
                        @foreach(($movies[$tier['letter']] ?? []) as $movie)
                            @php
                            $poster = sprintf("https://simkl.in/posters/%s_c.webp", $movie->poster);
                            $url = sprintf("https://www.imdb.com/title/%s/", $movie->imdb_id);
                            @endphp
                            <a
                                title="{{ $movie->title }}"
                                href="{{ $url }}"
                                target="_blank"
                                class="block aspect-170/250 overflow-hidden rounded-sm shadow-md"
                            >
                            <img alt="{{ $movie->title }}" src={{ $poster }} alt="{{ $movie->title }}" width="170" height="250" class="w-full" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
