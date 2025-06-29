<x-layout.default :title="__('dashboard.create_Providers')">
    @push('Dashboard-styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
    @endpush

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">

                    <div class="mb-6 card">
                        <div class="card-body">
                            <div class="gap-4 mb-4 d-flex align-items-center">
                                <img src="{{ admin_asset('layout/img/avatar.png') }}" alt="user-avatar"
                                    class="rounded d-block w-px-100 h-px-100 object-fit-image" id="uploadedAvatar" />
                            </div>
                        </div>

                        <div class="pt-4 card-body">
                            <form id="formAccountSettings" method="POST" action="{{ route('providers.store') }}"
                                enctype="multipart/form-data">
                                @csrf

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
                                        <input class="form-control" type="text" name="first_name" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.second_name')</label>
                                        <input class="form-control" type="text" name="second_name" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.third_name')</label>
                                        <input class="form-control" type="text" name="third_name" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.fourth_name')</label>
                                        <input class="form-control" type="text" name="fourth_name" required>
                                    </div>

                                    {{-- Section: Gender --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.personal_detail')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">@lang('dashboard.gender')</label>
                                        <select class="form-control" name="gender" required>
                                            <option value="">@lang('dashboard.select-gender')</option>
                                            <option value="Male">
                                                @lang('dashboard.male')
                                            </option>
                                            <option value="Female">
                                                @lang('dashboard.female')
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-md-3">
    <label class="form-label">@lang('dashboard.nationality')</label>
    <select class="form-control" name="nationality_id">
        <option value="">@lang('dashboard.select_nationality')</option>
        @foreach($nationalities as $nationality)
            <option value="{{ $nationality->id }}"
                >
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
                                        <input class="form-control" type="email" name="email" required>
                                    </div>
                                    <x-phone-input 
    name="mobile_number"
    :value="null"
    :countryCode="null"
    label="رقم الموبايل"
    class="col-md-3"
/>


                                    {{-- Section: Password --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.password_section')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">@lang('dashboard.password')</label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">@lang('dashboard.confirm_password')</label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                            required>
                                    </div>

                                    {{-- Section: Location --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.location')</h5>
                                        <hr>
                                    </div>

                                    <x-location-select :selectedCountry="null" :selectedRegion="null"
                                        :selectedCity="null" :fieldNames="[
                                            'country' => 'country',
                                            'region' => 'region',
                                            'city' => 'city',
                                        ]" />


                                    <div class="col-md-6">
                                        <label class="form-label">@lang('dashboard.address')</label>
                                        <input class="form-control" type="text" name="address">
                                    </div>

                                    {{-- Section: Coordinates --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.coordinates')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.latitude')</label>
                                        <input class="form-control" type="text" name="lat">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.longitude')</label>
                                        <input class="form-control" type="text" name="long">
                                    </div>

                                    {{-- Section: Account Settings --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.account_settings')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.status')</label>
                                        <select name="is_active" class="form-control" required>
                                            <option value="1">
                                                @lang('dashboard.active')
                                            </option>
                                            <option value="0">
                                                @lang('dashboard.pending')
                                            </option>
                                            <option value="2">
                                                @lang('dashboard.blocked')
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.role')</label>
                                        <select name="role" class="form-control" required>
                                            <option value="{{ \App\Models\User::ROLE_LAWYER }}">
                                                @lang('dashboard.lawyer')
                                            </option>
                                            <option value="{{ \App\Models\User::ROLE_CLIENT }}">
                                                @lang('dashboard.client')
                                            </option>
                                            <option value="{{ \App\Models\User::ROLE_ADMIN }}">
                                                @lang('dashboard.admin')
                                            </option>
                                            <option value="{{ \App\Models\User::ROLE_SUPERVISOR }}">
                                                @lang('dashboard.supervisor')
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">@lang('dashboard.is_old')</label>
                                        <select class="form-control" name="is_old" required>
                                            <option value="0">
                                                @lang('dashboard.no')
                                            </option>
                                            <option value="1">
                                                @lang('dashboard.yes')
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Section: Referral Info --}}
                                    <div class="col-12">
                                        <h5>@lang('dashboard.referral_info')</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.referral_code')</label>
                                        <input class="form-control" type="text" name="referral_code" disabled>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">@lang('dashboard.referred_by')</label>
                                        <input class="form-control" type="text" name="referred_by">
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
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
        <script>
            const phoneInputField = document.querySelector("#phone");
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "eg",
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js",
            });

            phoneInputField.addEventListener('change', function() {
                document.getElementById('mobile_number').value = phoneInput.getNumber();
                document.getElementById('mobile_country_code').value = phoneInput.getSelectedCountryData().dialCode;
            });
        </script>
    @endpush
</x-layout.default>
