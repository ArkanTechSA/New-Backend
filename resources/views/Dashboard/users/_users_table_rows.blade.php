@foreach ($users as $index => $user)
    <tr>
        <td>      {{ method_exists($users, 'firstItem') ? $users->firstItem() + $index : $index + 1 }}</td>
        <td>{{ $user->first_name }}</td>
        <td>{{ $user->email }}</td>
        <td style="direction: ltr;text-align: end;">(+{{ $user->mobile_country_code }}) &nbsp; {{ $user->mobile_number }}</td>
        <td>
            @switch($user->is_active)
                @case(1)
                    <span class="badge bg-success">مفعل</span>
                    @break
                @case(2)
                    <span class="badge bg-danger">محظور</span>
                    @break
                @case(0)
                    <span class="badge bg-warning">انتظار</span>
                    @break
                @default
                    <span class="badge bg-secondary">غير معروف</span>
            @endswitch
        </td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td>
            @if (
                $user->email &&
                $user->gender &&
                $user->mobile_number &&
                $user->mobile_country_code &&
                $user->country &&
                $user->first_name
            )
                <span class="badge bg-success">نعم</span>
            @else
                <span class="badge bg-danger">لا</span>
            @endif
        </td>
        <td>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">تعديل</a>
            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block" onsubmit="return confirm('هل أنت متأكد؟')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">حذف</button>
            </form>
        </td>
    </tr>
@endforeach
