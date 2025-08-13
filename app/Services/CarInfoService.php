<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\NiceDnrData;

class CarInfoService
{
    public function getCachedCarInfoForUser(): ?array
    {
        $user = Auth::user();

        if (!$user) return null;

        $this->migrateSessionHistoryToUserCache();

        $userCacheKey = "carInfo.user.{$user->id}";

        if (!Cache::has($userCacheKey)) return null;

        $queryInfo = Cache::get($userCacheKey); // ['owner' => '이름', 'no' => '차량번호']
        $carInfoCacheKey = "carInfo." . $queryInfo['owner'] . $queryInfo['no'];

        if (!Cache::has($carInfoCacheKey)) return null;

        $carInfo = Cache::get($carInfoCacheKey);

        return (!empty($carInfo['owner']) && !empty($carInfo['no'])) ? $carInfo : null;
    }

    public function storeUserQueryHistory(string $owner, string $no, string $gradeId = ''): void
    {
        $user = Auth::user();
        
        Log::info('[CarInfo] 히스토리 저장 시작', [
            'owner' => $owner,
            'no' => $no,
            'user_id' => $user ? $user->id : null,
            'is_logged_in' => $user ? true : false
        ]);
        
        if ($user) {
            // 로그인된 사용자: Cache에 저장
            $key = "carInfo.user.{$user->id}";
            
            Log::info('[CarInfo] 로그인된 사용자 - Cache 저장 처리', [
                'user_id' => $user->id,
                'cache_key' => $key
            ]);
            
            // 기존 데이터 불러오기 (없으면 빈 배열)
            $history = Cache::get($key, []);
            
            Log::info('[CarInfo] 기존 Cache 히스토리 조회', [
                'existing_count' => count($history),
                'existing_history' => $history
            ]);
            
            // 중복 제거 (같은 owner & no 조합이 있는지 확인)
            $exists = collect($history)->first(fn($item) =>
                isset($item['owner'], $item['no'], $item['gradeId']) &&
                $item['owner'] === $owner &&
                $item['no'] === $no &&
                $item['gradeId'] === $gradeId
            );
            
            if (!$exists) {
                $newItem = [
                    'owner' => $owner,
                    'no' => $no,
                    'gradeId' => $gradeId,
                    'created_at' => now()->toISOString(),
                ];
                $history[] = $newItem;
                
                Log::info('[CarInfo] 새로운 항목 Cache에 추가', [
                    'new_item' => $newItem,
                    'total_count' => count($history)
                ]);
            } else {
                Log::info('[CarInfo] 중복 항목 발견 - Cache 저장 건너뜀', [
                    'existing_item' => $exists
                ]);
            }
            
            Cache::put($key, $history, now()->addDays(config('days.car_info_cache_ttl')));
            
            Log::info('[CarInfo] Cache 저장 완료', [
                'final_count' => count($history),
                'ttl_days' => config('days.car_info_cache_ttl')
            ]);
            
        } else {
            // 로그인 전: 세션에 저장
            $sessionKey = 'guest_car_info_history';
            
            Log::info('[CarInfo] 미로그인 사용자 - 세션 저장 처리', [
                'session_key' => $sessionKey
            ]);
            
            // 기존 세션 데이터 가져오기
            $history = session($sessionKey, []);
            
            Log::info('[CarInfo] 기존 세션 히스토리 조회', [
                'existing_count' => count($history),
                'existing_history' => $history
            ]);
            
            // 중복 제거
            $exists = collect($history)->first(fn($item) =>
                isset($item['owner'], $item['no'], $item['gradeId']) &&
                $item['owner'] === $owner &&
                $item['no'] === $no &&
                $item['gradeId'] === $gradeId
            );
            
            if (!$exists) {
                $newItem = [
                    'owner' => $owner,
                    'no' => $no,
                    'gradeId' => $gradeId,
                    'created_at' => now()->toISOString(),
                ];
                $history[] = $newItem;
                
                Log::info('[CarInfo] 새로운 항목 세션에 추가', [
                    'new_item' => $newItem,
                    'count_before_limit' => count($history)
                ]);
                
                // 최대 개수 제한 (예: 10개)
                if (count($history) > 10) {
                    $history = array_slice($history, -10);
                    Log::info('[CarInfo] 세션 히스토리 개수 제한 적용', [
                        'limit' => 10,
                        'final_count' => count($history)
                    ]);
                }
            } else {
                Log::info('[CarInfo] 중복 항목 발견 - 세션 저장 건너뜀', [
                    'existing_item' => $exists
                ]);
            }
            
            // 세션에 저장
            session([$sessionKey => $history]);
            
            Log::info('[CarInfo] 세션 저장 완료', [
                'final_count' => count($history),
                'session_data' => $history
            ]);
        }
        
        Log::info('[CarInfo] 히스토리 저장 완료');
    }

    /**
     * 로그인 후 세션의 히스토리를 사용자 Cache로 마이그레이션
     */
    public function migrateSessionHistoryToUserCache(): void
    {
        $user = Auth::user();
        if (!$user) {
            Log::info('[CarInfo] 마이그레이션 중단 - 사용자 미로그인');
            return;
        }
        
        Log::info('[CarInfo] 세션 히스토리 마이그레이션 시작', [
            'user_id' => $user->id
        ]);
        
        $sessionKey = 'guest_car_info_history';
        $sessionHistory = session($sessionKey, []);
        
        Log::info('[CarInfo] 세션 히스토리 조회', [
            'session_key' => $sessionKey,
            'session_count' => count($sessionHistory),
            'session_data' => $sessionHistory
        ]);
        
        if (empty($sessionHistory)) {
            Log::info('[CarInfo] 마이그레이션 중단 - 세션 히스토리 없음');
            return;
        }
        
        $userKey = "carInfo.user.{$user->id}";
        $userHistory = Cache::get($userKey, []);
        
        Log::info('[CarInfo] 기존 사용자 Cache 조회', [
            'cache_key' => $userKey,
            'cache_count' => count($userHistory),
            'cache_data' => $userHistory
        ]);
        
        $migratedCount = 0;
        $duplicateCount = 0;
        
        // 세션 히스토리를 사용자 Cache에 병합 (중복 제거)
        foreach ($sessionHistory as $sessionItem) {
            $exists = collect($userHistory)->first(fn($item) =>
                isset($item['owner'], $item['no']) &&
                $item['owner'] === $sessionItem['owner'] &&
                $item['no'] === $sessionItem['no']
            );
            
            if (!$exists) {
                $userHistory[] = $sessionItem;
                $migratedCount++;
                
                Log::info('[CarInfo] 세션 항목 Cache로 마이그레이션', [
                    'migrated_item' => $sessionItem,
                    'migrated_count' => $migratedCount
                ]);
            } else {
                $duplicateCount++;
                
                Log::info('[CarInfo] 중복 항목 발견 - 마이그레이션 건너뜀', [
                    'duplicate_item' => $sessionItem,
                    'existing_item' => $exists
                ]);
            }
        }
        
        // Cache에 저장
        Cache::put($userKey, $userHistory, now()->addDays(config('days.car_info_cache_ttl')));
        
        Log::info('[CarInfo] 사용자 Cache 업데이트 완료', [
            'final_count' => count($userHistory),
            'migrated_count' => $migratedCount,
            'duplicate_count' => $duplicateCount
        ]);
        
        // 세션에서 제거
        session()->forget($sessionKey);
        
        Log::info('[CarInfo] 세션 히스토리 삭제 완료', [
            'session_key' => $sessionKey
        ]);
        
        Log::info('[CarInfo] 마이그레이션 완료', [
            'user_id' => $user->id,
            'total_migrated' => $migratedCount,
            'total_duplicates' => $duplicateCount
        ]);
    }
}