<?php

namespace App\Models;

use App\Models\Board;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // 파일
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

    public function registerMediaConversions(Media $media = null): void
    {
        if (env('RESIZE_IMAGE') === true) {
            $this->addMediaConversion('resized-image')
                ->width(env('IMAGE_WIDTH', 300))
                ->height(env('IMAGE_HEIGHT', 300));
        }
    }
}
