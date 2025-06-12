@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php
$carInfo = $_POST;
$user = auth()->user();
$user_phone = $user->phone;
@endphp
    
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="">
                <div class="card-body">
                    <h5 class="fw-bold">경매 할 차량을 등록해볼까요?</h5>

                    <form @submit.prevent="submit" x-data="apply" x-init="init({{ json_encode($carInfo) }})">
                        <div class="mb-4 mt-5   ">

                            <x-forms.input type="hidden" name="auction.auction_type" value="0" required :errors="true" />
                            
                            <x-forms.input type="hidden" name="auction.car_thumbnail" value="{{ $carInfo['car_thumbnail'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_price_now" value="{{ $carInfo['car_price_now'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_price_now_whole" value="{{ $carInfo['car_price_now_whole'] ?? '' }}" required :errors="true" />

                            <x-forms.input type="hidden" name="auction.car_maker" value="{{ $carInfo['car_maker'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_model" value="{{ $carInfo['car_model'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_model_sub" value="{{ $carInfo['car_model_sub'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_grade" value="{{ $carInfo['car_grade'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_grade_sub" value="{{ $carInfo['car_grade_sub'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_year" value="{{ $carInfo['car_year'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_first_reg_date" value="{{ $carInfo['car_first_reg_date'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_mission" value="{{ $carInfo['car_mission'] ?? '' }}" required :errors="true" />
                            <x-forms.input type="hidden" name="auction.car_fuel" value="{{ $carInfo['car_fuel'] ?? '' }}" required :errors="true" />

                            <label for="owner_name" class="form-label">
                                <span class="text-danger">*</span>
                                소유자
                            </label>

                            <div class="input-group">
                                <x-forms.input type="text"
                                        id="owner_name"
                                        class="form-control"
                                        name="auction.owner_name"
                                        placeholder="소유자 이름을 입력해주세요."
                                        required
                                        readonly
                                        flex="true"
                                    />

                                <button type="button" id="ownerAuthBtn" class="btn btn-secondary fs-6 border-0" :disabled="checkBusiness" style="height: 38px;">
                                    본인인증
                                </button>
                            </div>

                            <p class="text-danger">
                            ※ 차량 소유자 확인을 위해 본인 인증 버튼을 클릭해주세요.
                            </p>

                            <x-forms.input
                                type="text"
                                name="auction.car_no"
                                label="차량 번호"
                                placeholder="차량번호를 입력해주세요."
                                required
                                :errors="true"
                                readonly
                            />

                            <label>
                                <span class="text-danger">*</span>
                                주소
                            </label>
                            <p class="text-danger">차량 실주소지를 입력해주세요. (차량 진단 가능 지역)</p>
                            <x-forms.address
                                postName="auction.addr_post"
                                addr1Name="auction.addr1"
                                addr2Name="auction.addr2"
                                postCodeId="auction.addr_post"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="text"
                                name="auction.region"
                                label="지역"
                                placeholder="지역을 입력해주세요."
                                required
                                :errors="true"
                            />


                            <label>
                                <span class="text-danger">*</span>
                                차량상태 입력
                            </label>


                            <x-forms.input
                                type="hidden"
                                name="auction.car_status"
                                :errors="true"
                            />

                            <!-- Alpine.js 데이터 + 체크박스 -->
                            <div 
                                x-data="{
                                    selectedStatus: [],
                                    updateStatus() {
                                        form.auction.car_status = JSON.stringify(this.selectedStatus);
                                    }
                                }" 
                                x-init="updateStatus()"
                            >
                                <template x-for="label in ['침수', '화재', '엔진고장', '변속기고장']" :key="label">
                                    <label class="me-3">
                                        <input 
                                            type="checkbox" 
                                            :value="label" 
                                            x-model="selectedStatus" 
                                            @change="updateStatus()"
                                        >
                                        <span x-text="label"></span>
                                    </label>
                                </template>

                            </div>
                           
                            
                            <div class="input-unit-group w-100 mb-2 mt-3 d-flex">
                                <x-forms.input
                                    type="text"
                                    name="auction.car_km"
                                    label="주행거리"
                                    flex="true"
                                    placeholder="주행거리를 입력해주세요."
                                    required
                                    :errors="true"
                                    readonly
                                />

                                <span class="unit-label">Km</span>
                            </div>


                            <x-forms.textarea
                                name="auction.memo"
                                label=""
                                rows="3"
                                placeholder="ex) 외관손상, 차량내부손상, 사고유무등
주의) 주요결함 미고지시 추후 환불등 불이익이 있을 수 있습니다."
                                :errors="true"
                            />

                            <h5 class="fw-bold">은행/진단 정보</h5>


                            <div id="bankSelectorBox">
                            <x-forms.input
                                name="auction.bank"
                                label="은행 (매매대금 입금 받을 계좌)"
                                placeholder="예: 농협"
                                required
                                :errors="true"
                            />
                            </div>

                            <x-forms.input
                                name="auction.account"
                                placeholder="계좌번호를 입력해주세요"
                                required
                                :errors="true"
                            />
                            <p class="text-danger small mt-1">※ 계좌는 차량 소유주의 계좌번호만 입력가능 합니다.</p>

                            <x-forms.input
                                name="auction.diag_first_at"
                                label="진단희망 날짜 및 시간"
                                placeholder="진단희망일1"
                                type="datetime-local"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                name="auction.diag_second_at"
                                placeholder="진단희망일2"
                                type="datetime-local"
                                required
                                :errors="true"
                            />
                            <p class="text-danger small mt-1">※ 진단희망일은 신청일로부터 2일 후부터 입력 가능합니다. (진단시간 오전9시 ~ 오후6시)</p>

                            
                            <!-- 첨부파일 -->
                            <div class="mb-4 mt-4">
                                <h5 class="fw-bold">첨부파일</h5>

                                <div class="mb-4">
                                <x-forms.fileUpload
                                    label="자동차등록증"
                                    name="file_auction_car_license"
                                    :errors="true"
                                    :preview="false"
                                />
                                </div>

                                <div class="mt-4 d-flex justify-content-between align-items-center mb-4">
                                    <p class="mb-0 fw-bold">본인 소유 차량이 아닐 경우,<br>위임장 또는 소유자 인감 증명서가 필요해요</p>
                                    <button type="button" class="btn btn-success text-white fs-6 border-0" id="delegationFormBtn">위임장 양식</button>
                                </div>

                                <div class="mt-4">
                                <x-forms.fileUpload
                                    name="file_auction_proxy"
                                    :errors="true"
                                    :preview="false"
                                />
                                </div>
                            </div>


                            <!-- 법인/사업자 차량 체크 -->
                            <div class="mb-4">
                                <x-forms.checkbox
                                    name="auction.is_business_owner"
                                    label="법인 / 사업자차량"
                                    value="1"
                                    wrapperClass="rounded-check"
                                    :errors="true"
                                />

                                <p class="text-danger small mt-1">
                                    ※ 법인 및 사업자 명의로 등록된 차량의 경우 체크해 주세요.
                                </p>
                            </div>

                            <!-- 경매 신청 버튼 -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-3 shadow-sm btn-lg border-0 fs-6">
                                    경매 신청하기
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.carInfo = @json($carInfo);
    window.user = @json($user);
    window.user_phone = "{{ $user_phone }}";

    // 은행 선택 모달
    document.getElementById('bankSelectorBox').addEventListener('click', () => {
        Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/bankSelectorModal', {
            id: 'bankSelectorModal',
            title: '은행 선택',
            showFooter: false
        });
    });

    // 본인인증 모달
    document.getElementById('ownerAuthBtn').addEventListener('click', () => {
        Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/ownerAuthModal', {
            id: 'ownerAuthModal',
            title: '본인인증',
            size: 'custom-size',
            showFooter: false,
            onResult: (result) => {
                // console.log('result?', result);

                if(result.resIndividualBusinessYN === 'Y'){
                    document.getElementById('ownerAuthBtn').disabled = true;
                }else{
                    document.getElementById('ownerAuthBtn').disabled = false;
                }
            }
        });
    });

    // 위임장 모달
    document.getElementById('delegationFormBtn').addEventListener('click', () => {
        Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/delegationFormModal', {
            id: 'delegationFormModal',
            title: '위임장 양식',
            size: 'modal-lg',
            showFooter: false,
            data: {
                carInfo: window.carInfo,
                user: window.user,
                user_phone: window.user_phone,
                auction: window.apply?.form?.auction ?? {}
            }
        });
    });
</script>

@endsection