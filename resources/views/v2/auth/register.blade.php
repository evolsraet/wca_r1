@extends('v2.layouts.app')

@php
    $form = null;
    $isUpdate = false;
    $form['isDealer'] = request()->get('isDealer') ? true : false;

    if (auth()->check()) {
        $form = [];
        $form['user'] = array_merge(auth()->user()->toArray(), [
                'phone' => auth()->user()->phone,
        ]);

        // files
        $media = auth()->user()->media->toArray();
        $files = [];
        foreach($media as $item) {
            $files[$item['collection_name']][] = $item;
        }

        if( isset($form['user']['dealer']) ) {
            $form['dealer'] = auth()->user()->dealer;
            $form['isDealer'] = true;
            unset($form['user']['dealer']);
        } else {
            $form['isDealer'] = false;
        }

        $isUpdate = true;
        $form['isUpdate'] = true;
    }

    $container = false;

@endphp

@section('content')

<div class="py-3" x-data="register"
x-init="init({{ json_encode($form) }})">

    <div class="position-fixed top-0 left-0 w-100 h-100" x-show="!window.userId">
        <div class="d-none d-md-flex flex-column align-items-start justify-content-center text-start" style="height: 100vh;">
            <div class="mb-4 px-5">
                <h4 class="fw-bold text-secondary mb-2">내 차 판매에</h4>
                <h4 class="fw-bold">
                    <span class="text-danger">28%</span>
                    <span class="text-secondary"> 정도 가까워지는 중...</span>
                </h4>
            </div>
        
            <div class="position-relative">

                <video width="80%" autoplay="" loop="" muted="" playsinline="" preload="auto">
                    <source src="{{ asset('images/video/register_vi.mp4') }}" type="video/mp4">
                </video>

            </div>
        </div>
    </div>

    <div class="container ">
        <div class="row justify-content-center position-relative">
            <div class="col-md-4 col-12">
                <div class="card shadow rounded-4 no-shadow-mobile">
                    {{-- <div class="card-header">
                        @if ($isUpdate)
                            회원정보 수정
                        @else
                            회원가입
                        @endif
                    </div> --}}

                    <div class="card-body p-4">

                        <form @submit.prevent="submit">

                            @if ($isUpdate)
                                <input type="hidden" name="user.id" value="{{ auth()->user()->id }}">
                            @endif

                            <!-- 기본 정보 -->
                            <div class="mb-4">
                                <h5 class="fw-bold mb-4">회원정보를 입력해주세요</h5>

                                <x-forms.input
                                    type="text"
                                    name="user.name"
                                    label="이름"
                                    placeholder="이름을 입력해주세요."
                                    required
                                    :errors="true"
                                    autocomplete="off"
                                />

                                <x-forms.input
                                    type="email"
                                    name="user.email"
                                    label="이메일"
                                    required
                                    :errors="true"
                                    :disabled="$isUpdate"
                                    autocomplete="off"
                                />

                                <x-forms.input
                                    type="tel"
                                    name="user.phone"
                                    label="전화번호"
                                    placeholder="- 없이 전화번호를 입력해 주세요"
                                    required
                                    :errors="true"
                                    autocomplete="off"
                                />

                                <x-forms.input
                                    type="password"
                                    name="user.password"
                                    label="비밀번호"
                                    placeholder="8자리 이상 으로 입력해 주세요"
                                    :required="!$isUpdate"
                                    :errors="true"
                                    autocomplete="off"
                                />

                                <x-forms.input
                                    type="password"
                                    name="user.password_confirmation"
                                    label="비밀번호 확인"
                                    placeholder="비밀번호를 다시 입력해주세요"
                                    :required="!$isUpdate"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- 딜러 정보 -->
                            <div class="mb-4">
                                @if (!$isUpdate)
                                <x-forms.checkbox
                                    name="isDealer"
                                    :errors="false"
                                    wrapperClass="rounded-check"
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
                                            autocomplete="off"
                                        />

                                        <x-forms.input
                                            type="text"
                                            name="dealer.birthday"
                                            label="생년월일"
                                            placeholder="생년월일 6자리 예) 900101"
                                            required
                                            :errors="true"
                                            autocomplete="off"
                                        />

                                        <x-forms.input
                                            type="tel"
                                            name="dealer.phone"
                                            label="연락처"
                                            placeholder="- 없이 전화번호를 입력해 주세요"
                                            required
                                            :errors="true"
                                            autocomplete="off"
                                        />

                                        <x-forms.input
                                            type="text"
                                            name="dealer.company"
                                            label="소속상사"
                                            placeholder="상사명(상사 정식 명칭)"
                                            required
                                            :errors="true"
                                            autocomplete="off"
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
                                            required
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
                                            autocomplete="off"
                                        />

                                        <x-forms.input
                                            type="text"
                                            name="dealer.corporation_registration_number"
                                            label="법인등록번호"
                                            placeholder="법인등록번호를 입력해주세요"
                                            required
                                            :errors="true"
                                            autocomplete="off"
                                        />

                                        <x-forms.input
                                            type="text"
                                            name="dealer.car_management_business_registration_number"
                                            label="자동차관리업 등록번호"
                                            placeholder="자동차관리업 등록번호를 입력해주세요"
                                            required
                                            :errors="true"
                                            autocomplete="off"
                                        />

                                        <!-- 파일 업로드 -->
                                        <x-forms.fileUpload
                                            label="사진 (본인 확인용)"
                                            name="file_user_photo"
                                            :preview="true"
                                            :errors="true"
                                            :existingFiles="$files['file_user_photo'] ?? null"
                                            {{-- :required="!$isUpdate" --}}
                                        />

                                        <x-forms.fileUpload
                                            label="사업자 등록증"
                                            name="file_user_biz"
                                            :existingFiles="$files['file_user_biz'] ?? null"
                                            :errors="true"
                                            {{-- :required="!$isUpdate" --}}
                                        />

                                        <x-forms.fileUpload
                                            label="매도용인감정보"
                                            name="file_user_sign"
                                            :errors="true"
                                            :existingFiles="$files['file_user_sign'] ?? null"
                                            {{-- :required="!$isUpdate" --}}
                                        />

                                        <x-forms.fileUpload
                                            label="매매업체 대표증 or 종사원증"
                                            name="file_user_cert[]"
                                            :existingFiles="$files['file_user_cert'] ?? null"
                                            :errors="true"
                                            {{-- :required="!$isUpdate" --}}
                                        />

                                        @php
                                            $auctionTermsAndPolicy = [
                                                'title' => '온라인자동차경매장 규약',
                                                'content' => '/v2/docs/auctionTermsAndPolicy?raw=1',
                                            ];

                                            $residentRegistrationNumberConsent = [
                                                'title' => '주민등록번호(법인등록번호) 수집 동의',
                                                'content' => '/v2/docs/residentRegistrationNumberConsent?raw=1',
                                            ];

                                            $carManagementRegistrationConsent = [
                                                'title' => '자동차관리사업등록번호 수집 동의',
                                                'content' => '/v2/docs/carManagementRegistrationConsent?raw=1',
                                            ];

                                            $businessInformationConsent = [
                                                'title' => '사업자정보 수집 동의',
                                                'content' => '/v2/docs/businessInformationConsent?raw=1',
                                            ];

                                            $privacy = [
                                                'title' => '개인정보처리방침',
                                                'content' => '/v2/docs/privacy?raw=1',
                                            ];

                                        @endphp

                                        @if (!$isUpdate)
                                        <!-- 약관 동의 -->
                                        <x-forms.checkbox
                                            name="dealer.isCheckDealer1"
                                            :label="'<a href=\'#\' @click.prevent=\'Alpine.store(`modal`).showHtmlFromUrl(`' . $auctionTermsAndPolicy['content'] . '`, {title: `' . $auctionTermsAndPolicy['title'] . '`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})\'>' . $auctionTermsAndPolicy['title'] . '</a>에 동의합니다.'"
                                            required
                                            wrapperClass="rounded-check"
                                            :errors="true"
                                        />

                                        <x-forms.checkbox
                                            name="dealer.isCheckDealer2"
                                            :label="'<a href=\'#\' @click.prevent=\'Alpine.store(`modal`).showHtmlFromUrl(`' . $residentRegistrationNumberConsent['content'] . '`, {title: `' . $residentRegistrationNumberConsent['title'] . '`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})\'>' . $residentRegistrationNumberConsent['title'] . '</a>에 동의합니다.'"
                                            required
                                            wrapperClass="rounded-check"
                                            :errors="true"
                                        />

                                        <x-forms.checkbox
                                            name="dealer.isCheckDealer3"
                                            :label="'<a href=\'#\' @click.prevent=\'Alpine.store(`modal`).showHtmlFromUrl(`' . $carManagementRegistrationConsent['content'] . '`, {title: `' . $carManagementRegistrationConsent['title'] . '`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})\'>' . $carManagementRegistrationConsent['title'] . '</a>에 동의합니다.'"
                                            required
                                            wrapperClass="rounded-check"
                                            :errors="true"
                                        />

                                        <x-forms.checkbox
                                            name="dealer.isCheckDealer4"
                                            :label="'<a href=\'#\' @click.prevent=\'Alpine.store(`modal`).showHtmlFromUrl(`' . $businessInformationConsent['content'] . '`, {title: `' . $businessInformationConsent['title'] . '`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})\'>' . $businessInformationConsent['title'] . '</a>에 동의합니다.'"
                                            required
                                            wrapperClass="rounded-check"
                                            :errors="true"
                                        />
                                        @endif
                                    </div>
                                </template>
                            </div>

                            @if (!$isUpdate)
                            <x-forms.checkbox
                                name="user.isCheckPrivacy"
                                :label="'<a href=\'#\' @click.prevent=\'Alpine.store(`modal`).showHtmlFromUrl(`/v2/docs/privacy?raw=1`, {title: `개인정보처리방침`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})\'>개인정보처리방침</a>에 동의합니다.'"
                                required
                                wrapperClass="rounded-check"
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
</div>
@endsection
