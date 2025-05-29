@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'required' => false,
    'class' => '',
    'errors' => null
])

<div class="mb-3">
    @if($label)
        <label class="form-label">
            @if($required)
                <span class="text-danger">*</span>
            @endif
            {{ $label }}
        </label>
    @endif

    <select
        class="form-select {{ $class }}"
        id="{{ $name }}"
        name="{{ $name }}"
        x-model="form.{{ $name }}"
        {{ $required ? 'required' : '' }}
        :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
        {{ $attributes }}
    >
        <option value="">선택하세요</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    </select>

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
    @endif
</div>
