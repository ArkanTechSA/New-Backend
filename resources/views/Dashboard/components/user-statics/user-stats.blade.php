<div class="row g-6 mb-6">
    @foreach ($stats as $key => $value)
        @php
            $titles = [
                'total' => ['Total Users', 'عدد الحسابات الإجمالي', 'bg-label-primary', 'tabler-users'],
                'completed_profiles' => ['Completed Profiles', 'الملفات المكتملة', 'bg-label-success', 'tabler-user-check'],
                'incomplete_profiles' => ['Incomplete Profiles', 'الملفات غير المكتملة', 'bg-label-warning', 'tabler-user-exclamation'],
                'active' => ['Active Users', 'الحسابات المفعّلة', 'bg-label-info', 'tabler-user-check'],
                'pending' => ['Pending Users', 'قيد الانتظار', 'bg-label-secondary', 'tabler-user-search'],
                'banned' => ['Banned Users', 'الحسابات المحظورة', 'bg-label-danger', 'tabler-user-off'],
            ];
            $title = $titles[$key][0];
            $subtitle = $titles[$key][1];
            $bgClass = $titles[$key][2];
            $icon = $titles[$key][3];
        @endphp
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            {{-- <span class="text-heading">{{ $title }}</span> --}}
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{ $value }}</h4>
                            </div>
                            <small class="mb-0">{{ $subtitle }}</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded {{ $bgClass }}">
                                <i class="icon-base ti {{ $icon }} icon-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
