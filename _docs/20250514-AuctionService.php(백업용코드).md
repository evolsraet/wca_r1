    // 요청 중간 처리: 결과 필터링 및 데이터 검증   

    public function middleProcess($method, $request, $auction, $id = null)
    {
        // Log::info('경매 상태 업데이트 모드?', ['method' => $method]);

        // $user = User::find($auction->user_id);

        switch ($method) {
            case 'index':
            case 'show':
                $this->filterResultsBasedOnRole($auction);
                break;
            case 'store':
                // 본인인증 검증 추가
                $this->validateAndSetAuctionData($request, $auction);
                break;
            case 'update':

                Log::info('경매 상태 업데이트 모드??', ['method' => $auction, 'mode' => $request->mode]);

                // 상태변경
                // request()->mode 가 있을 경우 그대로 두고, 없으면서 $acution->status 가 변경됬을 경우 그 request()->mode 에 $acution->status 대입
                if (!request()->has('mode') && $auction->isDirty('status')) {
                    request()->merge(['mode' => $request->status]);
                }

                $this->modifyOnlyMe($auction, request()->mode == 'dealerInfo');

                // TODO: 딜러정보가 탁송 입력되고, 고객 탁송필요 정보가 모두 입력되면 dlvr 로 변경해야한다

                // 모드별 분기
                if (request()->has('mode')) {
                    switch (request()->mode) {
                        case 'dealerInfo':
                            // 허용된 필드 목록
                            $allowedFields = ['dest_addr_post', 'dest_addr1', 'dest_addr2'];

                            // 변경된 속성만 가져오기
                            $dirtyAttributes = $auction->getDirty();

                            // 허용되지 않은 필드만 원래 값으로 되돌림
                            foreach ($dirtyAttributes as $key => $value) {
                                if (!in_array($key, $allowedFields)) {
                                    $auction->$key = $auction->getOriginal($key);
                                }
                            }

                            // Log::info('딜러정보 모드', ['method' => $auction]);
                            $data = [];
                            $data['id'] = $auction->id;
                            $data['car_no'] = $auction->car_no; // 차량번호  
                            $data['car_model'] = $auction->car_model; // 차량모델
                            $data['mobile'] = User::find($auction->user_id)->phone; // 출발지 전화번호
                            $data['dest_mobile'] = User::find($auction->bids[0]->user_id)->phone; // 출발지 전화번호
                            $data['start_addr'] = $auction->addr1 . ' ' . $auction->addr2; // 주소
                            $data['dest_addr'] = $auction->dest_addr1 . ' ' . $auction->dest_addr2; // 주소
                            $data['user_id'] = $auction->user_id; // 사용자 아이디
                            $data['bid_user_id'] = $auction->bids[0]->user_id; // 입찰자 아이디
                            $data['user_email'] = User::find($auction->user_id)->email; // 사용자 이메일
                            $data['bid_user_email'] = User::find($auction->bids[0]->user_id)->email; // 입찰자 이메일
                            $data['taksong_wish_at'] = $auction->taksong_wish_at; // 탁송 날짜

                            // 탁송 처리
                            Log::info('[탁송 처리] 딜러정보 모드', ['method' => $data]);
                            TaksongAddJob::dispatch($auction, $data);

                            break;
                        case 'reauction':
                            // 재경매 : 옥션변수가 오고, 재옥션 상태가 아니고, auction->status 가 wait 일 경우,  상태변경
                            if (!$auction->is_reauction && $auction->status == 'wait') {
                                $auction->status = 'ing';
                                $auction->is_reauction = true;
                                $auction->final_at = now()->addDays(config('days.reauction_day'));

                                Log::info('재경매 모드', ['method' => $auction]);

                                $bids = Bid::where('auction_id', $auction->id)->get();
                                foreach($bids as $bid){
                                    Log::info('재경매 모드 입찰자 알림', ['mode' => 'reauction','method' => $bid->user_id]);
                                    AuctionBidStatusJob::dispatch($bid->user_id, 'reauction', $auction->id, $bid->user_id,'');
                                                                    

                                }
                                AuctionBidStatusJob::dispatch($auction->user_id, 'reauction', $auction->id, $bid->user_id,'');



                            } else {
                                throw new \Exception('재경매변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'diag':
                            // 진단으로 변경
                            if (!$auction->is_reauction && $auction->status == 'ask') {
                                $auction->status = 'diag';
                                $auction->final_at = now()->addDays(config('days.auction_day'));


                                Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                                AuctionDiagJob::dispatch($auction->user_id, $auction);

                            } else {
                                throw new \Exception('진단변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'dlvr':
                            // 배송으로 변경
                            if ($auction->status == 'chosen') {
                                $auction->status = 'dlvr';
                            } else {
                                throw new \Exception('배송변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'done':
                            // 완료으로 변경
                            if (in_array($auction->status, ['dlvr', 'chosen'])) {
                                $auction->status = 'done';


                                Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                                $bids = Bid::find($auction->bid_id);

                                AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
                                AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');


                            } else {
                                throw new \Exception('배송변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'cancel':
                            // 취소으로 변경
                            if (!in_array($auction->status, ['done', 'dlvr'])) {
                                $auction->status = 'cancel';


                                Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                                AuctionCancelJob::dispatch($auction->user_id, $auction->id);

                                $bids = Bid::where('id', $auction->id)->get();
                                foreach($bids as $bid){
                                    // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                                    AuctionCancelJob::dispatch($bid->user_id, $auction->id);
                                }

                            } else {
                                throw new \Exception('취소변경 가능상태가 아닙니다.');
                            }
                            break; 

                        case 'chosen':
                            // $auction->status = 'chosen';

                            Log::info('유저가 경매를 선택 했을때1 ', ['method' => $auction]);

                            

                            break;

                        case 'requested':
                            // 요청으로 변경
                            Log::info('경매 상태 업데이트 요청 모드', ['method' => $auction]);
                            break;

                        case 'uploaded':
                            // 업로드로 변경
                            Log::info('경매 상태 업데이트 업로드 모드', ['method' => $auction]);
                            break;

                        case 'confirmed':
                            // 확정으로 변경
                            Log::info('경매 상태 업데이트 확정 모드', ['method' => $auction]);
                            break;

                        // case 'ing':
                        //     $auction->final_at = now()->addDays(env('AUCTION_DAY'));
                            
                        //     break;
                    }
                }

                $bids = Bid::find($auction->bid_id);

                // 취소시 알림
                if($auction->status == 'cancel'){

                    Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                    AuctionCancelJob::dispatch($auction->user_id, $auction->id);

                    $bids = Bid::where('auction_id', $auction->id)->get();
                    foreach($bids as $bid){
                        // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                        AuctionCancelJob::dispatch($bid->user_id, $auction->id);
                    }
                }   

                // 입찰자에게 알림
                if($auction->status == 'dlvr'){


                    if($auction->is_deposit == 'totalDeposit'){
                        Log::info('경매 상태 업데이트 입금완료 모드', ['method' => $auction]);
                        // 고객 / 딜러 에게 알림 
                        AuctionTotalDepositJob::dispatch($auction->user_id, $auction, 'user');
                        AuctionTotalDepositJob::dispatch($bids->user_id, $auction, 'dealer');

                    }else {
                        Log::info('경매 상태 업데이트 입찰선택 모드', ['method' => $auction]);

                        if($auction->bids){
                            Log::info('경매 상태 업데이트 입찰선택 모드 입찰자 알림' . $auction->bids->first()->user_id, ['method' => '']);
                            // AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
                        }
    
                        AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user');
                    }

                    
                }   

                // 입찰자에게 알림
                if($auction->status == 'wait'){
                    Log::info('경매 상태 업데이트 선택대기 모드1', ['method' => $auction]);

                    AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id, '', '');
                }
                
                // 경매완료시 전체 입찰자에게 알림
                if($auction->status == 'done'){
                    
                    if($auction->is_deposit == 'totalAfterFee'){

                        Log::info('경매 상태 업데이트 수수료 입금완료 모드', ['method' => $auction]);
                        // 딜러에게 알림 
                        AuctionTotalAfterFeeJob::dispatch($bids->user_id, $auction);

                    }else{
                        Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                        $bids = Bid::find($auction->bid_id);
    
                        AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
                        AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');
                    }

                }

                // 진단대기중 알림 
                if($auction->status == 'diag'){
                    Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                    AuctionDiagJob::dispatch($auction->user_id, $auction);
                }

                // 경매진행중 알림
                if($auction->status == 'ing'){

                    // 진단중인데 진단도 안했거나, 아직 완료 안됐을 때 상태 변경 막기
                    if (empty($auction->diag_check_at)) {
                        throw new \Exception('진단이 완료되지 않았습니다. 상태 변경 불가.', 422);
                    }

                    $auction->final_at = now()->addDays(config('days.auction_day'));
                    if(!$auction->bid_id){
                        Log::info('경매 상태 업데이트 경매진행중 모드', ['method' => $auction]);

                        AuctionIngJob::dispatch($auction->user_id, $auction->id, $auction->final_at);

                    }
                }


                if($auction->status == 'chosen'){
                    Log::info('유저가 경매를 선택 했을때211', ['method' => $auction, 'requestMode' => request()->mode]);

                    // AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user');    
                    if(!request()->mode){
                        AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
                    }

                }


                break;
            case 'destroy':
                $this->modifyOnlyMe($auction);
                break;
        }
    }

