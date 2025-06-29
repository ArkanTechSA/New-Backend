{{-- $token = session('admin_jwt_token'); --}}


@php
    $token = session('admin_jwt_token');

    $parts = explode('.', $token);
    $payload = isset($parts[1]) ? json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true) : [];
    $payload = $payload['user'] ?? '';

    // dd(    $token,$payload);

@endphp
<!-- Navbar -->

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="px-0 nav-item nav-link me-xl-6" href="javascript:void(0)">
            <i class="icon-base ti tabler-menu-2 icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        <div class="navbar-nav align-items-center">





            @php
                use Illuminate\Support\Facades\File;

                $locales = collect(File::directories(resource_path('lang')))
                    ->filter(fn($path) => File::exists($path . '/dashboard.php'))
                    ->map(fn($path) => basename($path))
                    ->all();

                $currentLocale = app()->getLocale();
                $localeInfo = include resource_path("lang/$currentLocale/dashboard.php");
            @endphp


            <div class="nav-item dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" id="nav-locale" href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <span class="fi fi-{{ $localeInfo['flag'] }}"></span>
                    <span class="d-none ms-2" id="nav-locale-text">
                        {{ $localeInfo['name'] }}
                    </span>
                </a>


                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-locale-text">
                    @foreach ($locales as $locale)
                        @php
                            $info = include resource_path("lang/$locale/dashboard.php");
                        @endphp
                        <li>
                            <form method="POST" action="{{ route('admin.setLocale') }}">
                                @csrf
                                <input type="hidden" name="locale" value="{{ $locale }}">
                                <button type="submit"
                                    class="dropdown-item align-items-center {{ app()->getLocale() == $locale ? 'active' : '' }}">
                                    <span>
                                        <i class="fi fi-{{ $info['flag'] }} me-2"></i>
                                        {{ $info['name'] }}
                                    </span>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>

            </div>



            <div class="nav-item dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-sun icon-md theme-icon-active"></i>
                    <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-theme-text">
                    <li>
                        <button type="button" class="dropdown-item align-items-center active"
                            data-bs-theme-value="light" aria-pressed="false">
                            <span><i class="icon-base ti tabler-sun icon-md me-3" data-icon="sun"></i>Light</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
                            aria-pressed="true">
                            <span><i class="icon-base ti tabler-moon-stars icon-md me-3"
                                    data-icon="moon-stars"></i>Dark</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
                            aria-pressed="false">
                            <span><i class="icon-base ti tabler-device-desktop-analytics icon-md me-3"
                                    data-icon="device-desktop-analytics"></i>System</span>
                        </button>
                    </li>
                </ul>
            </div>


        </div>

        <ul class="flex-row navbar-nav align-items-center ms-md-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="p-0 nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online ">
                        <img src="{{ $userData['photo']  ? admin_asset('layout/img/users/'.$userData['photo']) : admin_asset('layout/img/avatar.png') }}" alt class=" object-fit-image rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $userData['photo']  ? admin_asset('layout/img/users/'.$userData['photo']) : admin_asset('layout/img/avatar.png') }}" alt
                                            class="h-auto w-px-40 rounded-circle object-fit-image" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $userData['first_name'] ?? 'User' }}</h6>
                                    <small class="text-body-secondary">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="my-1 dropdown-divider mx-n2"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                            <i class="icon-base ti tabler-user icon-md me-3"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.change-password.form') }}">
                            <i class="icon-base ti tabler-lock icon-md me-3"></i>
                            <span>Change Password</span>
                        </a>

                    </li>
     

                    <li>
                        <div class="my-1 dropdown-divider mx-n2"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                            @csrf
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-base ti tabler-power icon-md me-3"></i>
                                <span>Log Out</span>
                            </a>
                        </form>

                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

<!-- / Navbar -->
