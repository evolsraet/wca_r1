@extends('v2.layouts.app')

@section('content')

<div class="container py-5" x-data="auctionRegisterForm">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="">
                <div class="card-body">
                    <h5 class="fw-bold">공매 할 차량을 등록해볼까요?</h5>

                    <form @submit.prevent="onSubmit">
                        <div class="mb-4 mt-5   ">                            
                            
                            <label for="owner_name" class="form-label">
                                <span class="text-danger">*</span>
                                회사명
                            </label>

                            <x-forms.input type="text"
                                id="company_name"
                                class="form-control"
                                name="auction.company_name"
                                placeholder="회사명을 입력해주세요."
                                required
                                flex="true"
                            />


                            <div @click="showBankSelector">
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
                                name="auction.account_name"
                                label="계좌소유자명"
                                placeholder="계좌소유자명을 입력해주세요."
                                type="text"
                                required
                                :errors="true"
                            />
                            
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


                                <div class="mt-4">
                                <x-forms.fileUpload
                                    label="공매 엑셀파일 일괄등록"
                                    name="file_auction_excel"
                                    :errors="true"
                                    :preview="false"
                                />
                                </div>

                                <div class="mt-4 d-flex justify-content-between align-items-center mb-2">
                                    <button type="button" class="btn btn-success text-white fs-6 btn-lg border-0 w-100" @click="exportExcel">엑셀파일 형식 참고</button>
                                </div>

                                <button type="submit" 
                                    class="btn btn-primary rounded-3 shadow-sm btn-lg border-0 fs-6 w-100"
                                    :disabled="isLoading"
                                    x-html="isLoading ? '<span class=\'spinner-border spinner-border-sm me-2\'></span>처리 중...' : '공매 신청하기'">
                                </button>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

