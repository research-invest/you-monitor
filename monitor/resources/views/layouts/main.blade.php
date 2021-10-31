<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Json rpc demo project</title>
    <!-- Bootstrap core CSS -->
    <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
    <link href="{{asset('/css/blog.css')}}" rel="stylesheet">
</head>
<body>

<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-11 text-center">
                <a class="blog-header-logo text-dark" href="/">Json RPC News</a>
            </div>
            <div class="col-1 pt-1">
                <a class="link-secondary" href="{{ route('admin') }}">Admin</a>
            </div>
        </div>
    </header>

    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'world']) }}">World</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'technology']) }}">Technology</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'design']) }}">Design</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'culture']) }}">Culture</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'business']) }}">Business</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'politics']) }}">Politics</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'science']) }}">Science</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'health']) }}">Health</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'style']) }}">Style</a>
            <a class="p-2 link-secondary" href="{{ route('category', ['category' => 'travel']) }}">Travel</a>
        </nav>
    </div>
</div>

@yield('content')

<footer class="blog-footer">
    <p>
        <a href="#">Back to top</a>
    </p>
    <p>
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </p>
</footer>

</body>
</html>
