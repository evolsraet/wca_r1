@props([
    'label' => '',
    'name' => '',
    'accept' => '*/*',
    'preview' => false,
    'previewUrl' => '',
    'previewSize' => '100px',
    'errors' => null,
    'multiple' => false
])

@php
    $inputName = $multiple ? $name . '[]' : $name;
@endphp

<div class="mb-3"
    x-data="fileUpload('{{ $inputName }}', {{ $multiple ? 'true' : 'false' }})"
    x-init="console.log('FileUpload initialized:', {
        name: '{{ $inputName }}',
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
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </template>
            <div>
                <input
                    type="file"
                    class="form-control"
                    name="{{ $inputName }}"
                    @change="handleFileSelect"
                    accept="{{ $accept }}"
                    :multiple="{{ $multiple ? 'true' : 'false' }}"
                    :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
                    {{ $attributes }}
                >
                @if($errors)
                    <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
                @endif
            </div>
        </div>
    @else
        <div>
            <input
                type="file"
                class="form-control"
                name="{{ $inputName }}"
                @change="handleFileSelect"
                accept="{{ $accept }}"
                :multiple="{{ $multiple ? 'true' : 'false' }}"
                :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $name) }}?.length > 0 }"
                {{ $attributes }}
            >
            @if($errors)
                <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $name) }}?.[0]"></div>
            @endif

            <!-- 선택된 파일 목록 -->
            <template x-if="fileList.length > 0">
                <div class="mt-2">
                    <template x-for="(file, index) in fileList" :key="index">
                        <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded mb-1">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark me-2"></i>
                                <span x-text="file.name" class="text-truncate" style="max-width: 200px;"></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="removeFile(index)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    @endif
</div>
