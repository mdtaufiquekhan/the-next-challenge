<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>

    <!-- Bootstrap CSS (from CDN) -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet"> --}}

    <!-- Quill Select CSS -->
    {{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> --}}

    <!-- Custom CSS -->
    {{-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> --}}
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">The Next Challenge</a>
        </div>
        <div>
            <button id="theme-toggle" class="btn btn-sm text-bg">
                🌓 Toggle Dark Mode
            </button>
        </div>
    </nav>

   
        @yield('content')

<div id="global-loader" style="display: none;">
  <div class="main-estimate-text-container">
    <div>⏳ <strong>Generating AI content... Please wait.</strong></div>
    <div id="loader-timer">Estimated wait: ~20 seconds (elapsed: 0s)</div>
  </div>
</div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

    {{-- <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script> --}}


    {{-- <script src="https://cdn.tiny.cloud/1/cv6sisl2cqbvxfz0ekcg6il19ee0oyl4yrsixhz7v15uqu9q/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> --}}


    {{-- <script src="{{ asset('js/wizard-field-behaviors.js') }}"></script> --}}


    {{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}

    <!-- Custom JS -->
    {{-- <script src="{{ asset('js/main.js') }}"></script> --}}
    @yield('footer-content')

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('theme-toggle');
            const htmlEl = document.documentElement;

            // Initialize theme from localStorage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                htmlEl.setAttribute('data-theme', 'dark');
            }

            // Toggle theme on button click
            button.addEventListener('click', function () {
                const current = htmlEl.getAttribute('data-theme');
                const newTheme = current === 'dark' ? 'light' : 'dark';
                htmlEl.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });
        });
    </script> --}}


</body>
</html>
