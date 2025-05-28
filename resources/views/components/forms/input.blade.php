@props([
    'type' => 'text',
    'label' => '',
    'name' => '',
    'required' => false,
    'readonly' => false,
    'placeholder' => '',
    'class' => '',
    'errors' => null,
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <input
        type="{{ $type }}"
        class="form-control {{ $class }}"
        id="{{ $name }}"
        name="{{ $name }}"
        x-model="form.{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ $placeholder }}"
        :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
        {{ $attributes }}
    >

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
    @endif
</div>
