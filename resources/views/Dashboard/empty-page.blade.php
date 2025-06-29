<x-layout.default :title="'Dashboard Home'">

    {{-- @push('Dashboard-styles')
        <style>
        </style>
    @endpush --}}


    <div>
        <h1>starter page</h1>
    </div>




            {{-- @ar('مرحبا بك في لوحة التحكم') --}}

        {{-- @en('Welcome to the Dashboard') --}}


    @if ($userData)
        <p>مرحبا، {{ $userData['first_name'] ?? 'User' }}</p>
    @endif


    {{-- @push('Dashboard-scripts')
        <script></script>
    @endpush --}}
</x-layout.default>
