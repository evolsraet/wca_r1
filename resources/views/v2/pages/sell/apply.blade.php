@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php

@endphp

<div class="container py-5 form-custom">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="">
                <div class="card-body">
                    <h5 class="fw-bold">경매 할 차량을 등록해볼까요?</h5>

                    <form @submit.prevent="submit">
                        <div class="mb-4 mt-5   ">
                            <x-forms.input
                                type="text"
                                name="user.name"
                                label="소유자"
                                placeholder="소유자 이름을 입력해주세요."
                                required
                                :errors="true"
                            />
                            <p class="text-danger">차량 소유자 확인을 위해 본인 인증 버튼을 클릭해주세요.</p>

                            <x-forms.input
                                type="text"
                                name="user.name"
                                label="차량 번호"
                                placeholder="차량번호를 입력해주세요."
                                required
                                :errors="true"
                            />

                            <label>주소</label>
                            <p class="text-danger">차량 실주소지를 입력해주세요. (차량 진단 가능 지역)</p>
                            <x-forms.address
                                postName="dealer.company_post"
                                addr1Name="dealer.company_addr1"
                                addr2Name="dealer.company_addr2"
                                postCodeId="dealer.company_post"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="text"
                                name="user.name"
                                label="지역"
                                placeholder="지역을 입력해주세요."
                                required
                                :errors="true"
                            />


                            <label>차량상태 입력</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer1"
                                        label="침수"
                                        :errors="true"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer1"
                                        label="화재"
                                        :errors="true"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer1"
                                        label="엔진고장"
                                        :errors="true"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer1"
                                        label="변속기고장"
                                        :errors="true"
                                    />
                                </div>
                            </div>
                            
                            <div class="input-unit-group w-100 mb-2">
                                <input type="text" class="form-control" placeholder="주행거리">
                                <span class="unit-label">Km</span>
                            </div>

                            <x-forms.textarea
                                name="dealer.introduce"
                                label=""
                                rows="3"
                                placeholder="ex) 외관손상, 차량내부손상, 사고유무등 \n 주의) 주요결함 미고지시 추후 환불등 불이익이 있을 수 있습니다."
                                required
                                :errors="true"
                            />

                            <h5 class="fw-bold">은행 진단 정보</h5>


                            <x-forms.input
                                name="bank.name"
                                label="은행 (매매대금 입금 받을 계좌)"
                                placeholder="예: 농협"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                name="bank.number"
                                label="계좌번호"
                                placeholder="계좌번호를 입력해주세요"
                                required
                                :errors="true"
                            />
                            <p class="text-danger small mt-1">※ 계좌는 차량 소유주의 계좌번호만 입력가능 합니다.</p>

                            <x-forms.input
                                name="hope_diag_date1"
                                label="진단희망일1"
                                placeholder="진단희망일1"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                name="hope_diag_date2"
                                label="진단희망일2"
                                placeholder="진단희망일2"
                                required
                                :errors="true"
                            />
                            <p class="text-danger small mt-1">※ 진단희망일은 신청일로부터 2일 후부터 입력 가능합니다. (진단시간 오전9시 ~ 오후6시)</p>

                            
                            <!-- 첨부파일 -->
                            <div class="mb-4">
                                <h5 class="fw-bold">첨부파일</h5>

                                <x-forms.fileUpload
                                    label="자동차등록증"
                                    name="file_car_reg"
                                    :errors="true"
                                    :preview="true"
                                />

                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <p class="mb-0 fw-bold">본인 소유 차량이 아닐 경우,<br>위임장 또는 소유자 인감 증명서가 필요해요</p>
                                    <a href="#" class="btn btn-success text-white">위임장 양식</a>
                                </div>

                                <x-forms.fileUpload
                                    label="위임장 또는 인감 증명서"
                                    name="file_proxy"
                                    :errors="true"
                                    :preview="true"
                                />
                            </div>


                            <!-- 법인/사업자 차량 체크 -->
                            <div class="mb-4">
                                <x-forms.checkbox
                                    name="is_corporate_car"
                                    label="법인 / 사업자차량"
                                    :errors="true"
                                />

                                <p class="text-danger small mt-1">
                                    ※ 법인 및 사업자 명의로 등록된 차량의 경우 체크해 주세요.
                                </p>
                            </div>

                            <!-- 경매 신청 버튼 -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-3 shadow-sm border-0 ">
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

@endsection