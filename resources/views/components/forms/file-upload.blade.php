@props([
    'label' => '',
    'name' => '',
    'accept' => '*/*',
    'preview' => false,
    'previewUrl' => '',
    'previewSize' => '100px',
    'errors' => null
])

<div class="mb-3"
    x-data="fileUpload('{{ $name }}')"
    x-init="console.log('FileUpload initialized:', {
        name: '{{ $name }}',
        parent: $el.closest('[x-data]'),
        hasForm: $el.closest('[x-data]')?.form ? true : false
    })"
>
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    @if($preview)
        <div class="d-flex align-items-center">
            <template x-if="previewUrl">
                <div class="position-relative me-3">
                    <img :src="previewUrl" class="rounded" style="width: {{ $previewSize }}; height: {{ $previewSize }}; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" @click="removeFile">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </template>
            <div>
                <input
                    type="file"
                    class="form-control"
                    name="{{ $name }}"
                    @change="handleFileSelect"
                    accept="{{ $accept }}"
                    :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
                    {{ $attributes }}
                >
                @if($errors)
                    <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
                @endif
            </div>
        </div>
    @else
        <input
            type="file"
            class="form-control"
            name="{{ $name }}"
            @change="handleFileSelect"
            accept="{{ $accept }}"
            :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
            {{ $attributes }}
        >
        @if($errors)
            <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
        @endif
    @endif
</div>
