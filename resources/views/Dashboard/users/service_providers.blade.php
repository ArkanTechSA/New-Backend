<x-layout.default :title="__('dashboard.service_providers')">

    @push('Dashboard-styles')
        <style></style>
        <link rel="stylesheet" href="{{ admin_asset('layout/css/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ admin_asset('layout/css/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ admin_asset('layout/css/buttons.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ admin_asset('layout/css/select2.css') }}" />
        <link rel="stylesheet" href="{{ admin_asset('layout/css/form-validation.css') }}" />
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <x-userStats :stats="$statsForServiceProviders" />

            <div class="card">
                <div class="card-header border-bottom">


                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@lang('dashboard.service_providers')</h5>

                        <!-- زر جديد فوق الجدول -->
                        <button type="button" class="btn btn-primary" id="addProviderBtn">
                            @lang('dashboard.add_service_provider')
                        </button>
                    </div>

                    <div class="card-datatable">
                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="@lang('dashboard.search_placeholder')" />
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="">@lang('dashboard.all_statuses')</option>
                                    <option value="1">@lang('dashboard.active')</option>
                                    <option value="2">@lang('dashboard.banned')</option>
                                    <option value="0">@lang('dashboard.pending')</option>
                                </select>
                            </div>
                            <div class="col-md-3">


                                <select id="countryFilter" class="form-select">
                                    <option value="">@lang('dashboard.all_countries')</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-2">
                                <select id="rowsPerPage" class="form-select">
                                    <option value="10">10 @lang('dashboard.rows')</option>
                                    <option value="20">20 @lang('dashboard.rows')</option>
                                    <option value="50">50 @lang('dashboard.rows')</option>
                                </select>
                            </div>

                        </div>

                        <table class="table table-bordered datatables-users">
                            <thead class="border-top">
                                <tr>
                                    <th>@lang('dashboard.number')</th>
                                    <th>@lang('dashboard.first_name')</th>
                                    <th>@lang('dashboard.email')</th>
                                    <th>@lang('dashboard.mobile')</th>
                                    <th>@lang('dashboard.status')</th>
                                    <th>@lang('dashboard.created_at')</th>
                                    <th>@lang('dashboard.is_completed')</th>
                                    <th>@lang('dashboard.actions')</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                @include('Dashboard.users._users_table_rows', ['users' => $users])
                            </tbody>
                            <div class="mt-3">
                                <div id="pagination-links">
                                    {{ $users->links('pagination::bootstrap-5') }}

                                </div>
                            </div>

                        </table>
                    </div>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser"
                        aria-labelledby="offcanvasAddUserLabel">
                        <div class="offcanvas-header border-bottom">
                            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">@lang('dashboard.add_user')</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        @push('Dashboard-scripts')
            <script src="{{ admin_asset('layout/js/datatables.js') }}"></script>
            <script src="{{ admin_asset('layout/js/form-validation.js') }}"></script>
            <script src="{{ admin_asset('layout/js/select2.js') }}"></script>

            <script>
                function fetchUsers() {
                    const search = document.getElementById('searchInput').value;
                    const status = document.getElementById('statusFilter').value;
                    const country = document.getElementById('countryFilter').value;
                    const perPage = document.getElementById('rowsPerPage').value;

                    const params = new URLSearchParams({
                        search: search,
                        status: status,
                        country: country,
                        per_page: perPage
                    });

                    fetch(`{{ route('admin.providers.search') }}?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('userTableBody').innerHTML = data.html;
                            document.getElementById('userPagination').innerHTML = data.pagination;
                        })
                        .catch(error => console.error('خطأ في تحميل البيانات:', error));
                }

                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById('searchInput').addEventListener('keyup', fetchUsers);
                    document.getElementById('statusFilter').addEventListener('change', fetchUsers);
                    document.getElementById('countryFilter').addEventListener('change', fetchUsers);
                    document.getElementById('rowsPerPage').addEventListener('change', fetchUsers);
                });
            </script>
            <script>
                document.getElementById("addProviderBtn").addEventListener("click", function() {
                    window.location.href = "{{ route('providers.create') }}";
                });
            </script>
        @endpush
</x-layout.default>
