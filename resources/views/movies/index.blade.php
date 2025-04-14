<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Movies tier list</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/js/app.tsx'])
</head>
<body class="font-sans antialiased px-4 my-10">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Tierlist</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('export') }}" class="btn btn-neutral">
                <svg class="size-[1.2em]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 4c0-.5523-.4477-1-1-1H3c-.5523 0-1 .4477-1 1v16c0 .5523.4477 1 1 1h18c.5523 0 1-.4477 1-1V4ZM4 15h3.416c.7716 1.7659 2.5337 3 4.584 3 2.0503 0 3.8124-1.2341 4.584-3H20v4H4v-4ZM4 5h16v8h-5c0 1.6569-1.3431 3-3 3s-3-1.3431-3-3H4V5Zm12 6h-3v3h-2v-3H8l4-4.5 4 4.5Z"/>
                </svg>
                Exporter
            </a>
            <a href="{{ route('sync') }}" class="btn btn-primary">
                <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M5.46257 4.43262C7.21556 2.91688 9.5007 2 12 2C17.5228 2 22 6.47715 22 12C22 14.1361 21.3302 16.1158 20.1892 17.7406L17 12H20C20 7.58172 16.4183 4 12 4C9.84982 4 7.89777 4.84827 6.46023 6.22842L5.46257 4.43262ZM18.5374 19.5674C16.7844 21.0831 14.4993 22 12 22C6.47715 22 2 17.5228 2 12C2 9.86386 2.66979 7.88416 3.8108 6.25944L7 12H4C4 16.4183 7.58172 20 12 20C14.1502 20 16.1022 19.1517 17.5398 17.7716L18.5374 19.5674Z"></path></svg>
                Importer les films
            </a>
        </div>
    </div>

    <tier-list movies="{{ $movies->toJson() }}" tiers="{{ $tiers->toJson() }}"></tier-list>

</body>
</html>
