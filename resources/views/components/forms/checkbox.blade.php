@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'class' => '',
    'errors' => null,
])

<div class="mb-3">
    <div class="form-check">
        <input
            type="checkbox"
            class="form-check-input {{ $class }}"
            id="{{ $name }}"
            name="{{ $name }}"
            x-model="form.{{ $name }}"
            {{ $required ? 'required' : '' }}
            :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
            {{ $attributes }}
        >
        <label class="form-check-label" for="{{ $name }}">{!! $label !!}</label>

        @if($errors)
            <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
        @endif
    </div>
</div>
