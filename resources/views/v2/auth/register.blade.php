@extends('v2.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">회원가입</div>

                <div class="card-body"
                    x-data="register"
                >
                    <form @submit.prevent="submit">
                        <!-- 기본 정보 -->
                        <div class="mb-4">
                            <h5>기본 정보</h5>

                            <x-forms.input
                                type="text"
                                name="name"
                                label="이름"
                                placeholder="이름을 입력해주세요."
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="email"
                                name="email"
                                label="이메일"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="tel"
                                name="phone"
                                label="전화번호"
                                placeholder="- 없이 전화번호를 입력해 주세요"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="password"
                                name="password"
                                label="비밀번호"
                                placeholder="6~8자리 숫자,영어,특수문자 혼합"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="password"
                                name="password_confirmation"
                                label="비밀번호 확인"
                                placeholder="비밀번호를 다시 입력해주세요"
                                required
                            />
                        </div>

                        <!-- 딜러 정보 -->
                        <div class="mb-4">
                            <x-forms.checkbox
                                name="isDealer"
                                label="혹시 딜러이신가요? 네,딜러에요!"
                            />

                            <template x-if="form.isDealer">
                                <div>
                                    <p class="text-secondary opacity-50">딜러라면 추가 정보 입력이 필요해요</p>

                                    <x-forms.input
                                        type="text"
                                        name="dealerName"
                                        label="딜러 이름"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealerBirthDate"
                                        label="생년월일"
                                        placeholder="901230"
                                        maxlength="6"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="tel"
                                        name="dealerContact"
                                        label="연락처"
                                        placeholder="- 없이 전화번호를 입력해 주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="company"
                                        label="소속상사"
                                        placeholder="상사명(상사 정식 명칭)"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.textarea
                                        name="introduce"
                                        label="소개"
                                        rows="3"
                                        placeholder="소개를 입력해주세요."
                                        required
                                        :errors="true"
                                    />

                                    <!-- 회사 주소 -->
                                    <x-forms.address
                                        label="소속상사 주소"
                                        postName="company_post"
                                        addr1Name="company_addr1"
                                        addr2Name="company_addr2"
                                        postCodeId="company_post"
                                        required
                                        :errors="true"
                                    />

                                    <!-- 수신 주소 -->
                                    <x-forms.address
                                        label="인수차량 도착지 주소"
                                        postName="receive_post"
                                        addr1Name="receive_addr1"
                                        addr2Name="receive_addr2"
                                        postCodeId="receive_post"
                                        :errors="true"
                                    />

                                    <!-- 파일 업로드 -->
                                    <x-forms.file-upload
                                        label="사진 (본인 확인용)"
                                        name="file_user_photo"
                                        accept="image/*"
                                        :preview="true"
                                        previewUrl="photoUrl"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.file-upload
                                        label="사업자 등록증"
                                        name="file_user_biz"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.file-upload
                                        label="매도용인감정보"
                                        name="file_user_sign"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.file-upload
                                        label="매매업체 대표증 or 종사원증"
                                        name="file_user_cert"
                                        required
                                        :errors="true"
                                    />

                                    <!-- 약관 동의 -->
                                    <x-forms.checkbox
                                        name="isDealerApplyCheck"
                                        label="<a href='#' @click.prevent='openModal(\"privacy\")'>온라인자동차경매장 규약</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="isDealerApplyCheck1"
                                        label="<a href='#' @click.prevent='openModal(\"privacy\")'>주민등록번호(법인등록번호) 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="isDealerApplyCheck2"
                                        label="<a href='#' @click.prevent='openModal(\"privacy\")'>자동차관리사업등록번호 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="isDealerApplyCheck3"
                                        label="<a href='#' @click.prevent='openModal(\"privacy\")'>사업자정보 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />
                                </div>
                            </template>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-1"></span>
                                약관 동의 및 회원가입
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
