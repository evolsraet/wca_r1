<?php

namespace App\Models;

use App\Models\Board;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Log;

class Article extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    use ModelTrait;


    // 업로드 가능한 파일들
    public $files = [
        'board_attach' => '첨부파일',
        // 'file_user_owner'  => '위임장/소유자 인감증명서',
    ];

    // 한개만 저장되고 새로 업로드시 삭제될 파일들
    public $files_one = [];

    public $searchable = ['title', 'content', 'extra1', 'extra2'];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc');
    }

    // 파일
    /* 
    public function registerMediaCollections(): void
    {
        foreach ($this->files as $file => $name) {
            $mediaCollection = $this->addMediaCollection($file)
                ->useFallbackUrl('/images/placeholder.jpg')
                ->useFallbackPath(public_path('/images/placeholder.jpg'));

            // $files_one 배열에 있는 항목에만 singleFile() 적용
            if (array_key_exists($file, $this->files_one)) {
                $mediaCollection->singleFile();
            }
        }
    }
    */

    public function registerMediaCollections(): void
    {
        foreach ($this->files as $file => $label) {
            $collection = $this->addMediaCollection($file)
                ->useFallbackUrl('/images/placeholder.jpg')
                ->useFallbackPath(public_path('/images/placeholder.jpg'));

            // ✅ 단일 업로드만 지정된 항목에 singleFile() 적용
            if (in_array($file, $this->files_one, true)) {
                $collection->singleFile();
            }
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if (env('RESIZE_IMAGE') === true) {
            $this->addMediaConversion('resized-image')
                ->width(env('IMAGE_WIDTH', 300))
                ->height(env('IMAGE_HEIGHT', 300));
        }
    }

    /**
     * 첨부파일 업로드를 모델 레벨에서 안전하게 처리
     *
     * @param mixed $files 업로드된 파일 또는 파일 배열
     * @param string $collection 미디어 컬렉션명 (기본값: board_attach)
     */
    public function attachUploadedFiles($files, $collection = 'board_attach')
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $index => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                Log::info('[Article 모델] addMedia - 파일 처리 중', [
                    'originalName' => $file->getClientOriginalName(),
                    'mimeType' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);

                $this->addMedia($file->getRealPath())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection($collection);
            } else {
                Log::warning('[Article 모델] 유효하지 않은 파일', [
                    'file_key' => "$collection.$index",
                    '파일객체' => $file
                ]);
            }
        }
    }
}
