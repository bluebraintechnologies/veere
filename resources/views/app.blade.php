<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Veeere') }}</title>
        <meta name="local_token" content="aHR0cHM6Ly9kZW1vLnZlZWVyZS5jb20vaW1hZ2VzLw==">
        <meta name="media_token" content="aHR0cHM6Ly93bXMudmVlZXJlLmNvbS91cGxvYWRzL2ltZy8=">
        <meta name="site_url" content="http://127.0.0.1:9011" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="keywords" content="Veeere" />
        <meta name="description" content="Veeere">
        <meta name="author" content="Blue Brain Technologies">

        <!-- site Favicon -->
        <link rel="icon" href="{{ asset('/images/favicon.png') }}" sizes="32x32" />
        <link rel="apple-touch-icon" href="{{ asset('/images/favicon.png') }}" />
        <meta name="msapplication-TileImage" content="{{ asset('/images/favicon.png') }}" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=4">
        <style>
            @font-face {
                font-family: 'EcIcons';
                src: url('../fonts/ecicons.eot?v=4.7.0');
                src: url('../fonts/ecicons.eot?#iefix&v=4.7.0') format('embedded-opentype'),
                    url('../fonts/ecicons.woff2?v=4.7.0') format('woff2'),
                    url('../fonts/ecicons.woff?v=4.7.0') format('woff'),
                    url('../fonts/ecicons.ttf?v=4.7.0') format('truetype'),
                    url('../fonts/ecicons.svg?v=4.7.0#fontawesomeregular') format('svg');
                font-weight: normal;
                font-style: normal;
            }
            #nprogress .spinner{display: block;position: fixed;z-index: 1031;top: 0;right: 0;width: calc(100vw);text-align: center;height: calc(100vh);background: rgba(255,255,255,0.5);margin: 0;padding: calc(40vh) 0 0 calc(50vw - 8rem);}
        </style>
        @routes
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script src="{{ asset('js/app.js') }}?v=10" defer></script>
        <script src="{{ asset('assets/js/vendor/jquery-3.5.1.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('img.svg_img[src$=".svg"]').each(function() {
                    var $img = $(this);
                    var imgURL = $img.attr('src');
                    var attributes = $img.prop("attributes");
    
                    $.get(imgURL, function(data) {
                            // Get the SVG tag, ignore the rest
                            var $svg = $(data).find('svg');
    
                            // Remove any invalid XML tags
                            $svg = $svg.removeAttr('xmlns:a');
    
                            // Loop through IMG attributes and apply on SVG
                            $.each(attributes, function() {
                                $svg.attr(this.name, this.value);
                            });
    
                            // Replace IMG with SVG
                            $img.replaceWith($svg);
                        }, 'xml');
                });
            });
        </script>
    </body>
</html>
