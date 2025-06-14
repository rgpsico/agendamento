<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>Doccure</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('template/assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/feather.css') }}">

    <!-- Mobile CSS-->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/intltelinput/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/intltelinput/css/demo.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/custom.css') }}">

</head>

<body class="login-body">

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <header class="header login-header-info">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                    <a href="index.html" class="navbar-brand logo">
                        <img src="{{ asset('https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png') }}"
                            class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="index.html" class="menu-logo">
                            <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png"
                                class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <ul class="main-nav">
                        <li class="has-submenu">
                            <a href="">Home <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="index-2.html">Home 2</a></li>
                                <li><a href="index-3.html">Home 3</a></li>
                                <li><a href="index-4.html">Home 4</a></li>
                                <li><a href="index-5.html">Home 5</a></li>
                                <li><a href="index-6.html">Home 6</a></li>
                                <li><a href="index-7.html">Home 7</a></li>
                                <li><a href="index-8.html">Home 8</a></li>
                                <li><a href="index-9.html">Home 9</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="javascript:void(0);">Doctors <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="doctor-dashboard.html">Doctor Dashboard</a></li>
                                <li><a href="appointments.html">Appointments</a></li>
                                <li><a href="schedule-timings.html">Schedule Timing</a></li>
                                <li><a href="my-patients.html">Patients List</a></li>
                                <li><a href="patient-profile.html">Patients Profile</a></li>
                                <li><a href="chat-doctor.html">Chat</a></li>
                                <li><a href="invoices.html">Invoices</a></li>
                                <li><a href="doctor-profile-settings.html">Profile Settings</a></li>
                                <li><a href="reviews.html">Reviews</a></li>
                                <li><a href="doctor-register.html">Doctor Register</a></li>
                                <li class="has-submenu">
                                    <a href="doctor-blog.html">Blog</a>
                                    <ul class="submenu">
                                        <li><a href="doctor-blog.html">Blog</a></li>
                                        <li><a href="blog-details.html">Blog view</a></li>
                                        <li><a href="doctor-add-blog.html">Add Blog</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="javascript:void(0);">Patients <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Doctors</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="map-grid.html">Map Grid</a></li>
                                        <li><a href="map-list.html">Map List</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Search Doctor</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="search.html">Search Doctor 1</a></li>
                                        <li><a href="search-2.html">Search Doctor 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="doctor-profile.html">Doctor Profile</a></li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Booking</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="booking.html">Booking 1</a></li>
                                        <li><a href="booking-2.html">Booking 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="checkout.html">Checkout</a></li>
                                <li><a href="booking-success.html">Booking Success</a></li>
                                <li><a href="patient-dashboard.html">Patient Dashboard</a></li>
                                <li><a href="favourites.html">Favourites</a></li>
                                <li><a href="chat.html">Chat</a></li>
                                <li><a href="profile-settings.html">Profile Settings</a></li>
                                <li><a href="change-password.html">Change Password</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="">Pharmacy <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="pharmacy-index.html">Pharmacy</a></li>
                                <li><a href="pharmacy-details.html">Pharmacy Details</a></li>
                                <li><a href="pharmacy-search.html">Pharmacy Search</a></li>
                                <li><a href="product-all.html">Product</a></li>
                                <li><a href="product-description.html">Product Description</a></li>
                                <li><a href="cart.html">Cart</a></li>
                                <li><a href="product-checkout.html">Product Checkout</a></li>
                                <li><a href="payment-success.html">Payment Success</a></li>
                                <li><a href="pharmacy-register.html">Pharmacy Register</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu active">
                            <a href="javascript:void(0);">Pages <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="about-us.html">About Us</a></li>
                                <li><a href="contact-us.html">Contact Us</a></li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Call</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="voice-call.html">Voice Call</a></li>
                                        <li><a href="video-call.html">Video Call</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Invoices</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="invoices.html">Invoices</a></li>
                                        <li><a href="invoice-view.html">Invoice View</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu active">
                                    <a href="javascript:void(0);">Authentication</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="login-email.html">Login Email</a></li>
                                        <li><a href="login-phone.html">Login Phone</a></li>
                                        <li><a href="doctor-signup.html">Doctor Signup</a></li>
                                        <li class="active"><a href="patient-signup.html">Patient Signup</a></li>
                                        <li><a href="forgot-password.html">Forgot Password 1</a></li>
                                        <li><a href="forgot-password2.html">Forgot Password 2</a></li>
                                        <li><a href="login-email-otp.html">Email OTP</a></li>
                                        <li><a href="login-phone-otp.html">Phone OTP</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);">Error Pages</a>
                                    <ul class="submenu inner-submenu">
                                        <li><a href="error-404.html">404 Error</a></li>
                                        <li><a href="error-500.html">500 Error</a></li>
                                    </ul>
                                </li>
                                <li><a href="blank-page.html">Starter Page</a></li>
                                <li><a href="pricing.html">Pricing Plan</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                                <li><a href="maintenance.html">Maintenance</a></li>
                                <li><a href="coming-soon.html">Coming Soon</a></li>
                                <li><a href="terms-condition.html">Terms & Condition</a></li>
                                <li><a href="privacy-policy.html">Privacy Policy</a></li>
                                <li><a href="components.html">Components</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Blog <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="blog-list.html">Blog List</a></li>
                                <li><a href="blog-grid.html">Blog Grid</a></li>
                                <li><a href="blog-details.html">Blog Details</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Admin <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="admin/index.html" target="_blank">Admin</a></li>
                                <li><a href="pharmacy/index.html" target="_blank">Pharmacy Admin</a></li>
                            </ul>
                        </li>
                        <li class="flag-dropdown-hide">
                            <div class="flag-dropdown">
                                <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-expanded="false">
                                    <img src="{{ asset('assets/img/flags/flag-01.png') }}" alt=""
                                        height="20" class="flag-img"> <span>English</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <img src="{{ asset('assets/img/flags/flag-01.png') }}" alt=""
                                            height="16"> English
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <img src="{{ asset('assets/img/flags/flag-02.png') }}" alt=""
                                            height="16"> French
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <img src="{{ asset('assets/img/flags/flag-03.png') }}" alt=""
                                            height="16"> Spanish
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <img src="{{ asset('assets/img/flags/flag-05.png') }}" alt=""
                                            height="16"> German
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <ul class="nav header-navbar-rht">
                    <li class="nav-item dropdown">
                        <div class="flag-dropdown">
                            <a class="dropdown-toggle nav-link" data-bs-toggle="dropdown" href="#"
                                role="button" aria-expanded="false">
                                <img src="{{ asset('template/assets/img/flags/flag-01.png') }}" alt=""
                                    height="20" class="flag-img"> <span>English</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ asset('template/assets/img/flags/flag-01.png') }}" alt=""
                                        height="16"> English
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ asset('template/assets/img/flags/flag-02.png') }}" alt=""
                                        height="16"> French
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ asset('template/assets/img/flags/flag-03.png') }}" alt=""
                                        height="16"> Spanish
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <img src="{{ asset('template/assets/img/flags/flag-05.png') }}" alt=""
                                        height="16"> German
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- /Header -->

        <!-- Page Content -->
        <div class="login-content-info">
            <div class="container">

                <!-- Patient Signup -->
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="account-content">
                            <div class="login-shapes">
                                <div class="shape-img-left">
                                    <img src="assets/img/shape-01.png" alt="">
                                </div>
                                <div class="shape-img-right">
                                    <img src="assets/img/shape-02.png" alt="">
                                </div>
                            </div>
                            <div class="account-info">
                                <div class="login-back">
                                    <a href="signup.html"><i class="fa-solid fa-arrow-left-long"></i> Back</a>
                                </div>
                                <div class="login-title">
                                    <h3>Patient Signup</h3>
                                    <p class="mb-0">Welcome back! Please enter your details.</p>
                                </div>
                                <form action="signup-success.html">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input
                                            class="form-control form-control-lg group_formcontrol form-control-phone"
                                            id="phone" name="phone" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control"
                                            placeholder="Enter Your First Name">
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control"
                                            placeholder="Enter Your Last Name">
                                    </div>
                                    <div class="form-group form-check-box terms-check-box">
                                        <div class="form-group-flex">
                                            <label class="custom_check">
                                                I have read and agree to Doccure’s <a href="javascript:void(0);">Terms
                                                    of Service</a> and <a href="javascript:void(0);">Privacy
                                                    Policy.</a>
                                                <input type="checkbox" name="Terms">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-block" type="submit">Register Now</button>
                                    </div>
                                    <div class="form-group back-btn-light">
                                        <button class="btn btn-light btn-block" type="submit">Create an
                                            Account</button>
                                    </div>
                                    <div class="account-signup">
                                        <p>Already a Member? <a href="login-email.html">Sign in</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Patient Signup -->

            </div>
        </div>
        <!-- /Page Content -->

        <!-- Cursor -->
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>
        <!-- /Cursor -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('template/assets/js/jquery-3.6.4.min.js') }}"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('template/assets/js/feather.min.js') }}"></script>

    <!-- Mobile Input -->
    <script src="{{ asset('template/assets/plugins/intltelinput/js/intlTelInput.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('template/assets/js/script.js') }}"></script>

</body>

</html>
