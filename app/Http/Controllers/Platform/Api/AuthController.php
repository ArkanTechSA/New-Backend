<?php

namespace App\Http\Controllers\Platform\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Services\EmailService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendOtp(Request $request, SmsService $smsService)
    {
        $validator = Validator::make($request->all(), [
            'phone_code' => ['required', 'integer', 'in:20,966,1,44,...'],
            'phone' => ['required', 'string', 'min:6', 'max:20', 'unique:users,mobile_number'],
        ], [
            'phone_code.required' => 'يجب عليك ادخال رمز الهاتف',
            'phone_code.integer' => 'يجب عليك ادخال رمز الهاتف بشكل صحيح',
            'phone_code.in' => 'يجب عليك ادخال رمز الهاتف بشكل صحيح',
            'phone.required' => 'يجب عليك ادخال رقم الهاتف',
            'phone.string' => 'يجب عليك ادخال رقم الهاتف بشكل صحيح',
            'phone.unique' => 'رقم الهاتف موجود سابقاً',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $otp = rand(100000, 999999);

        $user = User::create([
            'mobile_number' => $request->phone,
            'mobile_country_code' => $request->phone_code,
            'otp' => $otp,
            'password' => Hash::make('ymtaznewaccount01'),
        ]);

        $message = "كود تفعيل الحساب : $otp";
        $fullPhone = $request->phone_code.$request->phone;
        $sent = $smsService->sendSms($fullPhone, $message);

        return response()->json([
            'status' => true,
            'message' => 'تم ارسال كود التأكيد على SMS , نرجو مراجعة هاتفك حتي يمكنك تأكيد رقمك',
            'data' => null,
            'code' => 200,
            'otp' => app()->environment('local') ? $otp : null,
        ], 200);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:6|max:20',
            'phone_code' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'invalid_data',
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::where('mobile_number', $request->phone)
            ->where('mobile_country_code', $request->phone_code)
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'رقم الهاتف غير مسجل لدينا',
                'data' => null,
            ], 404);
        }

        if ($user->otp === null && $user->is_active) {
            return response()->json([
                'status' => false,
                'code' => 409,
                'message' => 'رقم الهاتف مفعل بالفعل',
                'data' => null,
            ], 409);
        }

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);
        app(SmsService::class)->sendSms($user->mobile_number.$user->mobile_country_code, $otp);
        if (app()->environment('local')) {
            $debugOtp = $otp;
        } else {

            $debugOtp = null;
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'تم إعادة إرسال رمز التفعيل',
            'data' => null,
            'otp' => $debugOtp, // يظهر فقط في بيئة local
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:6|max:20',
            'phone_code' => 'required|string|max:10',
            'otp' => 'required|digits:6',
        ], [
            'mobile_number.required' => 'يجب عليك ادخال رقم الهاتف',
            'mobile_country_code.required' => 'يجب عليك ادخال رمز الدولة',
            'otp.required' => 'يجب عليك ادخال كود التحقق',
            'otp.digits' => 'كود التحقق يجب أن يكون مكون من 6 أرقام',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'هناك أخطاء في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        // بحث عن المستخدم بالرقم ورمز الدولة و OTP
        $user = User::where('mobile_number', $request->phone)
            ->where('mobile_country_code', $request->phone_code)
            ->where('otp', $request->otp)
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'كود التحقق غير صحيح أو رقم الهاتف غير مسجل',
                'data' => null,
                'code' => 401,
            ], 401);
        }

        // يمكن هنا تحديث حالة المستخدم لو لازم (مثلاً تفعيل الحساب)
        $user->is_active = true;
        $user->otp = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'تم التحقق من كود التفعيل بنجاح',
            'data' => null,
            'code' => 200,
        ]);
    }

    public function completeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'third_name' => 'nullable|string|max:100',
            'fourth_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:6|max:20',
            'phone_code' => 'required|string|max:10',
            'account_type' => 'required|in:client,lawyer',
            'otp' => 'nullable|digits:6',
            'gender' => 'nullable|in:male,female,Male,Female,other',
            'referred_by' => 'nullable|exists:users,id',
            'accepted_tos' => 'nullable|accepted',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.not_contains' => 'الاسم لا يجب أن يحتوي على الحرف ـ',
            'first_name.required' => 'الاسم الاول مطلوب',
            'first_name.not_contains' => 'الاسم الأول لا يجب أن يحتوي على الحرف ـ',
            'second_name.required' => 'الاسم الثاني مطلوب',
            'second_name.not_contains' => 'الاسم الثاني لا يجب أن يحتوي على الحرف ـ',
            'fourth_name.required' => 'الاسم الرابع مطلوب',
            'fourth_name.not_contains' => 'الاسم الرابع لا يجب أن يحتوي على الحرف ـ',
            'third_name.not_contains' => 'الاسم الثالث لا يجب أن يحتوي على الحرف ـ',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني يجب ان يكون بالشكل الصحيح',
            'email.unique' => ' البريد الإلكتروني موجود مسبقاً',

            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.numeric' => 'رقم الهاتف يجب ان يكون ارقام',
            'phone.unique' => 'رقم الهاتف موجود سابقاً',
            'phone_code.required' => 'مقدمة الدولة مطلوبة',
            'phone_code.numeric' => 'مقدمة الدولة يجب ان تكون ارقام',

            'account_type.required' => 'نوع الحساب مطلوب',
            'account_type.in' => ' نوع الحساب يجب ان يكون ضمن [lawyer,client]',

            'password.required' => 'كلمة المرور مطلوب',

            'gender.in' => 'يجب ان يكون الجنس Male او Female',
            'gender.required' => 'الجنس  مطلوب',
            'referred_by.valid_referral_code' => 'رمز المشاركة غير صحيح',

            'otp.required_if' => 'برجاء تفعيل رقم الهاتف',
            'accepted_tos' => 'يجب الموافقة على الشروط والأحكام',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'هناك أخطاء في البيانات المدخلة',
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::where('mobile_number', $request->phone)
            ->where('mobile_country_code', $request->phone_code)
            ->where('otp', null)
            ->where('is_active', 1)  // لازم يكون مفعل
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'رقم الهاتف غير مفعل ',
                'data' => null,
                'code' => 401,
            ], 401);
        }

        if (! empty($user->otp)) {
            return response()->json([
                'status' => false,
                'message' => 'يرجى تفعيل رقم الهاتف أولاً',
                'data' => null,
                'code' => 403,
            ], 403);
        }

        $accountTypeMap = [
            'lawyer' => 1,
            'client' => 2,
        ];
        $accountTypeValue = $accountTypeMap[$request->account_type] ?? null;

        $user->update([
            'full_name' => $request->name,
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'third_name' => $request->third_name,
            'fourth_name' => $request->fourth_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $accountTypeValue,
            'gender' => $request->gender,
            'referred_by' => $request->referred_by,
            'accepted_tos' => true,
        ]);

        $test = [
            'full_name' => $request->name,
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'third_name' => $request->third_name,
            'fourth_name' => $request->fourth_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $accountTypeValue,
            'gender' => $request->gender,
            'referred_by' => $request->referred_by,
            'accepted_tos' => true,
        ];

        $this->emailService->send(
            $user->email,
            'أهلاً بك في منصتنا القانونية',
            '',
            'view',
            'emails.register_welcome',
            [
                'name' => $user->first_name.' '.$user->second_name,
                'email' => $user->email,
                'accountType' => $request->account_type === 'lawyer' ? 'محامي' : 'عميل',
            ],
            'noreply',
            1
        );

        return response()->json([
            'status' => true,
            'message' => 'تم التسجيل بنجاح',
            'data' => null,
            'code' => 200,
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', // البريد أو الهاتف
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'invalid_data',
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('mobile_number', $request->email);
        })
            ->with(['countryRelation', 'regionRelation', 'cityRelation', 'nationality'])
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'البريد أو رقم الهاتف أو كلمة المرور غير صحيحة',
                'data' => null,
            ], 401);
        }

        if ($user->is_active != 1) {
            return response()->json([
                'status' => false,
                'code' => 403,
                'message' => 'الحساب غير مفعل',
                'data' => null,
            ], 403);
        }

        $token = JWTAuth::fromUser($user);

        $accountData = [
            'id' => $user->id,
            'name' => $user->full_name ?? $user->name,
            'email' => $user->email,
            'phone' => $user->mobile_number,
            'phone_code' => $user->mobile_country_code,
            'type' => $user->getRoleNameAttribute(),
            'image' => $user->profile_image_url ?? 'https://api.ymtaz.sa/Male.png',
            'nationality' => [
                'id' => $user->nationality?->id ?? null,
                'name' => $user->nationality?->name ?? null,
            ],
            'country' => [
                'id' => $user->countryRelation?->id,
                'name' => $user->countryRelation?->name,
            ],
            'region' => [
                'id' => $user->regionRelation?->id,
                'name' => $user->regionRelation?->name,
            ],
            'city' => [
                'id' => $user->cityRelation?->id,
                'title' => $user->cityRelation?->title,
            ],
            'longitude' => $user->longitude ?? null,
            'latitude' => $user->latitude ?? null,
            'gender' => $user->gender,
            'token' => $token,
            'status' => $user->is_active,
            'createdAt' => $user->created_at ? $user->created_at->toIso8601String() : null,
            'streamio_id' => $user->streamio_id ?? null,
            'streamio_token' => $user->streamio_token ?? null,
            'daysStreak' => $user->days_streak ?? 0,
            'points' => $user->points ?? 0,
            'xp' => $user->xp ?? 0,
            'currentLevel' => $user->current_level ?? 1,
            'currentRank' => [
                'id' => $user->rank_id ?? null,
                'name' => $user->rank_name ?? null,
                'border_color' => $user->rank_border_color ?? null,
                'image' => $user->rank_image ?? null,
            ],
            'xpUntilNextLevel' => $user->xp_until_next_level ?? 0,
            'referralCode' => $user->referral_code ?? null,
            'lastSeen' => $user->last_seen ? $user->last_seen->toIso8601String() : null,
            'email_confirmation' => $user->email_confirmation ?? 0,
            'phone_confirmation' => $user->phone_confirmation ?? 0,
            'profile_complete' => $user->isProfileComplete(),
            'account_type' => $user->getRoleNameAttribute(),
            'subscribed' => $user->subscribed ?? false,
            'subscription' => $user->subscription ?? null,
        ];

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "شريكنا العزيز :\nحسابكم الآن قيد الدراسة والتفعيل، وسيصلكم  الإشعار بتفعيل عضويتكم قريباً.",
            'data' => [
                'account' => $accountData,
            ],
        ], 200);
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('mobile_number', $request->email)
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم غير موجود',
            ], 404);
        }

        $code = strtoupper(Str::random(6)); // Random alphanumeric code

        $user->otp = $code;
        $user->save();

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $this->emailService->send(
                to: $user->email,
                subject: 'رمز استعادة كلمة المرور',
                body: '',
                type: 'view',
                viewPath: 'emails.reset_password',
                data: [
                    'name' => $user->first_name.' '.$user->second_name,
                    'otp' => $code,
                    'email' => $user->email,
                ]
            );
        } else {
            // Send SMS
            app(SmsService::class)->sendSms($user->mobile_number.$user->mobile_country_code, "رمز استعادة كلمة المرور هو: $code");
        }

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال الرمز إلى وسيلة التواصل المختارة.',
            'code' => $code,

        ]);
    }

    public function verifyCodeAndResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'invalid_data',
                'data' => [
                    'errors' => $validator->errors(),
                ],
            ], 422);
        }

        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('mobile_number', $request->email);
        })->where('otp', $request->token)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'رمز غير صحيح أو مستخدم غير موجود.',
            ], 401);
        }

        $user->password = bcrypt($request->password);
        $user->otp = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث كلمة المرور بنجاح.',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $user = User::where('id', $user['id'])
            ->first();

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|unique:users,mobile_number,'.$user->id,
            'first_name' => 'nullable|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'fourth_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'region_id' => 'nullable|exists:regions,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'nationality_id' => 'nullable|exists:nationalities,id',
            'degree' => 'nullable|integer',
            'about' => 'nullable|string',
            'national_id' => 'nullable|string|max:20',
            'account_type' => 'nullable|in:client,lawyer',
            'identity_type' => 'nullable|integer',
            'day' => 'nullable|integer|min:1|max:31',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:1900|max:'.now()->year,
            'general_specialty' => 'nullable|integer|exists:specialties,id',
            'accurate_specialty' => 'nullable|integer|exists:specialties,id',
            'functional_cases' => 'nullable|integer|exists:functional_cases,id',
            'languages' => 'nullable|array',
            'languages.*' => 'integer|exists:languages,id',
            'sections' => 'nullable|array',
            'sections.*' => 'integer|exists:sections,id',
            'license_no' => 'nullable|array',
            'license_no.*' => 'string|max:100',
            'license_image' => 'nullable|array',
            'license_image.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation_failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->fill([
            'email' => $request->email,
            'mobile_number' => $request->phone,
            'mobile_country_code' => $request->phone_code,
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'fourth_name' => $request->fourth_name,
            'gender' => $request->gender,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'region_id' => $request->region_id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'nationality_id' => $request->nationality_id,
            'degree' => $request->degree,
            'about' => $request->about,
            'account_type' => $request->account_type,
            'national_id' => $request->national_id,
            'identity_type' => $request->identity_type,
            'birth_date' => $request->year && $request->month && $request->day
                ? Carbon::createFromDate($request->year, $request->month, $request->day)
                : null,
        ]);

        $user->save();

        if ($request->has('languages')) {
            $user->languages()->sync($request->languages);
        }

        if ($request->has('sections')) {
            $user->sections()->sync($request->sections);
        }

        if ($request->has('license_no')) {
            foreach ($request->license_no as $sectionId => $licenseNumber) {
                $user->licenses()->updateOrCreate(
                    ['section_id' => $sectionId],
                    ['license_no' => $licenseNumber]
                );
            }
        }

        if ($request->hasFile('license_image')) {
            foreach ($request->file('license_image') as $sectionId => $file) {
                $filePath = $file->store('uploads/licenses', 'public');
                $user->licenses()->updateOrCreate(
                    ['section_id' => $sectionId],
                    ['license_image' => $filePath]
                );
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data' => ['user' => $user->fresh()],
        ]);
    }
}

// app(SmsService::class)->sendSms($user->mobile_number . $user->mobile_country_code, "رمز استعادة كلمة المرور هو: $code");

//   $this->emailService->send(
//                 to: $user->email,
//                 subject: 'رمز استعادة كلمة المرور',
//                 body: '',
//                 type: 'view',
//                 viewPath: 'emails.reset_password',
//                 data: [
//                     'name' => $user->first_name . ' ' . $user->second_name,
//                     'otp' => $code,
//                     'email' => $user->email,
//                 ]
// );
