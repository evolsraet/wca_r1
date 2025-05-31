@props([
    'type' => 'text',
    'label' => '',
    'name' => '',
    'required' => false,
    'readonly' => false,
    'placeholder' => '',
    'class' => '',
    'errors' => null,
    'model' => null,
    'noMargin' => false
])

<div class="{{ $noMargin ? '' : 'mb-3' }}">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            @if($required)
                <span class="text-danger">*</span>
            @endif
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        class="form-control {{ $class }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @if($model)
            x-model="{{ $model }}"
        @else
            x-model="form.{{ $name }}"
        @endif
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ $placeholder }}"
        @if($errors)
            :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
        @endif
        {{ $attributes }}
    >

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
    @endif
</div>
