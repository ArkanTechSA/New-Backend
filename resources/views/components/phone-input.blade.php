@props([
    'name' => 'mobile_number',
    'value' => '',
    'countryCode' => '',
    'label' => 'Mobile Number',
    'class' => 'col-md-4'
])

@php
    $dummyName = 'dummy_' . $name;
@endphp

<div class="{{ $class }}">
    <label class="form-label">{{ $label }}</label><br>
    <input type="tel" id="{{ $dummyName }}" name="{{ $dummyName }}" class="form-control" value="{{ $value }}">
    <input type="hidden" name="{{ $name }}" id="{{ $name }}">
    <input type="hidden" name="mobile_country_code" id="mobile_country_code">
</div>

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.min.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
@endonce

@push('Dashboard-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector("#{{ $dummyName }}");
            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                nationalMode: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js",
                geoIpLookup: callback => {
                    fetch("https://ipinfo.io/json?token=<YOUR_TOKEN>")
                        .then(res => res.json())
                        .then(data => callback(data.country || "us"))
                        .catch(() => callback("us"));
                }
            });

function updateHiddenFields() {
    const countryData = iti.getSelectedCountryData();
    const dialCode = countryData.dialCode;
    const fullNumber = iti.getNumber();

    const numberWithoutCode = fullNumber.replace('+' + dialCode, '').replace(/^0+/, '');

    document.getElementById("{{ $name }}").value = numberWithoutCode;
    document.getElementById("mobile_country_code").value = dialCode || '';
}


            input.addEventListener('input', updateHiddenFields);
            input.addEventListener('countrychange', updateHiddenFields);

            const rawNumber = @json($value);
            const dialCode = @json($countryCode);
            if (rawNumber && dialCode) {
                iti.setNumber('+' + dialCode + rawNumber);
            }

            updateHiddenFields();
        });
    </script>
@endpush
