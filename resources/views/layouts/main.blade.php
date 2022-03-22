<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('assets/icon.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/breakpoint.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/trix.css') }}" rel="stylesheet">
    <link href="{{ asset('css/imgpreview.css') }}" rel="stylesheet">
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
        <div class="container justify-content-center">
            <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav align-items-center">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="/">Home</a>
                    <a class="nav-link {{ Request::is('explore') ? 'active' : '' }}" href="/explore">Explore</a>
                    <a class="nav-link {{ Request::is(['chatlist','chat']) ? 'active' : '' }}" href="/chatlist">Chat</a>
                    <a class="nav-link {{ Request::is('dashboard-'.Auth::user()->username) ? 'active' : '' }}" href="/dashboard-{{ Auth::user()->username }}">Dashboard</a>
                    <a class="nav-link active" href="#" data-bs-toggle="modal" data-bs-target="#upload" style="font-size: 1.5rem;"><i class="bi bi-plus-square"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container" style="margin: 5rem auto;">
    @yield('content')
    </div>

    <nav class="navbar navbar-expand-lg navbar-light fixed-bottom d-sm-none" style="background-color: #e3f2fd;">
        <div class="container justify-content-center align-items-baseline">
            <div class="navbar-bot" style="font-size: 1rem">
                <a class="link-dark mx-2 text-decoration-none" href="/">
                    @if (Request::is('/'))
                    <i class="bi bi-house-door-fill"></i>
                    @else
                    <i class="bi bi-house-door"></i>
                    @endif
                </a>
                <a class="link-dark mx-2 text-decoration-none" href="/explore">
                    <i class="bi bi-search"></i>
                </a>
                <a class="link-dark mx-2 text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#upload" style="font-size: 1.5rem;"><i class="bi bi-plus-square"></i></a>
                <a class="link-dark mx-2 text-decoration-none" href="/chatlist">
                    @if (Request::is(['chatlist','chat']))
                    <i class="bi bi-chat-square-dots-fill"></i>
                    @else
                    <i class="bi bi-chat-square-dots"></i>
                    @endif
                </a>
                <a class="link-dark mx-2 text-decoration-none" href="/dashboard-{{ Auth::user()->username }}">
                    <img src="{{ asset('storage/'.Auth::user()->image) }}" class="rounded-circle" width="18" style="aspect-ratio:1/1;">
                </a>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="upload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadLabel">Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('post') }}" method="POST" enctype="multipart/form-data" class="form-post">
                    @csrf
                    <label class="image-post" for="image-add">
                        <img id="preview-add" src="{{ asset('assets/camera.png') }}">
                        <input id="image-add" type="file" name="image" accept="image/*" class="@error('image') is-invalid @enderror" onchange="previewImage()">
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </label>
                    <label class="mb-2 w-100" for="caption">Caption (maks: 1000):
                        <input id="caption" type="hidden" name="caption" class="@error('caption') is-invalid @enderror">
                        <trix-editor input="caption"></trix-editor>
                        @error('caption')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="max-char d-none" style="color: red;">
                            Mencapai batas karakter.
                        </div>
                    </label>
                    <button type="submit" class="btn btn-primary mx-auto d-block">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/trix.js') }}"></script>
    <script src="{{ asset('js/uploadPreview.js') }}"></script>
    <script>
        addEventListener("trix-change", event => {
            const { editor } = event.target;
            const string = editor.getDocument().toString();
            let characterCount = string.length - 1;
            const maxChar = document.querySelectorAll('.max-char');
            if (characterCount > 1000) {
                maxChar.forEach(element => {
                    element.className = 'max-char d-block';
                });
            } else {
                maxChar.forEach(element => {
                    element.className = 'max-char d-none';
                });
            }
        })
    </script>
</body>
</html>
