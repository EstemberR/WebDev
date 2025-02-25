<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    @yield('title')
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Sidebar background */
    .sidenav {
        box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2) !important;
        z-index: 1000 !important;
        position: fixed !important;
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
    }
    .navbar-main {
      
        box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2) !important;
    }
    .logout-btn {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin: 0 1rem;
    }

    .logout-btn:hover {
        background: linear-gradient(310deg, #ea580c, #facc15);
    }

    .logout-btn:hover .icon {
        background: transparent !important;
    }

    .logout-btn:hover .color-background {
        fill: white !important;
    }

    .logout-btn:hover .nav-link-text {
        color: white !important;
        font-weight: 600;
    }

    .logout-btn .icon {
        transition: all 0.3s ease;
    }

    .logout-btn .nav-link-text {
        transition: all 0.3s ease;
        color: #344767;
    }

    .logout-btn .color-background {
        fill: #344767;
        transition: all 0.3s ease;
    }

    .logout-btn .color-background.opacity-6 {
        fill-opacity: 0.6;
    }

    /* New styles for all sidebar items */
    .nav-item .nav-link {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin: 0 1rem;
        color: var(--light) !important;
    }

    .nav-item .nav-link:not(.active):hover {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .nav-item .nav-link:hover .icon {
        background: transparent !important;
    }

    .nav-item .nav-link:hover .nav-link-text {
        color: white !important;
        font-weight: 600;
    }

    .nav-item .nav-link:hover .color-background,
    .nav-item .nav-link:hover .color-background.opacity-6 {
        fill: white !important;
    }

    .nav-item .nav-link .icon {
        transition: all 0.3s ease;
    }

    .nav-item .nav-link .nav-link-text {
        transition: all 0.3s ease;
        color: #344767;
    }

    .nav-item .nav-link .color-background {
        fill: #344767;
        transition: all 0.3s ease;
    }

    .nav-item .nav-link .color-background.opacity-6 {
        fill-opacity: 0.6;
        transition: all 0.3s ease;
    }

    .user-name-display {
        color: #344767;
        padding: 0;
        cursor: default;
        pointer-events: none;
    }

    .user-name-display i {
        margin-right: 8px;
    }

    .sidenav .navbar-brand {
        color: var(--light);
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0 text-center" href="{{ route('dashboard') }}">
          <div class="d-flex flex-column align-items-center">
              <span class="font-weight-bold" style="font-size: 1.2rem; color: #ffffff;">
                  Student Information
              </span>
              <span class="font-weight-bold" style="font-size: 1.2rem; color: #ffffff;">
                  System
              </span>
          </div>
      </a>
  </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(Auth::user()->user_type === 'student')
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('student.dashboard') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('student.dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('student.studentSubjects') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('student.studentSubjects') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-book text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">My Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('student.studentGrades') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('student.studentGrades') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-dark text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-bar text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">My Grades</span>
                    </a>
                </li>
            @endif
            
            @if(Auth::user()->user_type === 'instructor')
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dashboard') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('enrollment.index') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('enrollment.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-list text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Enrollment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('students.index') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('students.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('subjects.index') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('subjects.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-book text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('grades.index') ? 'active bg-gradient-white text-dark' : 'text-dark' }}" 
                       href="{{ route('grades.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-list-alt text-dark opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Grades</span>
                    </a>
                </li>
            @endif
            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a class="nav-link d-flex align-items-center logout-btn" href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <svg width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>logout</title>
                                <g stroke="none" stroke-width="2" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(304.000000, 151.000000)">
                                                <path class="color-background opacity-6" d="M15.0002,6.99999997 C15.0002,4.23857625 17.2388,2 20.0002,2 L32.0002,2 C34.7616,2 37.0002,4.23857625 37.0002,6.99999997 L37.0002,32.9999999 C37.0002,35.7614237 34.7616,38 32.0002,38 L20.0002,38 C17.2388,38 15.0002,35.7614237 15.0002,32.9999999 L15.0002,26.9999999 L18.0002,26.9999999 L18.0002,32.9999999 L34.0002,32.9999999 L34.0002,6.99999997 L18.0002,6.99999997 L18.0002,12.9999999 L15.0002,12.9999999 L15.0002,6.99999997 Z"></path>
                                                <path class="color-background" d="M15.7071068,20.7071067 C15.3165825,21.097631 14.6834175,21.097631 14.2928932,20.7071067 L4.29289322,10.7071067 C3.90236893,10.3165825 3.90236893,9.68341751 4.29289322,9.29289322 L14.2928932,-0.707106781 C14.6834175,-1.09763107 15.3165825,-1.09763107 15.7071068,-0.707106781 C16.0976311,-0.316582489 16.0976311,0.316582489 15.7071068,0.707106781 L7.41421356,9 L24,9 C24.5522847,9 25,9.44771525 25,10 L25,20 C25,20.5522847 24.5522847,21 24,21 L7.41421356,21 L15.7071068,29.2928932 C16.0976311,29.6834175 16.0976311,30.3165825 15.7071068,30.7071068 C15.3165825,31.0976311 14.6834175,31.0976311 14.2928932,30.7071068 L4.29289322,20.7071067 C3.90236893,20.3165825 3.90236893,19.6834175 4.29289322,19.2928932 L14.2928932,9.29289322 C14.6834175,8.90236893 15.3165825,8.90236893 15.7071068,9.29289322 C16.0976311,9.68341751 16.0976311,10.3165825 15.7071068,10.7071067 L7.41421356,19 L22,19 L22,11 L7.41421356,11 L15.7071068,19.2928932 C16.0976311,19.6834175 16.0976311,20.3165825 15.7071068,20.7071067 Z" transform="translate(14.500000, 15.000000) scale(-1, 1) translate(-14.500000, -15.000000)"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Sign Out</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('Pages')</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">@yield('Pages')</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
          <ul class="navbar-nav justify-content-end">
            <!-- Profile Dropdown -->
            <li class="nav-item pe-2 d-flex align-items-center">
              <span class="user-name-display">
                  <i class="fa fa-user"></i>
                  {{Auth::user()->name}}
              </span>
          </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
   @yield('content')
  </main>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Stack scripts -->
  @stack('scripts')
  <style>
    :root {
        --primary-orange: #f97316;    /* Bright orange */
        --primary-yellow: #facc15;    /* Bright yellow */
        --dark-orange: #ea580c;       /* Deep orange */
        --medium-orange: #fb923c;     /* Medium orange */
        --light-orange: #fdba74;      /* Light orange */
        --dark-yellow: #eab308;       /* Deep yellow */
        --light-yellow: #fde047;      /* Light yellow */
        --light: #ffffff;
        --dark: #1a1a1a;
        --gray-light: #f3f4f6;
    }

    /* Sidebar Styling */
    .sidenav {
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
        border-right: 1px solid rgba(234, 88, 12, 0.1);
    }

    .sidenav .navbar-brand {
        color: var(--light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .sidenav .nav-link {
        color: var(--light) !important;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 5px 10px;
    }

    .sidenav .nav-link:hover {
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
        color: var(--dark) !important;
    }

    .sidenav .nav-link.active {
        background: var(--light) !important;
        color: var(--dark) !important;
        box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);
    }

    /* Icon Styling in Sidebar */
    .sidenav .icon-shape {
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 8px;
    }

    .sidenav .nav-link:hover .icon-shape,
    .sidenav .nav-link.active .icon-shape {
        background: rgba(255, 255, 255, 0.2) !important;
    }

    /* Sidebar Footer or Bottom Section */
    .sidenav .sidenav-footer {
        background: var(--dark-orange);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Main Content Area */
    .main-content {
        background-color: var(--gray-light);
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card-header {
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
        color: var(--light);
        border-radius: 12px 12px 0 0 !important;
    }

    /* Stats Cards */
    .stats-card {
        background: linear-gradient(310deg, var(--primary-orange), var(--light-yellow));
        color: var(--light);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(310deg, var(--primary-yellow), var(--dark-yellow));
    }

    /* Tables */
    .table thead th {
        background: var(--dark-orange);
        color: var(--light);
        border: none;
        padding: 12px 16px;
    }

    .table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(234, 88, 12, 0.1);
    }

    .table tbody tr:hover {
        background: rgba(234, 88, 12, 0.05);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(310deg, var(--primary-orange), var(--primary-yellow));
        border: none;
        box-shadow: 0 4px 6px rgba(234, 88, 12, 0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(234, 88, 12, 0.3);
    }

    /* Status Badges */
    .badge-success {
        background: linear-gradient(310deg, #059669, #10b981);
    }

    .badge-secondary {
        background: linear-gradient(310deg, #4b5563, #6b7280);
    }
  </style>
</body>

</html>