<x-layout.default :title="__('dashboard.edit_profile')">
    @push('Dashboard-styles')
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css"> --}}
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">

                    <div class="mb-6 card">
                        <div class="card-body">
                            <div class="gap-4 mb-4 d-flex align-items-center">
                                <img src="{{ $user->photo ? admin_asset('layout/img/users/' . $user->photo) : admin_asset('layout/img/avatar.png') }}"
                                    alt="user-avatar" class="rounded d-block w-px-100 h-px-100 object-fit-image"
                                    id="uploadedAvatar" />
                            </div>
                        </div>

                        <div class="pt-4 card-body">
                            <form id="formAccountSettings" method="POST"
                                action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                {{-- Upload Photo --}}
                                <div class="mb-4 button-wrapper">
                                    <label for="upload" class="btn btn-primary me-3" tabindex="0">
                                        @lang('dashboard.upload_new_photo')
                                        <input type="file" id="upload" class="account-file-input" name="photo"
                                            hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-label-secondary account-image-reset">
                                        @lang('dashboard.reset')
                                    </button>
                                    <div class="mt-2 text-muted small">@lang('dashboard.allowed_image_types')</div>
                                </div>

                                <div class="row gy-4 gx-6">
                                    {{-- Section: Personal Information --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.personal_information')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.first_name')</label>
                                        <input class="form-control" type="text" name="first_name"
                                            value="{{ old('first_name', $user->first_name) }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.second_name')</label>
                                        <input class="form-control" type="text" name="second_name"
                                            value="{{ old('second_name', $user->second_name) }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.third_name')</label>
                                        <input class="form-control" type="text" name="third_name"
                                            value="{{ old('third_name', $user->third_name) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.fourth_name')</label>
                                        <input class="form-control" type="text" name="fourth_name"
                                            value="{{ old('fourth_name', $user->fourth_name) }}">
                                    </div>

                                    {{-- Section: Gender --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.personal_detail')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">@lang('dashboard.gender')</label>
                                        <select class="form-control" name="gender">
                                            <option value="">@lang('dashboard.select')</option>
                                            <option value="Male"
                                                {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>
                                                @lang('dashboard.male')</option>
                                            <option value="Female"
                                                {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>
                                                @lang('dashboard.female')</option>
                                        </select>
                                    </div>
<div class="col-md-3">
    <label class="form-label">@lang('dashboard.nationality')</label>
    <select class="form-control" name="nationality_id">
        <option value="">@lang('dashboard.select_nationality')</option>
        @foreach($nationalities as $nationality)
            <option value="{{ $nationality->id }}"
                {{ old('nationality_id', $user->nationality_id) == $nationality->id ? 'selected' : '' }}>
          {{    $nationality->name}}
            </option>
        @endforeach
    </select>
</div>


                                    {{-- Section: Contact Information --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.contact_information')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.email')</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}">
                                    </div>
                                    <x-phone-input name="mobile_number" 
                                    :value="old('mobile_number', $user->mobile_number)" 
                                    :countryCode="old('mobile_country_code', $user->mobile_country_code)"
                                        label="رقم الموبايل" class="col-md-3" />

                                    <div class="col-md-1">
                                        <label class="form-label">@lang('dashboard.otp')</label>
                                        <input disabled class="form-control" type="text" name="otp"
                                            value="{{ old('otp', $user->otp) }}">
                                    </div>

                                    {{-- Section: Location --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.location')</h5>
                                        <hr>
                                    </div>


                                    <x-location-select :selectedCountry="old('country', $user->country)" :selectedRegion="old('region', $user->region)"
                                        :selectedCity="old('city', $user->city)" :fieldNames="[
                                            'country' => 'country',
                                            'region' => 'region',
                                            'city' => 'city',
                                        ]" />

                                    <div class="col-md-6">
                                        <label class="form-label">@lang('dashboard.address')</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', $user->address) }}">
                                    </div>

                                    {{-- Section: Coordinates --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.coordinates')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.latitude')</label>
                                        <input class="form-control" type="text" name="lat"
                                            value="{{ old('lat', $user->lat) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.longitude')</label>
                                        <input class="form-control" type="text" name="long"
                                            value="{{ old('long', $user->long) }}">
                                    </div>

                                    {{-- Section: Account Settings --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.account_settings')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.status')</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>
                                                @lang('dashboard.active')</option>
                                            <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>
                                                @lang('dashboard.pending')</option>
                                            <option value="2" {{ $user->is_active == 2 ? 'selected' : '' }}>
                                                @lang('dashboard.blocked')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.role')</label>
                                        <select name="role" class="form-control">
                                            <option value="{{ \App\Models\User::ROLE_LAWYER }}"
                                                {{ $user->role === \App\Models\User::ROLE_LAWYER ? 'selected' : '' }}>
                                                @lang('dashboard.lawyer')</option>
                                            <option value="{{ \App\Models\User::ROLE_CLIENT }}"
                                                {{ $user->role === \App\Models\User::ROLE_CLIENT ? 'selected' : '' }}>
                                                @lang('dashboard.client')</option>
                                            <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                                                {{ $user->role === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>
                                                @lang('dashboard.admin')</option>
                                            <option value="{{ \App\Models\User::ROLE_SUPERVISOR }}"
                                                {{ $user->role === \App\Models\User::ROLE_SUPERVISOR ? 'selected' : '' }}>
                                                @lang('dashboard.supervisor')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">@lang('dashboard.is_old')</label>
                                        <select class="form-control" name="is_old">
                                            <option value="0"
                                                {{ old('is_old', $user->is_old) == '0' ? 'selected' : '' }}>
                                                @lang('dashboard.no')</option>
                                            <option value="1"
                                                {{ old('is_old', $user->is_old) == '1' ? 'selected' : '' }}>
                                                @lang('dashboard.yes')</option>
                                        </select>
                                    </div>

                                    {{-- Section: Referral Info --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.referral_info')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.referral_code')</label>
                                        <input class="form-control" type="text" name="referral_code"
                                            value="{{ old('referral_code', $user->referral_code) }}" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.referred_by')</label>
                                        <input class="form-control" type="text" name="referred_by"
                                            value="{{ old('referred_by', $user->referred_by) }}">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-3">@lang('dashboard.save_changes')</button>
                                    <button type="reset" class="btn btn-label-secondary">@lang('dashboard.cancel')</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>

    @push('Dashboard-scripts')
    @endpush
</x-layout.default>
