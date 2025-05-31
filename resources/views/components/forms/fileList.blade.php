@props([
    'label' => '첨부파일',
    'dataPath' => 'files',
    'showDownload' => true,
    'downloadAction' => 'downloadFile'
])

@php
    $dataPath = str_replace('.', '?.', $dataPath);
@endphp

<div x-show="{{ $dataPath }} && {{ $dataPath }}.length > 0">
    @if($label)
        <h6 class="mb-2">
            <i class="mdi mdi-paperclip me-1"></i>
            {{ $label }}
        </h6>
    @endif

    <div class="mt-2">
        <template x-for="(file, index) in ({{ $dataPath }} || [])" :key="file.id || index">
            <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded mb-1 border">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-file me-2 text-muted"></i>
                    <span x-text="file.original_name || file.file_name || file.name || 'Unknown'"
                          class="text-truncate" style="max-width: 200px;"></span>
                    <small x-show="file.file_size"
                           x-text="formatFileSize(file.file_size)"
                           class="text-muted ms-2"></small>
                </div>

                @if($showDownload)
                    <button @click="{{ $downloadAction }}(file)"
                            class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-download"></i>
                        <span class="d-none d-sm-inline ms-1">다운로드</span>
                    </button>
                @endif
            </div>
        </template>
    </div>
</div>
