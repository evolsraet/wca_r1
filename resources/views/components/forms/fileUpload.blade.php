@props([
    'label' => '',
    'name' => '',
    'accept' => '*/*',
    'preview' => false,
    'previewUrl' => '',
    'previewSize' => '100px',
    'errors' => null,
    'existingFiles' => [],
    'buttonText' => '파일 선택'
])

@php
    $isMultiple = str_ends_with($name, '[]');
    $inputName = $name;
    $errorKey = str_replace('[]', '', $name);
@endphp

<div class="mb-3"
    x-data="fileUpload('{{ $inputName }}')"
>
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    @if($preview)
        <div class="d-flex align-items-center">
            <template x-if="fileList && fileList.length > 0">
                <div class="position-relative me-3">
                    <template x-if="fileList[0] && fileList[0].previewUrl">
                        <img :src="fileList[0].previewUrl" class="rounded" style="width: {{ $previewSize }}; height: {{ $previewSize }}; object-fit: cover;">
                    </template>
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" @click="removeFile()">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </template>
            <div>
                <input
                    type="file"
                    class="d-none"
                    name="{{ $inputName }}"
                    @change="handleFileSelect"
                    accept="{{ $accept }}"
                    :multiple="{{ $isMultiple ? 'true' : 'false' }}"
                    :class="{ 'is-invalid': errors?.{{ $errorKey }}?.length > 0 }"
                    {{ $attributes }}
                    id="file-{{ $inputName }}"
                >
                <button type="button" class="btn btn-light w-100 border" onclick="document.getElementById('file-{{ $inputName }}').click()">
                    <i class="mdi mdi-upload me-1"></i>{{ $buttonText }}
                </button>
                @if($errors)
                    <div class="invalid-feedback" x-text="errors?.{{ $errorKey }}?.[0]"></div>
                @endif
            </div>
        </div>
    @else
        <div>
            <input
                type="file"
                class="d-none"
                name="{{ $inputName }}"
                @change="handleFileSelect"
                accept="{{ $accept }}"
                :multiple="{{ $isMultiple ? 'true' : 'false' }}"
                :class="{ 'is-invalid': errors?.{{ $errorKey }}?.length > 0 }"
                {{ $attributes }}
                id="file-{{ $inputName }}"
            >
            <button type="button" class="btn btn-light w-100 border" onclick="document.getElementById('file-{{ $inputName }}').click()">
                <i class="mdi mdi-upload me-1"></i>{{ $buttonText }}
            </button>
            @if($errors)
                <div class="invalid-feedback" x-text="errors?.{{ $errorKey }}?.[0]"></div>
            @endif

            <!-- 선택된 파일 목록 -->
            <template x-if="fileList && fileList.length > 0">
                <div class="mt-2">
                    <template x-for="(file, index) in fileList" :key="index">
                        <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded mb-1">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark me-2"></i>
                                <span x-text="file.name" class="text-truncate" style="max-width: 200px;"></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="removeFile(index)">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    @endif

    <!-- 기존 파일 목록 -->
    @if(count($existingFiles) > 0)
        <div class="mt-2">
            <small class="text-muted mb-1 d-block">기존 파일</small>
            @foreach($existingFiles as $file)
                <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded mb-1" data-file-uuid="{{ $file['uuid'] }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark me-2"></i>
                        <a href="{{ $file['original_url'] }}" target="_blank" class="text-decoration-none text-truncate" style="max-width: 200px;">
                            {{ $file['file_name'] }}
                        </a>
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            data-delete-file
                            data-file-uuid="{{ $file['uuid'] }}">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
