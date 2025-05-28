@props([
    'label' => '',
    'name' => '',
    'rows' => 3,
    'required' => false,
    'readonly' => false,
    'placeholder' => '',
    'class' => '',
    'errors' => null
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        class="form-control {{ $class }}"
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        x-model="form.{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ $placeholder }}"
        :class="{ 'is-invalid': errors?.{{ $name }}?.length > 0 }"
        {{ $attributes }}
    ></textarea>

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ $name }}?.[0]"></div>
    @endif
</div>
