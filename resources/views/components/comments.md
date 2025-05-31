# Comments 컴포넌트 사용법

## 개요
`comments.blade.php`는 다양한 곳에서 재사용할 수 있는 댓글 시스템 컴포넌트입니다. Laravel의 Polymorphic Relationship을 활용하여 게시판, 상품, 이벤트 등 어디서든 댓글 기능을 쉽게 추가할 수 있습니다.

## 파일 구조
```
resources/views/components/comments.blade.php  ← Blade 컴포넌트
resources/v2/js/feature/comments.js           ← Alpine.js 컴포넌트
```

## 백엔드 테이블 구조
```sql
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commentable_type` varchar(255) NOT NULL,    -- 댓글 대상 모델 (Article 등)
  `commentable_id` varchar(255) NOT NULL,      -- 댓글 대상 ID
  `content` text NOT NULL,                     -- 댓글 내용
  `user_id` bigint(20) unsigned NOT NULL,      -- 작성자 ID
  `ip` varchar(255) DEFAULT NULL,              -- 작성자 IP
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);
```

## 기본 사용법

### 1. 게시판에서 사용
```blade
<!-- 게시글 상세 페이지에서 -->
<x-comments 
    commentable-type="Article" 
    commentable-id="{{ $article->id }}" />
```

### 2. 상품에서 사용
```blade
<!-- 상품 상세 페이지에서 -->
<x-comments 
    commentable-type="Product" 
    commentable-id="{{ $product->id }}" 
    title="상품 리뷰" />
```

### 3. 이벤트에서 사용
```blade
<!-- 이벤트 페이지에서 -->
<x-comments 
    commentable-type="Event" 
    commentable-id="{{ $event->id }}" 
    title="이벤트 댓글" />
```

## 옵션 설명

| 옵션 | 타입 | 기본값 | 설명 |
|------|------|--------|------|
| `commentable-type` | string | 'Article' | 댓글 대상 타입 (Article, Product, Event 등) |
| `commentable-id` | string | '' | 댓글 대상 ID |
| `title` | string | '댓글' | 댓글 섹션 제목 |
| `show-title` | boolean | true | 제목 표시 여부 |
| `page-size` | integer | 10 | 페이지당 댓글 수 |
| `class` | string | '' | 추가 CSS 클래스 |

**주의**: 현재 테이블 구조상 답글(대댓글) 기능은 지원하지 않습니다.

## 고급 사용 예시

### 1. 제목 숨기고 커스텀 스타일링
```blade
<x-comments 
    commentable-type="Gallery" 
    commentable-id="{{ $gallery->id }}" 
    :show-title="false"
    class="custom-comments-style" />
```

### 2. 많은 댓글이 예상되는 경우
```blade
<x-comments 
    commentable-type="PopularPost" 
    commentable-id="{{ $post->id }}" 
    page-size="20" />
```

## 필요한 API 엔드포인트

댓글 시스템이 정상 작동하려면 다음 API 엔드포인트들이 구현되어야 합니다:

### 1. 댓글 목록 조회
```
GET /api/comments?commentable_type={type}&commentable_id={id}&page={page}&per_page={size}&sort={sort}

Response:
{
    "data": [
        {
            "id": 1,
            "content": "댓글 내용",
            "user": {
                "name": "사용자명"
            },
            "is_author": false,
            "is_admin": false,
            "can_edit": true,
            "can_delete": true,
            "is_liked": false,        // 좋아요 기능 구현시
            "likes_count": 5,         // 좋아요 기능 구현시
            "created_at": "2024-01-01T10:00:00Z"
        }
    ],
    "total": 100,
    "current_page": 1,
    "last_page": 10
}
```

### 2. 댓글 작성
```
POST /api/comments
{
    "commentable_type": "Article",
    "commentable_id": "123",
    "content": "댓글 내용"
}
```

### 3. 댓글 수정
```
PUT /api/comments/{commentId}
{
    "content": "수정된 내용"
}
```

### 4. 댓글 삭제
```
DELETE /api/comments/{commentId}
```

### 5. 댓글 좋아요 (선택사항)
```
POST /api/comments/{commentId}/like

Response:
{
    "is_liked": true,
    "likes_count": 6
}
```

## Laravel 백엔드 구현 예시

### Model Relationship 설정
```php
// Article.php
class Article extends Model
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Comment.php
class Comment extends Model
{
    protected $fillable = ['commentable_type', 'commentable_id', 'content', 'user_id', 'ip'];
    
    public function commentable()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Route 설정 예시
```php
// routes/api.php
Route::prefix('api')->group(function () {
    Route::get('comments', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('comments/{comment}/like', [CommentController::class, 'like']); // 선택사항
});
```

## 스타일링

댓글 컴포넌트는 Bootstrap 5.3을 기반으로 하며, 다음과 같은 CSS 클래스들을 사용합니다:

- `.comments-container` - 전체 컨테이너
- `.comment-item` - 개별 댓글
- `.comment-content` - 댓글 내용
- `.comment-actions` - 댓글 액션 버튼

커스텀 스타일링이 필요한 경우 `class` 속성을 통해 추가 클래스를 지정할 수 있습니다.

## 주요 변경사항

1. **답글 기능 제거**: 테이블에 `parent_id` 필드가 없어서 답글 기능을 비활성화
2. **Polymorphic 지원**: `commentable_type`과 `commentable_id` 활용
3. **API 경로 단순화**: `/api/comments` 형태로 단순화
4. **권한체크 제거**: 별도 권한체크 없이 바로 사용 가능
5. **DB 필드명 사용**: props명을 DB 필드명과 일치하게 변경

## 주의사항

1. **Alpine.js 필수**: 이 컴포넌트는 Alpine.js가 필요합니다.
2. **API 엔드포인트**: 위에 명시된 API 엔드포인트들이 구현되어야 합니다.
3. **XSS 방지**: 댓글 내용은 자동으로 HTML 이스케이프 처리됩니다.
4. **답글 미지원**: 현재 테이블 구조상 답글 기능은 지원하지 않습니다.
5. **모델명 처리**: 백엔드에서 모델명을 자동으로 Full namespace로 변환하여 처리합니다.

## 트러블슈팅

### Alpine 초기화 문제
현재 게시판 페이지에서는 Alpine 초기화가 지연되므로, 다음과 같은 과정을 거칩니다:
1. `window.deferAlpineInit = true`로 Alpine 시작 지연
2. DOM이 준비되면 `window.deferAlpineInit = false`로 변경
3. Alpine이 자동으로 시작됨

### 댓글이 로드되지 않는 경우
1. API 엔드포인트가 올바르게 구현되었는지 확인
2. `commentable_type`과 `commentable_id` 매개변수 확인
3. 네트워크 탭에서 API 호출 상태 확인
4. 브라우저 콘솔에서 에러 메시지 확인

### 댓글 작성이 안 되는 경우
1. 사용자 로그인 상태 확인
2. CSRF 토큰 설정 확인
3. 백엔드 validation 에러 확인
