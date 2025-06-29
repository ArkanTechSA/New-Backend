<x-layout.default :title="__('dashboard.dashboard_home')">

    {{-- @push('Dashboard-styles')
        <style>
        </style>
    @endpush --}}


    <h1>@lang('dashboard.dashboard')</h1>



                    <div class="col-12">
                                        <h5>@lang('dashboard.Providers_statistics')</h5>
                                        <hr>
                                        <x-userStats :stats="$statsForServiceProviders" />
                                    </div>                    
                                    <div class="col-12">
                                        <h5>@lang('dashboard.Requesters_statistics')</h5>
                                        <hr>
                                        <x-userStats :stats="$statsForServiceRequesters" />
                                    </div>




    {{-- @push('Dashboard-scripts')
        <script></script>
    @endpush --}}
</x-layout.default>


{{-- 
 'Providers_statistics' => 'احصائيات مقدمي الخدمة',
    'Requesters_statistics' --}}