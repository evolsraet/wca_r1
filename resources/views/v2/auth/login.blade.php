@extends('v2.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="mb-0">로그인</h4>
                </div>
                <div class="card-body">
                    <form x-data="login"
                          x-init="$store.login.setRedirectUrl('{{ request()->header('referer') }}')"
                          @submit.prevent="submit">
                        <!-- 이메일 -->
                        <div class="mb-3">
                            <label for="email" class="form-label">이메일</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   x-model="form.email"
                                   :class="{ 'is-invalid': errors.email }"
                                   required>
                            <div class="invalid-feedback" x-text="errors.email"></div>
                        </div>

                        <!-- 비밀번호 -->
                        <div class="mb-3">
                            <label for="password" class="form-label">비밀번호</label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   x-model="form.password"
                                   :class="{ 'is-invalid': errors.password }"
                                   required>
                            <div class="invalid-feedback" x-text="errors.password"></div>
                        </div>

                        <!-- 에러 메시지 -->
                        <div class="alert alert-danger" x-show="error" x-text="error"></div>

                        <!-- 로그인 버튼 -->
                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-2"></span>
                                로그인
                            </button>
                        </div>

                        <!-- 추가 링크 -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">비밀번호 찾기</a>
                            <span class="mx-2">|</span>
                            <a href="{{ route('register') }}" class="text-decoration-none">회원가입</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
