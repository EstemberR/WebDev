<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
        <style>
            .welcome-section {
                background: #040404;
                position: relative;
            }
            .welcome-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(45deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%);
            }
            .btn-custom {
                background: linear-gradient(310deg, #ea580c, #facc15);
                border: none;
                transition: all 0.3s ease;
            }
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);
                background: linear-gradient(310deg, #c2410c, #eab308);
            }
            .text-gradient {
                background: linear-gradient(310deg, #ea580c, #facc15);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .nav-link {
                transition: all 0.3s ease;
            }
            .nav-link:hover {
                color: #ea580c !important;
            }
            .navbar-brand {
                font-size: 2rem !important;
                padding: 0.5rem 1rem;
                transition: all 0.3s ease;
            }
            .navbar-brand:hover {
                transform: translateY(-2px);
            }
            .nav-link {
                font-size: 1.2rem !important;
                padding: 0.5rem 1.5rem !important;
                margin: 0 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }
            .nav-link:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3">
            <div class="container">
                <a class="navbar-brand text-white fs-3" href="/">
                    <span class="font-weight-bold">Student Information System</span>
                </a>
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a href="{{ route('dashboard') }}" class="nav-link text-white fs-5 px-3">Dashboard</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link text-white fs-5 px-3">Log in</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="nav-link text-white fs-5 px-3">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Section -->
        <header class="welcome-section min-vh-100">
            <div class="container position-relative z-index-2">
                <div class="row min-vh-100 align-items-center">
                    <div class="col-lg-8 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Welcome to</h1>
                        <h1 class="text-gradient display-3 font-weight-bolder mb-4">Student Information System</h1>
                        <p class="text-white-50 lead mb-5">
                            Manage student records, track academic progress, and streamline administrative tasks efficiently.
                        </p>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-custom btn-lg text-white mb-0 me-2">
                                <span class="ms-1">Get Started</span>
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>
    </body>
</html>
