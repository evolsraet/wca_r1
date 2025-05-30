@extends('v2.layouts.app')

@php
    $form = null;
    $isUpdate = false;
    if (auth()->check()) {
        $form = [];
        $form['user'] = array_merge(auth()->user()->toArray(), [
                'phone' => auth()->user()->phone,
        ]);

        $media = auth()->user()->media->toArray();
        $files = [];
        foreach($media as $item) {
            $files[$item['collection_name']][] = $item;
        }
        dump($files);
        if( isset($form['user']['dealer']) ) {
            $form['dealer'] = auth()->user()->dealer;
            unset($form['user']['dealer']);
        } else {
            $form['isDealer'] = false;
        }

        $isUpdate = true;
        $form['isUpdate'] = true;
    }
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if ($isUpdate)
                        회원정보 수정
                    @else
                        회원가입
                    @endif
                </div>

                <div class="card-body"
                    x-data="register"
                    x-init="init({{ json_encode($form) }})"
                    >

                    <form @submit.prevent="submit" x-ref="formRef">

                        @if ($isUpdate)
                            <input type="hidden" name="user.id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="user.status" value="{{ auth()->user()->status }}">
                        @endif

                        <!-- 기본 정보 -->
                        <div class="mb-4">
                            <h5 class="text-primary">기본 정보</h5>

                            <x-forms.input
                                type="text"
                                name="user.name"
                                label="이름"
                                placeholder="이름을 입력해주세요."
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="email"
                                name="user.email"
                                label="이메일"
                                required
                                :errors="true"
                                :disabled="$isUpdate"
                            />

                            <x-forms.input
                                type="tel"
                                name="user.phone"
                                label="전화번호"
                                placeholder="- 없이 전화번호를 입력해 주세요"
                                required
                                :errors="true"
                            />

                            <x-forms.input
                                type="password"
                                name="user.password"
                                label="비밀번호"
                                placeholder="6~8자리 숫자,영어,특수문자 혼합"
                                :required="!$isUpdate"
                                :errors="true"
                            />

                            <x-forms.input
                                type="password"
                                name="user.password_confirmation"
                                label="비밀번호 확인"
                                placeholder="비밀번호를 다시 입력해주세요"
                                :required="!$isUpdate"
                            />
                        </div>

                        <!-- 딜러 정보 -->
                        <div class="mb-4">
                            @if (!$isUpdate)
                            <x-forms.checkbox
                                name="isDealer"
                                :errors="false"
                                label="혹시 딜러이신가요? 네,딜러에요!"
                            />
                            @endif

                            <template x-if="form.isDealer">
                                <div>
                                    @if ($isUpdate)
                                    <h5 class="text-primary">딜러 정보</h5>
                                    <hr>
                                    @else
                                    <p class="text-secondary opacity-50">딜러라면 추가 정보 입력이 필요해요</p>
                                    @endif
                                    <x-forms.input
                                        type="text"
                                        name="dealer.name"
                                        label="딜러 이름"
                                        placeholder="딜러 이름을 입력해주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealer.birthday"
                                        label="생년월일"
                                        placeholder="생년월일 6자리"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="tel"
                                        name="dealer.phone"
                                        label="연락처"
                                        placeholder="- 없이 전화번호를 입력해 주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealer.company"
                                        label="소속상사"
                                        placeholder="상사명(상사 정식 명칭)"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealer.company_duty"
                                        label="직책"
                                        placeholder="직책을 입력해주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.textarea
                                        name="dealer.introduce"
                                        label="딜러소개"
                                        rows="3"
                                        placeholder="소개를 입력해주세요."
                                        required
                                        :errors="true"
                                    />

                                    <!-- 회사 주소 -->
                                    <x-forms.address
                                        label="소속상사 주소"
                                        postName="dealer.company_post"
                                        addr1Name="dealer.company_addr1"
                                        addr2Name="dealer.company_addr2"
                                        postCodeId="dealer.company_post"
                                        required
                                        :errors="true"
                                    />

                                    <!-- 수신 주소 -->
                                    <x-forms.address
                                        label="인수차량 도착지 주소"
                                        postName="dealer.receive_post"
                                        addr1Name="dealer.receive_addr1"
                                        addr2Name="dealer.receive_addr2"
                                        postCodeId="dealer.receive_post"
                                        :errors="true"
                                    />

                                    <!-- 사업자 관련 번호 -->
                                    <x-forms.input
                                        type="text"
                                        name="dealer.business_registration_number"
                                        label="사업자등록번호"
                                        placeholder="사업자등록번호를 입력해주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealer.corporation_registration_number"
                                        label="법인등록번호"
                                        placeholder="법인등록번호를 입력해주세요"
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.input
                                        type="text"
                                        name="dealer.car_management_business_registration_number"
                                        label="자동차관리업 등록번호"
                                        placeholder="자동차관리업 등록번호를 입력해주세요"
                                        required
                                        :errors="true"
                                    />

                                    <!-- 파일 업로드 -->
                                    <x-forms.fileUpload
                                        label="사진 (본인 확인용)"
                                        name="file_user_photo"
                                        :preview="true"
                                        :errors="true"
                                        :existingFiles="$files['file_user_photo'] ?? null"
                                        :required="!$isUpdate"
                                    />

                                    <x-forms.fileUpload
                                        label="사업자 등록증"
                                        name="file_user_biz"
                                        :existingFiles="$files['file_user_biz'] ?? null"
                                        :errors="true"
                                        :required="!$isUpdate"
                                    />

                                    <x-forms.fileUpload
                                        label="매도용인감정보"
                                        name="file_user_sign"
                                        :errors="true"
                                        :existingFiles="$files['file_user_sign'] ?? null"
                                        :required="!$isUpdate"
                                    />

                                    <x-forms.fileUpload
                                        label="매매업체 대표증 or 종사원증"
                                        name="file_user_cert[]"
                                        :existingFiles="$files['file_user_cert'] ?? null"
                                        :errors="true"
                                        :required="!$isUpdate"
                                    />

                                    @php
                                        $privacy = [
                                            'title' => '온라인자동차경매장 규약',
                                            'content' => file_get_contents(resource_path('v2/docs/privacy.html')),
                                        ];
                                    @endphp

                                    @if (!$isUpdate)
                                    <!-- 약관 동의 -->
                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer1"
                                        label="<a href='#' @click.prevent='Alpine.store(`modal`).showHtml(`<h2>개인정보 수집 및 이용 동의</h2>

<p>당사는 회원가입, 상담, 서비스 신청 등을 위해 아래와 같은 개인정보를 수집하고 있습니다.</p>

<h3>1. 수집하는 개인정보 항목</h3>
<ul>
    <li>필수항목: 이름, 주민등록번호(법인등록번호), 로그인ID, 비밀번호, 자택 주소, 휴대전화번호, 이메일, 서비스 이용기록, 접속 로그, 쿠키, 접속 IP 정보</li>
    <li>선택항목: 자동차관리사업등록번호, 회사명, 부서, 직책, 팩스번호</li>
</ul>
`, {title: `{{ $privacy['title'] }}`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})'>{{ $privacy['title'] }}</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer2"
                                        label="<a href='#' @click.prevent='openModal(`privacy`)'>주민등록번호(법인등록번호) 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer3"
                                        label="<a href='#' @click.prevent='openModal(`privacy`)'>자동차관리사업등록번호 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />

                                    <x-forms.checkbox
                                        name="dealer.isCheckDealer4"
                                        label="<a href='#' @click.prevent='openModal(`privacy`)'>사업자정보 수집 동의</a>에 동의합니다."
                                        required
                                        :errors="true"
                                    />
                                    @endif
                                </div>
                            </template>
                        </div>

                        @if (!$isUpdate)
                        <x-forms.checkbox
                            name="user.isCheckPrivacy"
                            label="<a href='#' @click.prevent='openModal(`privacy`)'>개인정보처리방침</a>에 동의합니다."
                            required
                            :errors="true"
                        />
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-1"></span>
                                @if ($isUpdate)
                                    저장
                                @else
                                    약관 동의 및 회원가입
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
