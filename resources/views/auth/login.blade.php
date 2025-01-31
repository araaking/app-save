<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | Hando - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        <script src="{{ asset('assets/js/head.js') }}"></script>
    </head>

    <body>
        <!-- Begin page -->
        <div class="account-page">
            <div class="container-fluid p-0">
                <div class="row align-items-center g-0 px-3 py-3 vh-100">
                    <div class="col-xl-5 mx-auto d-flex align-items-center justify-content-center">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-0 p-0 p-lg-3">
                                            <div class="mb-4 p-0 text-lg-start text-center">
                                                <div class="auth-brand">
                                                    <a href="index.html" class="logo logo-light">
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('assets/images/logo-light-3.png') }}" alt="" height="24">
                                                        </span>
                                                    </a>
                                                    <a href="index.html" class="logo logo-dark">
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('assets/images/logo-dark-3.png') }}" alt="" height="24">
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="auth-title-section mb-4 text-lg-start text-center"> 
                                                <h3 class="text-dark fw-semibold mb-3">Masuk ke Sistem</h3>
                                                <p class="text-muted fs-14 mb-0">
                                                    Sistem Informasi Akademik MADRASAH DINIYAH TAKMILIYAH AWALIYAH RAUDLATUL MUTA'ALLIMIN CIBENCOY Cisaat Sukabumi
                                                </p>
                                            </div>

                                            <!-- Form Login -->
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf

                                                <!-- Email Address -->
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email address</label>
                                                    <input id="email" type="email" name="email" 
                                                           class="form-control" 
                                                           value="{{ old('email') }}" 
                                                           required autofocus autocomplete="username"
                                                           placeholder="Enter your email">
                                                    @error('email')
                                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Password -->
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input id="password" type="password" name="password" 
                                                           class="form-control" 
                                                           required autocomplete="current-password"
                                                           placeholder="Enter your password">
                                                    @error('password')
                                                        <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Remember Me & Forgot Password -->
                                                <div class="d-flex mb-3 justify-content-between align-items-center">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               class="form-check-input" 
                                                               id="remember_me" 
                                                               name="remember">
                                                        <label class="form-check-label" for="remember_me">
                                                            Remember me
                                                        </label>
                                                    </div>
                                                    @if (Route::has('password.request'))
                                                        <a class="text-muted fs-14" href="{{ route('password.request') }}">
                                                            Forgot password?
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="d-grid">
                                                    <button class="btn btn-primary fw-semibold" type="submit">
                                                        Log In
                                                    </button>
                                                </div>
                                            </form>

                                            <!-- Social Login Buttons -->
                                            <!-- <div class="mt-4 text-center">
                                                <p class="mb-2">Or sign in with</p>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center" href="#">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48" class="me-2">
                                                                <path fill="#ffc107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917"/>
                                                                <path fill="#ff3d00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691"/>
                                                                <path fill="#4caf50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44"/>
                                                                <path fill="#1976d2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917"/>
                                                            </svg>
                                                            <span>Google</span>
                                                        </a>
                                                    </div>

                                                    <div class="col-6">
                                                        <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center" href="#">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256" class="me-2">
                                                                <path fill="#1877f2" d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/>
                                                                <path fill="#fff" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z"/>
                                                            </svg>
                                                            <span>Facebook</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- End Social Login -->

                                            <div class="text-center text-muted mt-4">
                                                <p class="mb-0">
                                                    Don't have an account?
                                                    <a class="text-primary ms-2 fw-medium" href="auth-register.html">Sign up</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- END wrapper -->

        <!-- Vendor Scripts -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

        <!-- App Scripts -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
    </body>
</html>
