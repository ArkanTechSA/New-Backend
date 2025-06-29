@props([
    'label' => '',
    'name' => '',
    'type' => 'text', // text, select
    'options' => [],  // للاختيار في select
    'value' => '',
    'placeholder' => '',
])

<div class="col-md-6">
    <label class="form-label">{{ $label }}</label>

    @if ($type === 'select')
<select name="{{ $name }}" class="form-control">
    <option value="" disabled {{ $value === '' ? 'selected' : '' }}>اختر</option>
    @foreach ($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ (string)$value === (string)$optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
    @endforeach
</select>


    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="form-control"
        />
    @endif

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
