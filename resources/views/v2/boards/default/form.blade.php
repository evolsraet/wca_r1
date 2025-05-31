@extends('v2.layouts.app')

@section('content')
<div class="board-form board-skin-{{ $board->skin }}"
     x-data="articleForm"
     x-init="init('{{ $board->id }}', {{ $articleId ?? 'null' }})">

    <!-- 폼 헤더 -->
    <div class="form-header mb-4">
        <h2 x-text="isEdit ? '게시글 수정' : '게시글 작성'"></h2>
        <p class="text-muted">{{ $board->name ?? $board->id }}</p>
    </div>

    <!-- 로딩 상태 -->
    <div x-show="loading" class="text-center py-5">
        <i class="mdi mdi-loading mdi-spin fs-2"></i>
        <p class="mt-2">로딩 중...</p>
    </div>

    <!-- 폼 -->
    <div x-show="!loading">
        <form @submit.prevent="submit()">
            <!-- 카테고리 선택 -->
            @if(!empty($board->categories))
                <div class="mb-3">
                    <label class="form-label">카테고리</label>
                    <select x-model="form.article.category"
                            class="form-select"
                            :class="{ 'is-invalid': errors.category }">
                        <option value="">카테고리를 선택하세요</option>
                        @foreach(json_decode($board->categories) as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <div x-show="errors.category" class="invalid-feedback" x-text="errors.category"></div>
                </div>
            @endif

            <!-- 제목 -->
            <div class="mb-3">
                <label class="form-label">제목 <span class="text-danger">*</span></label>
                <input type="text"
                       x-model="form.article.title"
                       class="form-control"
                       :class="{ 'is-invalid': errors.title }"
                       placeholder="제목을 입력하세요"
                       required>
                <div x-show="errors.title" class="invalid-feedback" x-text="errors.title"></div>
            </div>

            <!-- 내용 -->
            <div class="mb-3">
                <label class="form-label">내용 <span class="text-danger">*</span></label>
                <textarea x-model="form.article.content"
                          class="form-control"
                          :class="{ 'is-invalid': errors.content }"
                          rows="15"
                          placeholder="내용을 입력하세요"
                          required></textarea>
                <div x-show="errors.content" class="invalid-feedback" x-text="errors.content"></div>
            </div>

            <!-- 첨부파일 -->
            @if(auth()->user()?->hasPermissionTo($board->attach_permission ?? 'act.admin'))
                <div class="mb-3">
                    <label class="form-label">첨부파일</label>
                    <input type="file"
                           name="attachments[]"
                           class="form-control"
                           :class="{ 'is-invalid': errors.attachments }"
                           multiple
                           accept="*/*">
                    <div class="form-text">여러 파일을 선택할 수 있습니다.</div>
                    <div x-show="errors.attachments" class="invalid-feedback" x-text="errors.attachments"></div>

                    <!-- 기존 첨부파일 (수정시) -->
                    <div x-show="isEdit && existingAttachments.length > 0" class="mt-2">
                        <small class="text-muted">기존 첨부파일:</small>
                        <div class="list-group mt-1">
                            <template x-for="attachment in existingAttachments" :key="attachment.id">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span x-text="attachment.original_name"></span>
                                    <button type="button"
                                            @click="removeAttachment(attachment.id)"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 비밀글 설정 -->
            @if($board->use_secret)
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox"
                               x-model="form.article.is_secret"
                               class="form-check-input"
                               id="is_secret"
                               value="1">
                        <label class="form-check-label" for="is_secret">
                            <i class="mdi mdi-lock me-1"></i>비밀글로 설정
                        </label>
                    </div>
                </div>
            @endif

            <!-- 에러 메시지 -->
            <div x-show="Object.keys(errors).length > 0" class="alert alert-danger mb-3">
                <ul class="mb-0">
                    <template x-for="(error, field) in errors" :key="field">
                        <li x-text="error"></li>
                    </template>
                </ul>
            </div>

            <!-- 액션 버튼 -->
            <div class="form-actions d-flex justify-content-between">
                <a href="{{ route('board.list', $board->id) }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>취소
                </a>

                <div>
                    <button type="button"
                            @click="saveDraft()"
                            class="btn btn-outline-info me-2"
                            :disabled="submitting">
                        <i class="mdi mdi-content-save-outline me-1"></i>임시저장
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            :disabled="submitting">
                        <span x-show="submitting">
                            <i class="mdi mdi-loading mdi-spin me-1"></i>
                        </span>
                        <i x-show="!submitting" class="mdi mdi-content-save me-1"></i>
                        <span x-text="isEdit ? '수정' : '등록'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
