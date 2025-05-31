@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'required' => false,
    'class' => '',
    'errors' => null,
    'model' => null,
    'noMargin' => false,
    'placeholder' => '선택하세요'
])

<div class="{{ $noMargin ? '' : 'mb-3' }}">
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
        @if($model)
            x-model="{{ $model }}"
        @else
            x-model="form.{{ $name }}"
        @endif
        {{ $required ? 'required' : '' }}
        @if($errors)
            :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
        @endif
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
    @endif
</div>
