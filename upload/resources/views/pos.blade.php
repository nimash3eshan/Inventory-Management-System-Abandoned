<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(!empty(setting('fevicon_path')))
    <link rel="icon" href="{{asset(\Storage::url(setting('fevicon_path')))}}"  />
    @else
    <link rel="icon" href="{{asset('images/fevicon.png')}}"  />
    @endif
    @include('includes.metatag')

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- Styles -->
    <!-- Bootstrap 4.3.1 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @include('includes.analytics')
</head>

<body>
    <div class="bg-electron-blue">
        <div class="container  bg-electron-blue">
            <nav class="navbar navbar-expand-lg navbar-light bg-electron-blue px-0">
                <nav class="navbar pl-0">
                    <a class="navbar-brand" href="#"><img src="/images/pos/fpos.png" class="nav-logo" height="40"
                            alt="">
                    </a>
                </nav>
                <div class="collapse navbar-collapse header-links" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto d-none d-lg-flex">
                        <li class="nav-item active p-2">
                            <a class="nav-link text-light" href="https://www.flexibleit.net/contact-us" target="_blank"> Contact <span
                                    class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
                <a href="{{\App\Utils\Fpos::DEMO_URL}}/login" target="_blank" class="btn btn-md px-4 btn-light"> Login </a>
            </nav>
            <div class="row py-5">
                <div class="col-6 col-md-6">
                    <div>
                        <h5> It's easy to manage your shop with </h5>
                        <h3>Flexible POS system</h3>
                        <a href="{{\App\Utils\Fpos::DEMO_URL}}/login" target="_blank" class="btn btn-lg px-4 mt-5 btn-light">Try it now</a>
                    </div>
                </div>
                <div class="col-6 col-md-6 ">
                    <div class="header-right">
                        <img src="/images/pos/post-software.svg" class="header-section-img" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row  justify-content-md-center">
            <div class="col-md-12 text-center mt-5">
                <h2>About <span> Flexible POS Software </span></h2>
            </div>
            <div class="col-5 col-md-5 p-5 ">
                <div class="about-para">
                    <p class=""> Flexible POS system with your business. Easily add morre cash registers users or even
                        store locations as needed. </p>
                    <p class=""> With robust hardware compatibility and freedom to use wih any PC, Mac, iPad,
                        You can customize design each register to suit its counter space and layout. </p>
                </div>
            </div>
            <div class="col-7 col-md-7 p-5">
                <img src="/images/pos/dashboard-mockup.png" class="about-section-img" alt="">
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class="row  justify-content-md-center">
            <div class="col-6 col-md-6 py-3">
                <a class="navbar-brand" href="#">
                    <img src="/images/pos/Mask-Group.png" class="feature-section-img" alt="">
                </a>
            </div>
            <div class="col-6 col-md-6">
                <h3>Features of <span class="feature-list">Flexible POS</span></h3>
                <div class="row mt-4">
                    <div class="col-md-6 ">
                        <ul class="feature-list">
                            <li> Manage Inventory </li>
                            <li> Manage Suppliers </li>
                            <li> Manage Sale </li>
                            <li> Manage Purchase </li>
                            <li> Manage Customre </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="feature-list">
                            <li> Manage Accounts </li>
                            <li> Manage Expenses </li>
                            <li> Manage Employee </li>
                            <li> Manage Reports </li>
                            <li> Fun with Settings </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5 bg-gradient py-5">
        <div class="container">
            <div class="row  justify-content-center">
                <div class="col-12 text-center">
                    <div class="p-5">
                        <h2 class="mb-3 text-center"> Do you want a total solution for your business? </h2>
                        <h5 class="text-center"><b>eManager</b> A best small business management software<br /> <a class="mt-4 btn-lg btn btn-primary" href="https://e-manager.org" target="_blank">Try it now</a></h5>
                    </div>
                
                    <div>
                        <div class="video-container">
                            <div class="embed-container">
                                <iframe src="https://www.youtube.com/embed/OL8DOxV1SnM" frameBorder="0" allow="autoplay; fullscreen; picture-in-picture" allowFullScreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-gradient py-5">
        <div class="container">
            <div class="row  justify-content-md-center">
                <div class="col-6 col-md-6  mt-5">
                    <div class="p-5">
                        <h1> Flexible POS </h1>
                        <h5>Very easy to use & Compatibility with every device </h5>
                    </div>
                </div>
                <div class="col-6 col-md-6 p-3 gradient-section-bg-img p-1">
                    <div>
                        <img src="/images/pos/dashboard-mockup.png" class="gradient-section-img " alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row  justify-content-md-center">
            <div class="col-md-12 text-center mt-5">
                <h1> Getting started it is flexible to use</h1>
            </div>
            <div class="col-4 col-md-4 p-5 ">
                <div class="single-step">
                    <div class="text-center">
                        <img src="/images/pos/Icon-awesome-upload.png" alt="">
                        <h3 class="pt-2">Upload</h3>
                    </div>
                    <div class="text-center">
                        <p> Upload all existing products & customers data at once with our easy to use CSV import wizard .
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-4 p-5 ">
                <div class="single-step">
                    <div class="text-center">
                        <img src="/images/pos/customize.png" alt="">
                        <h3 class="pt-2">Install</h3>
                    </div>
                    <div class="text-center">
                        <p> Flexible POS lets you customize all that matters including currency, time-zone , tax rate
                            settings and even language. </p>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-4 p-5 ">
                <div class="single-step">
                    <div class="text-center">
                        <img src="/images/pos/Icon-awesome-plug.png" alt="">
                        <h3 class="pt-2">Customize & Use</h3>
                    </div>
                    <div class="text-center">
                        <p> Set up store counter using any PC , Mac, or iPad, . Flexible POS offers plug-n-play
                            compatibility with most rental hardware. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-electron-blue">
        <div class="row  justify-content-md-center">
            <div class="col-12 col-md-12 text-center p-5 trial-section">
                <h2> Want to go for a trial version ? </h2>
                <p> It's easy. no need of any credit card, no risk. </p>
                <a href="{{\App\Utils\Fpos::DEMO_URL}}/login" target="_blank" class="btn btn-md px-4 active login-btn" > Try Now </a>

            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row">
            <div class="col-10 col-md-8">
                <h6> &copy;2021 all rights reserved by <a href="https://www.flexibleit.net" target="_blank">Flexible IT</a> </h6>
            </div>
            <div class="col-2 col-md-4 text-right">
                <nav>
                    <a href="https://www.flexibleit.net/contact-us" target="_blank" class="p-md-2 p-1">Contact</a>
                </nav>
            </div>
        </div>
    </div>
</body>
</html>