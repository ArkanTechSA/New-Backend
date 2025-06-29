<x-layout.default :title="'Edit Profile'">
@push('Dashboard-styles')
        <link rel="stylesheet" href="{{ admin_asset('layout/css/leaflet.css') }}" />

@endpush
    <div>
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-md-12">

                        <div class="mb-6 card">
                            <div class="card-body">
                                <div class="gap-6 d-flex align-items-start align-items-sm-center">
                                    <img
                                        {{-- src="{{ $user->photo ? asset('uploads/users/'.$user->photo) : asset('assets/img/avatars/1.png') }}" --}}
                                    src="{{ $user->photo ? admin_asset('layout/img/users/'.$user->photo) : admin_asset('layout/img/avatar.png') }}"

                                        alt="user-avatar"
                                        class="rounded d-block w-px-100 h-px-100 object-fit-image"
                                        id="uploadedAvatar" />

                                    
                                </div>
                            </div>

                            <div class="pt-4 card-body">
                                <form id="formAccountSettings" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')

                              <div class="button-wrapper">
                                        <label for="upload" class="mb-4 btn btn-primary me-3" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="icon-base ti tabler-upload d-block d-sm-none"></i>
                                            <input
                                                type="file"
                                                id="upload"
                                                class="account-file-input"
                                                name="photo"
                                                hidden
                                                accept="image/png, image/jpeg" />
                                        </label>
                                        <button type="button" class="mb-4 btn btn-label-secondary account-image-reset">
                                            <i class="icon-base ti tabler-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>
                                        <div>Allowed JPG or PNG. Max size of 800K</div>
                                    </div>
                                    <div class="mb-6 row gy-4 gx-6">
                                        <div class="col-md-6">
                                            <label class="form-label">First Name</label>
                                            <input class="form-control" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input class="form-control" type="text" name="latest_name" value="{{ old('latest_name', $user->latest_name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}">
                                        </div>
                                         <x-phone-input name="mobile_number" 
                                    :value="old('mobile_number', $user->mobile_number)" 
                                    :countryCode="old('mobile_country_code', $user->mobile_country_code)"
                                        label="رقم الموبايل" class="col-md-3" />

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
                                    <x-location-select  :selectedCountry="old('country', $user->country)" :selectedRegion="old('region', $user->region)"
                                        :selectedCity="old('city', $user->city)" :fieldNames="[
                                            'country' => 'country',
                                            'region' => 'region',
                                            'city' => 'city',
                                        ]" />



                                        <div class="col-md-6">
                                            <label class="form-label">Address</label>
                                            <input class="form-control" type="text" name="address" value="{{ old('address', $user->address) }}">
                                        </div>


                                        <div class="col-md-2">
                                            <label class="form-label">Latitude</label>
                                            <input class="form-control" type="text" name="lat" value="{{ old('lat', $user->lat) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Longitude</label>
                                            <input class="form-control" type="text" name="long" value="{{ old('long', $user->long) }}">
                                        </div> 
                                         <div class="col-md-12">
                                            <!-- Draggable Marker With Popup -->
                <div class="col-12">
                  <div class="card">
                    {{-- <h5 class="card-header">Draggable Marker With Popup</h5> --}}
                    <div class="card-body">
                      <div class="leaflet-map" id="dragMap"></div>

<input type="hidden" id="lat" name="lat" value="{{ old('lat', $user->lat ?? '') }}">
<input type="hidden" id="long" name="long" value="{{ old('long', $user->long ?? '') }}">
                    </div>
                  </div>
                </div>
                <!-- /Draggable Marker With Popup -->

                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary me-3">Save Changes</button>
                                        <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="content-backdrop fade"></div>
        </div>
    </div>
@push('Dashboard-scripts')

    <script defer src="{{ admin_asset('layout/js/leaflet.js') }}"></script>
    {{-- <script defer src="{{ admin_asset('layout/js/maps-leaflet.js') }}"></script> --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const latInput = document.getElementById("lat");
    const longInput = document.getElementById("long");

    let lat = parseFloat(latInput.value) || 30.0444;
    let long = parseFloat(longInput.value) || 31.2357;

    const map = L.map('dragMap').setView([lat, long], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([lat, long], { draggable: true }).addTo(map);

    marker.on('dragend', function (e) {
        const position = e.target.getLatLng();
        latInput.value = position.lat;
        longInput.value = position.lng;
    });

    function setLocation(lat, long) {
        marker.setLatLng([lat, long]);
        map.setView([lat, long], 13);
        latInput.value = lat;
        longInput.value = long;
    }

    // 📍 Search with Leaflet Control Geocoder
    L.Control.geocoder({
        defaultMarkGeocode: false
    }).on('markgeocode', function(e) {
        const center = e.geocode.center;
        setLocation(center.lat, center.lng);
    }).addTo(map);

    // 📍 Dropdown change event
    const dropdown = document.getElementById("locationDropdown");
    dropdown.addEventListener("change", function () {
        const value = this.value;
        if (value) {
            const [lat, long] = value.split(',').map(parseFloat);
            setLocation(lat, long);
        }
    });

    // 📍 Optional: Get location if empty
    if (!latInput.value || !longInput.value) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    setLocation(position.coords.latitude, position.coords.longitude);
                },
                function (error) {
                    console.warn("تعذر الحصول على الموقع الحالي.");
                }
            );
        }
    }
});
</script>


@endpush
</x-layout.default>
