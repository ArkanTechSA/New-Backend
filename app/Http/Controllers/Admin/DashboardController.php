<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');

        if (in_array($locale, ['en', 'ar'])) {
            session(['admin_locale' => $locale]);
        }

        return redirect()->back();
    }

    public function index()
    {
        return view('Dashboard.Dashboard.index');
    }

    public function serviceProviders(Request $request)
    {
        $role = 1;
        $perPage = $request->get('per_page', 10);

        $users = User::where('role', $role)->paginate($perPage);

        // $users = User::where('role', $role)->paginate($perPage);

        $countryIds = User::where('role', $role)
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->pluck('country');

        $countries = Country::whereIn('id', $countryIds)->get();

        return view('Dashboard.users.service_providers', compact('users', 'countries'));
    }

    public function searchProviders(Request $request)
    {
        $role = 1;

        $search = $request->input('search');
        $status = $request->input('status');
        $country = $request->input('country');

        $perPage = $request->input('per_page', 10);
        // dd($perPage);
        $query = User::where('role', $role);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        if ($country !== null && $country !== '') {
            $query->where('country', $country);
        }

        $users = $query->paginate($perPage);

        return response()->json([
            'html' => view('Dashboard.users._users_table_rows', compact('users'))->render(),
            'pagination' => (string) $users->links(),
        ]);
    }

    public function create()
    {

        $nationalities = \App\Models\Nationality::all();

        return view('Dashboard.users.create_user', compact('nationalities'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'second_name' => 'required|string|max:255',
                'third_name' => 'required|string|max:255',
                'fourth_name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'country' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'lat' => 'nullable|numeric',
                'long' => 'nullable|numeric',
                'is_active' => ['required', Rule::in([0, 1, 2])],
                'role' => ['required', Rule::in([
                    User::ROLE_LAWYER,
                    User::ROLE_CLIENT,
                    User::ROLE_ADMIN,
                    User::ROLE_SUPERVISOR,
                ])],
                'is_old' => ['required', Rule::in([0, 1])],
                'referred_by' => 'nullable|string|max:255',
                'nationality_id' => 'nullable|integer|exists:nationalities,id',

            ]);

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('users_photos', 'public');
                $validatedData['photo'] = $photoPath;
            }

            $validatedData['password'] = Hash::make($validatedData['password']);

            $fullNumber = $request->input('dummy_mobile_number');
            $countryCode = $request->input('mobile_country_code');

            $cleanNumber = preg_replace('/^\+?'.preg_quote($countryCode, '/').'/', '', $fullNumber);

            $validatedData['mobile_number'] = $cleanNumber;
            $validatedData['mobile_country_code'] = $countryCode;

            // dd($validatedData);

            $user = User::create($validatedData);

            if (Str::contains(url()->previous(), 'providers')) {
                return redirect()->route('admin.providers')->with('success', __('dashboard.user_created_successfully'));
            } else {
                return redirect()->route('admin.requesters')->with('success', __('dashboard.user_created_successfully'));
            }
        } catch (\Exception $e) {
            // dd([
            //     'message' => $e->getMessage(),
            //     // 'v' =>  $validatedData,

            // ]);
        }
    }

    public function serviceRequesters(Request $request)
    {
        $role = 2;
        $perPage = $request->get('per_page', 10);
        $users = User::where('role', $role)->paginate($perPage);

        $countryIds = User::where('role', $role)
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->pluck('country');

        $countries = Country::whereIn('id', $countryIds)->get();

        return view('Dashboard.users.service_requesters', compact('users', 'countries'));
    }

    public function searchRequesters(Request $request)
    {
        $role = 2;
        // dd($role);

        $search = $request->input('search');
        $status = $request->input('status');
        $country = $request->input('country');
        $perPage = $request->input('per_page', 10);
        $query = User::where('role', $role);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%");
            });
        }
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        if ($country !== null && $country !== '') {
            $query->where('country', $country);
        }

        $users = $query->paginate($perPage);

        return response()->json([
            'html' => view('Dashboard.users._users_table_rows', compact('users'))->render(),
            'pagination' => (string) $users->links(),
        ]);
    }

    public function edit(User $user)
    {

        $nationalities = \App\Models\Nationality::all();

        return view('Dashboard.users.edit', compact('user', 'nationalities'));
    }

    public function update(Request $request, User $user)
    {
        try {
            // dd('here');
            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'latest_name' => 'nullable|string|max:255',
                'second_name' => 'nullable|string|max:255',
                'third_name' => 'nullable|string|max:255',
                'fourth_name' => 'nullable|string|max:255',
                'full_name' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'mobile_number' => 'nullable|string|max:20|unique:users,mobile_number,'.$user->id,
                'mobile_country_code' => 'nullable|string|max:10',
                'lat' => 'nullable|numeric',
                'long' => 'nullable|numeric',
                'gender' => 'nullable|in:Male,Female',
                'is_active' => 'required',
                'role' => 'required|integer',
                'photo' => 'nullable|image|mimes:jpeg,png|max:800',
                'referral_code' => 'nullable|string|max:20|unique:users,referral_code,'.$user->id,
                'nationality_id' => 'nullable|integer|exists:nationalities,id',
            ]);
            // dd($validated, $request->all());

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('assets/Backend/layout/img/users'), $filename);
                $validated['photo'] = $filename;
            }

            $submittedReferral = $request->input('referral_code');

            // ❷ إذا كان فارغًا، أنشئ كودًا جديدًا وتأكد من فريدونيّته
            if (empty($submittedReferral)) {
                do {
                    $generatedCode = Str::upper(Str::random(8));   // مثال: 8 حروف وأرقام
                } while (User::where('referral_code', $generatedCode)->exists());

                $referralCode = $generatedCode;
            } else {
                $referralCode = $submittedReferral;
            }

            $fullNumber = $request->input('dummy_mobile_number');
            $countryCode = $request->input('mobile_country_code');

            $cleanNumber = preg_replace('/^\+?'.preg_quote($countryCode, '/').'/', '', $fullNumber);

            $validatedData['mobile_number'] = $cleanNumber;
            $validatedData['mobile_country_code'] = $countryCode;

            $user->first_name = $validated['first_name'] ?? $user->first_name;
            // $user->latest_name = $validated['latest_name'] ?? $user->latest_name;
            $user->second_name = $validated['second_name'] ?? $user->second_name;
            $user->third_name = $validated['third_name'] ?? $user->third_name;
            $user->fourth_name = $validated['fourth_name'] ?? $user->fourth_name;
            // $user->full_name = $validated['full_name'] ?? $user->full_name;
            $user->country = $validated['country'] ?? $user->country;
            $user->city = $validated['city'] ?? $user->city;
            $user->region = $validated['region'] ?? $user->region;
            $user->address = $validated['address'] ?? $user->address;
            $user->email = $validated['email'] ?? $user->email;
            $user->mobile_number = $validatedData['mobile_number'] ?? $user->mobile_number;
            $user->mobile_country_code = $countryCode ?? $user->mobile_country_code;
            $user->lat = $validated['lat'] ?? $user->lat;
            $user->long = $validated['long'] ?? $user->long;
            $user->gender = $validated['gender'] ?? $user->gender;
            $user->nationality_id = $validated['nationality_id'] ?? $user->nationality_id;

            $user->is_active = $validated['is_active'] ?? $user->is_active;

            $user->role = $validated['role'] ?? $user->role;
            $user->photo = $validated['photo'] ?? $user->photo;
            $user->referral_code = $referralCode;
            $user->save();
            //   dd('Validation Passed', $validated, $request->all());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd('Validation Failed', $e->errors(), $request->all());
        }

        return back()->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function getCountries()
    {
        $countries = Country::where('status', 1)->select('id', 'name')->get();

        return response()->json($countries);
    }

    public function getRegions($countryId)
    {
        $regions = Region::where('country_id', $countryId)->get();

        return response()->json($regions);
    }

    public function getCities($regionId)
    {
        $cities = City::where('region_id', $regionId)->get();

        return response()->json($cities);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function newsletterIndex()
    {
        $providers_requesters = getUsersByRoles(); // دي المفروض ترجع مصفوفة فيها المستخدمين
        $providers = $providers_requesters['providers'];
        $requesters = $providers_requesters['requesters'];

        return view('Dashboard.newsletter.index', compact('providers', 'requesters'));
    }

    public function newsletterPost(Request $request)
    {
        // Validate input
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $userIds = $request->input('users');

        // Fetch users
        $users = User::whereIn('id', $userIds)->get();

        $success = [];
        $failed = [];

        foreach ($users as $user) {
            try {
                $this->emailService->send(
                    $user->email,
                    $request->subject,
                    '',
                    'view',
                    'emails.newsletter',
                    [
                        'name' => $user->first_name.' '.$user->second_name,
                        'email' => $user->email,
                        'accountType' => $request->account_type === 'lawyer' ? 'محامي' : 'عميل',
                        'message' => $request->message,
                    ],
                    'newsletter',
                    1
                );

                $success[] = $user->email;
            } catch (\Throwable $e) {
                // سجل الايميل اللي فشل
                $failed[] = [
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'status' => 'done',
            'sent' => $success,
            'failed' => $failed,
        ]);
    }
}