<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $token = session('admin_jwt_token');
        $user = null;

        if ($token) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                session()->forget('admin_jwt_token');
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                session()->forget('admin_jwt_token');
            }
        }

        $userId = $user?->id;
        $user = $userId ? User::find($userId) : null;

        // Check if the user is already authenticated
        if ($user && $user->isAdmin()) {
            return redirect()->intended(env('ADMIN_DASHBOARD', '/admin'));
        }

        return view('Dashboard.auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Incorrect credentials']);
        }

        $user = auth()->user()->id;
        $user = User::where('id', $user)->first();

        if (! $user->isAdmin()) {
            // JWTAuth::invalidate($token);
            return back()->withErrors(['email' => 'Access denied']);
        }

        // Store token in session
        session(['admin_jwt_token' => $token]);
        $user->update(['token' => $token]);
        $user->save();

        return redirect()->intended(env('ADMIN_DASHBOARD', '/admin'));
    }

    public function logout()
    {
        JWTAuth::invalidate(session('admin_jwt_token'));
        session()->forget('admin_jwt_token');
        Auth::logout();

        return redirect()->route('admin.login');
    }

    public function editProfile()
    {
        $token = session('admin_jwt_token');
        $user = null;

        if ($token) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();
            } catch (\Exception $e) {
                return redirect()->route('admin.login');
            }
        }

        $nationalities = \App\Models\Nationality::all();

        return view('Dashboard.auth.edit-profile', compact('user', 'nationalities'));
    }

    public function updateProfile(Request $request)
    {

        $user = JWTAuth::setToken(session('admin_jwt_token'))->authenticate();

        $fullNumber = $request->input('dummy_mobile_number');
        $countryCode = $request->input('mobile_country_code');

        $cleanNumber = preg_replace('/^\+?'.preg_quote($countryCode, '/').'/', '', $fullNumber);

        $user['mobile_number'] = $cleanNumber;
        $user['mobile_country_code'] = $countryCode;

        $request->merge([
            'mobile_number' => $cleanNumber,
        ]);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'latest_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'mobile_number' => 'nullable|string|max:20|unique:users,mobile_number,'.$user->id,
            'address' => 'nullable|string',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'region_id' => 'nullable|integer|exists:regions,id',

            'photo' => 'nullable|image|max:2048',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'json1' => 'nullable|json',
            'json2' => 'nullable|json',
            'json3' => 'nullable|json',
            'json4' => 'nullable|json',
            'json5' => 'nullable|json',
            'column1' => 'nullable|string',
            'column2' => 'nullable|string',
            'column3' => 'nullable|string',
            'column4' => 'nullable|string',
            'column5' => 'nullable|string',
            'longtext1' => 'nullable|string',
            'longtext2' => 'nullable|string',
            'longtext3' => 'nullable|string',
            'longtext4' => 'nullable|string',
            'longtext5' => 'nullable|string',
            'referral_code' => 'nullable|string|max:20|unique:users,referral_code,'.$user->id,
            'nationality_id' => 'nullable|integer|exists:nationalities,id',

        ]);

        $user->fill($request->only([
            'first_name',
            'latest_name',
            'email',
            'mobile_number',
            'address',
            'nationality_id',
            'country',
            'city',
            'region',
            'lat',
            'long',
            'json1',
            'json2',
            'json3',
            'json4',
            'json5',
            'column1',
            'column2',
            'column3',
            'column4',
            'column5',
            'longtext1',
            'longtext2',
            'longtext3',
            'longtext4',
            'longtext5',
        ]));

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoName = time().'.'.$file->getClientOriginalExtension();

            $uploadPath = public_path('assets/Backend/layout/img/users');

            if (! file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // // Debug
            // dd([
            //     'upload_path' => $uploadPath,
            //     'photo_name' => $photoName,
            //     'file_exists_before_move' => $file->isValid()
            // ]);

            $file->move($uploadPath, $photoName);
            $user->photo = $photoName;
        }

        $fullNumber = $request->input('dummy_mobile_number');
        $countryCode = $request->input('mobile_country_code');

        $cleanNumber = preg_replace('/^\+?'.preg_quote($countryCode, '/').'/', '', $fullNumber);

        $user['mobile_number'] = $cleanNumber;
        $user['mobile_country_code'] = $countryCode;

        // dd($user);
        $user->save();

        return back()->with('success', 'تم تحديث البيانات بنجاح.');
    }

    public function changePasswordForm()
    {
        return view('Dashboard.auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = JWTAuth::setToken(session('admin_jwt_token'))->authenticate();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
