<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;
use App\Models\Auction;

class MediaService
{
    public function deleteMediaByUuids(array $uuids)
    {
        $medias = Media::whereIn('uuid', $uuids)->get();

        foreach ($medias as $media) {
            // 모델 인스턴스 로드
            $model = $media->model_type::find($media->model_id);
            // print_r($media);
            // die();

            // 권한검사
            switch ($media->model_type) {
                case 'App\\Models\\User':
                    // throw new \Exception(
                    //     print_r(
                    //         [
                    //             Auth::user()->id,
                    //             $media->model_id,
                    //             Auth::user()->hasPermissionTo('act.admin')
                    //         ],
                    //         true
                    //     )
                    // );
                    if (Auth::user()->id != $media->model_id && !Auth::user()->hasPermissionTo('act.admin')) {
                        throw new \Exception("미디어를 삭제할 권한이 없습니다. {$media->uuid}");
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }

        $count = 0;
        // 모든 권한 검사 후 삭제
        foreach ($medias as $media) {
            $media->delete();
            $count++;
        }

        return $count;
    }

    public function uploadFile($file, Auction $auction)
    {
        // 파일 업로드 로직
        $media = $auction->addMedia($file)
                         ->toMediaCollection('file_auction_owner');

        return $media;
    }
}
