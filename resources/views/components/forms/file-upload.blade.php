@props([
    'label' => '',
    'name' => '',
    'accept' => '*/*',
    'preview' => false,
    'previewUrl' => '',
    'previewSize' => '100px',
    'errors' => null
])

<div class="mb-3">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    @if($preview)
        <div class="d-flex align-items-center">
            <img :src="{{ $previewUrl }}" class="rounded-circle me-3" style="width: {{ $previewSize }}; height: {{ $previewSize }}; object-fit: cover;">
            <div>
                <input
                    type="file"
                    class="form-control"
                    @change="$dispatch('file-upload', { name: '{{ $name }}', event: $event })"
                    accept="{{ $accept }}"
                    :class="{ 'is-invalid': errors?.{{ $name }}?.length > 0 }"
                    {{ $attributes }}
                >
                @if($errors)
                    <div class="invalid-feedback" x-text="errors?.{{ $name }}?.[0]"></div>
                @endif
            </div>
        </div>
    @else
        <input
            type="file"
            class="form-control"
            @change="$dispatch('file-upload', { name: '{{ $name }}', event: $event })"
            accept="{{ $accept }}"
            :class="{ 'is-invalid': errors?.{{ $name }}?.length > 0 }"
            {{ $attributes }}
        >
        @if($errors)
            <div class="invalid-feedback" x-text="errors?.{{ $name }}?.[0]"></div>
        @endif
    @endif
</div>
