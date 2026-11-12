@php($theme = db4scw_get_theme())
<!DOCTYPE html>
<html lang="en" class="theme-{{ $theme }}">
    <head>
        <title>{{ $title ?? '' }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">      
        <!-- Include Bootstrap CSS file -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

        <!-- Include jQuery library -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Include Bootstrap JavaScript library -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        
        <!-- Colors from .env, used by the stylesheets below -->
        <style>
            :root {
                --page-bg: {{ config('app.db4scw_page_background_color') }};
                --accent: {{ config('app.db4scw_accent_background_color') }};
                --table-bg: {{ config('app.db4scw_table_background_color') }};
                --text: {{ config('app.db4scw_body_text_color') }};
                --modal-header-bg: {{ config('app.db4scw_modal_header_background_color') }};
                --modal-body-bg: {{ config('app.db4scw_modal_body_background_color') }};
                --nav-link: {{ config('app.db4scw_navbar_link_text_color') }};
                --section-bg: {{ rtrim(config('app.db4scw_content_background_color'), '; ') }};
            }
        </style>
        <link href="{{ db4scw_asset('css/coordinatorr.css') }}" rel="stylesheet">
        @if($theme != 'default')
        <link href="{{ db4scw_asset('css/theme-' . $theme . '.css') }}" rel="stylesheet">
        @endif
        <!-- On demand CSS -->
        {{ $styles ?? '' }}
    </head>
    <body>
         <!-- Floating Navbar -->
        <nav class="navbar fixed-top navbar-dark custom-navbar">
            <a class="navbar-brand" href="/">Coordinatorr</a>

            <div class="navbar-element">
                @if(auth()->check())
                <a class="nav-link navbar-link {{ request()->is('admin') ? 'active' : '' }}" href="/admin">Adminpanel</a>
                <a class="nav-link navbar-link {{ request()->is('activations/open') ? 'active' : '' }}" href="/activations/open">Open Activations</a>
                <form method="post" action="/logout" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link navbar-link">Logout</button>
                </form>
                @endif
                <a class="nav-link navbar-link {{ request()->is('planned_activations*') ? 'active' : '' }}" href="/planned_activations">Planned Activations</a>
            </div>

            @if($theme == 'christmas')
            <div class="xmas-lights" aria-hidden="true">@for($i = 0; $i < 64; $i++)<span></span>@endfor</div>
            @endif
        </nav>

        @if(!db4scw_has_activator_access() and !request()->routeIs('access'))
        <div class="container mt-4">
            <div class="notice">
                Read-only mode. <a href="/access">Enter the event access code</a> to log or plan activations.
            </div>
        </div>
        @endif

        <!-- Alerts -->
        @if(session()->has('danger'))
        <div class="bottom-right-alert">
            <div class="alert alert-danger">
                {{ session('danger') }}
            </div>
        </div>
        @endif
        @if(session()->has('warning'))
        <div class="bottom-right-alert">
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        </div>
        @endif
        @if(session()->has('success'))
        <div class="bottom-right-alert">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session()->has('light'))
        <div class="bottom-right-alert">
            <div class="alert alert-light">
                {{ session('light') }}
            </div>
        </div>
        @endif 
        @if(session()->has('updateinfo'))
        <div class="top-left-alert">
            <div class="alert alert-danger">
                A new version <a href="{{ session('updateinfo')['htmlUrl'] }}">{{ session('updateinfo')['latestVersion'] }}</a> got released. Please update asap following the <a href="https://hamawardz.de/docs/coordinatorr/installation/#updating-coordinatorr-to-a-new-version">instructions</a>.
            </div>
        </div>
        @endif
        
        <!-- Main Content -->
        {{ $slot }}

        <!-- Floating Footer -->
        <div class="footer">
            <a class="footerelement" href="{{ config('app.db4scw_impressum_url') }}">Impressum</a>
            <a class="footerelement" href="{{ config('app.db4scw_data_protection_url') }}">Datenschutz / Data protection declaration</a>
        </div>

        <!-- On demand JS -->
        {{ $scripts ?? '' }}

        <!-- Alert-JS -->
        <script type="text/javascript">
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 5000);
        </script>

    </body>
</html>