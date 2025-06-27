<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Services\OwnershipService;
use Illuminate\Support\Facades\Log;
class CheckOwnershipStatus extends Command
{
    protected $signature = 'ownership:check';
    protected $description = '1시간마다 명의이전 상태 자동 확인';

    protected OwnershipService $ownershipService;

    public function __construct(OwnershipService $ownershipService)
    {
        parent::__construct();
        $this->ownershipService = $ownershipService;
    }

    public function handle(): int
    {
        $auctions = Auction::where('status', 'dlvr')
            ->where('taksong_status', 'done')
            ->where('has_uploaded_name_change_file', 1)
            ->whereNotNull('done_at')
            ->get();

        if ($auctions->isEmpty()) {
            Log::info('[명의이전] 확인 / 경매가 없습니다.', [
                'path'=> __FILE__,
                'line'=> __LINE__,
                'auctions' => $auctions
            ]);
            $this->info('명의이전 완료 확인할 경매가 없습니다.');
            return Command::SUCCESS;
        }

        foreach ($auctions as $auction) {
            $result = $this->ownershipService->checkOwnershipByApi($auction);
            Log::info("[명의이전] 경매 ID {$auction->id} / 확인결과 : " . ($result ? '완료' : '미완료'), [
                'path'=> __FILE__,
                'line'=> __LINE__,
                'result' => $result
            ]);
            $this->info("명의이전 경매 ID {$auction->id} 확인 결과: " . ($result ? '완료' : '미완료'));
        }

        return Command::SUCCESS;
    }
}