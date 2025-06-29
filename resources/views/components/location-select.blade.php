@props([
    'selectedCountry' => null,
    'selectedRegion' => null,
    'selectedCity' => null,
    'fieldNames' => [
        'country' => 'country',
        'region' => 'region',
        'city' => 'city',
    ]
])

@php
    $countryName = $fieldNames['country'];
    $regionName = $fieldNames['region'];
    $cityName = $fieldNames['city'];
@endphp

<div class="row">
    <div class="col-md-2">
        <label class="form-label">@lang('dashboard.country')</label>
        <select id="country-{{ $countryName }}" name="{{ $countryName }}" class="form-control">
            <option value="">-- اختر الدولة --</option>
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">@lang('dashboard.region')</label>
        <select id="region-{{ $regionName }}" name="{{ $regionName }}" class="form-control">
            <option value="">-- اختر المنطقة --</option>
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">@lang('dashboard.city')</label>
        <select id="city-{{ $cityName }}" name="{{ $cityName }}" class="form-control">
            <option value="">-- اختر المدينة --</option>
        </select>
    </div>
</div>

@push('Dashboard-scripts')
<script>
    $(document).ready(function () {
        const countryField = '#country-{{ $countryName }}';
        const regionField = '#region-{{ $regionName }}';
        const cityField = '#city-{{ $cityName }}';

        const oldCountry = "{{ $selectedCountry }}";
        const oldRegion = "{{ $selectedRegion }}";
        const oldCity = "{{ $selectedCity }}";

        function loadCountries(selectedCountry = null) {
            return $.ajax({
                url: '{{ route("admin.getCountries") }}',
                type: 'GET',
                dataType: 'json',
                success: function (countries) {
                    $(countryField).empty().append('<option value="">-- اختر الدولة --</option>');
                    $.each(countries, function (i, country) {
                        $(countryField).append('<option value="' + country.id + '">' + country.name + '</option>');
                    });
                    if (selectedCountry) {
                        $(countryField).val(selectedCountry);
                    }
                }
            });
        }

        function loadRegions(countryId, selectedRegion = null) {
            if (!countryId) {
                $(regionField).empty().append('<option value="">-- اختر المنطقة --</option>');
                $(cityField).empty().append('<option value="">-- اختر المدينة --</option>');
                return $.Deferred().resolve().promise();
            }
            return $.ajax({
                url: '{{ route("admin.getRegions", "") }}/' + countryId,
                type: "GET",
                dataType: "json",
                success: function (regions) {
                    $(regionField).empty().append('<option value="">-- اختر المنطقة --</option>');
                    $(cityField).empty().append('<option value="">-- اختر المدينة --</option>');
                    $.each(regions, function (i, region) {
                        $(regionField).append('<option value="' + region.id + '">' + region.name + '</option>');
                    });
                    if (selectedRegion) {
                        $(regionField).val(selectedRegion);
                    }
                }
            });
        }

        function loadCities(regionId, selectedCity = null) {
            if (!regionId) {
                $(cityField).empty().append('<option value="">-- اختر المدينة --</option>');
                return $.Deferred().resolve().promise();
            }
            return $.ajax({
                url: '{{ route("admin.getCities", "") }}/' + regionId,
                type: "GET",
                dataType: "json",
                success: function (cities) {
                    $(cityField).empty().append('<option value="">-- اختر المدينة --</option>');
                    $.each(cities, function (i, city) {
                        $(cityField).append('<option value="' + city.id + '">' + city.title + '</option>');
                    });
                    if (selectedCity) {
                        $(cityField).val(selectedCity);
                    }
                }
            });
        }

        $(countryField).on('change', function () {
            const countryId = $(this).val();
            loadRegions(countryId).then(() => {
                $(cityField).empty().append('<option value="">-- اختر المدينة --</option>');
            });
        });

        $(regionField).on('change', function () {
            const regionId = $(this).val();
            loadCities(regionId);
        });

        loadCountries(oldCountry)
            .then(() => loadRegions(oldCountry, oldRegion))
            .then(() => loadCities(oldRegion, oldCity));
    });
</script>

@endpush
