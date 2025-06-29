<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="/" class="mt-10 app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">

                    <img src="{{ admin_asset('layout/img/logo.svg') }}" alt="Logo" height="60" class="mt-2">



                </span>
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="py-1 mt-5 menu-inner">
        <!-- Page -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-chart-bar"></i>
                <div data-i18n="Page 1">@lang('dashboard.analytics')</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.providers') ? 'active' : '' }}">
            <a href="{{ route('admin.providers') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user-star"></i>
                <div data-i18n="Service Providers">@lang('dashboard.service_providers')</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.requesters') ? 'active' : '' }}">
            <a href="{{ route('admin.requesters') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-user"></i>
                <div data-i18n="Service Requesters">@lang('dashboard.service_requesters')</div>
            </a>
        </li>
<hr></hr>
<li class="menu-item {{ request()->routeIs('admin.newsletters') ? 'active' : '' }}">
    <a href="{{ route('admin.newsletters') }}" class="menu-link">
        <i class="menu-icon icon-base ti tabler-mail"></i>
        <div data-i18n="Newsletter">@lang('dashboard.newsletter')</div>
    </a>
</li>

    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="p-2 layout-menu-toggle menu-link text-large text-bg-secondary rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>
<!-- / Menu -->
