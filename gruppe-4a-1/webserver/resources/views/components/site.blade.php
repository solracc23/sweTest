<!DOCTYPE html>
<html data-mode={{Session::get('theme') ?? 'white'}} data-font="normal">
<head>
    <title>{{$title}}</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{$style}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --font-size: {{ session('font_size', 16) }}px;
        }
        html {
            min-height: 100vh;
        }

        body {
            margin: 0;
            font-size: var(--font-size);
            font-family: Verdana, sans-serif;
            background: var(--body-background);
            height: 100%;
            transition: font-size 0.3s ease;
        }

        .main {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        h1 {
            font-size: calc(var(--font-size) * 2);
            margin: 0.67em 0;
        }

        h2 {
            font-size: calc(var(--font-size) * 1.5);
            margin: 0.83em 0;
        }

        h3 {
            font-size: calc(var(--font-size) * 1.17);
            margin: 1em 0;
        }

        p {
            font-size: var(--font-size);
            margin: 1em 0;
        }

        a { font-size: var(--font-size); }
        button { font-size: var(--font-size); }
        input, textarea, select {
            font-size: calc(var(--font-size) * 0.9);
        }

        li { font-size: var(--font-size); }

        html[data-mode = 'dark']
        {
            --body-background: #0B0E14;
            --surface-color: #1D232A;
            --hover-color: #14384D;



            --primary-color: #1A4A66;
            --primary-not-selected: #879EAF;
            --shadow: rgba(0, 0, 0, 0.45);
            --layer3: #303840;
            --text-color: white;
            --dropdown: #232A34;
        }

        html[data-font = 'normal']
        {
            --text-color: white;
        }


        html[data-mode = 'white']{
            --hover-color: #578fac;
            --surface-color: white;
            --body-background: #1D232A;
            --primary-color: #236C93;
            --primary-not-selected: #B1CBD9;
            --shadow: #888888;
            --layer3: white;
            --text-color: black;
            --dropdown: #f9f9f9

        }

    </style>
    @stack('styles')
</head>
<body>
<div class="main">
    {!! $slot !!}
</div>
@stack('scripts')
</body>
</html>
