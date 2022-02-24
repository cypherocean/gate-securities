
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    @include('layouts.meta')

    <title>@yield('title') | {{ _settings('SITE_TITLE') }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ _fevicon() }}">
    
    @include('layouts.styles')
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        @include('layouts.header')
        
        @include('layouts.sidebar')

        <div class="page-wrapper">
            @yield('content')

            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.scripts')
</body>
</html>