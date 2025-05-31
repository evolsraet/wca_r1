@extends('v2.layouts.app')

@section('title', 'test')

@section('content')

<div class="container py-5 style-guide">
    <h1 class="mb-4">STYLE GUIDE</h1>

    <!-- COLORS -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Colors</h5></div>
        <div class="card-body d-flex flex-wrap gap-3">
            <div class="p-3 bg-primary text-white">.bg-primary</div>
            <div class="p-3 bg-secondary text-white">.bg-secondary</div>
            <div class="p-3 bg-success text-white">.bg-success</div>
            <div class="p-3 bg-danger text-white">.bg-danger</div>
            <div class="p-3 bg-warning text-dark">.bg-warning</div>
            <div class="p-3 bg-info text-dark">.bg-info</div>
            <div class="p-3 bg-light text-dark border">.bg-light</div>
            <div class="p-3 bg-dark text-white">.bg-dark</div>
        </div>
    </div>

    <!-- BUTTONS -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Buttons</h5></div>
        <div class="card-body">
            <h6 class="mb-3">Basic Variants</h6>
            <div class="d-flex flex-wrap gap-3 mb-4">
                <button class="btn btn-primary">Primary</button>
                <button class="btn btn-secondary">Secondary</button>
                <button class="btn btn-success btn-lg">Success</button>
                <button class="btn btn-danger">Danger</button>
                <button class="btn btn-warning">Warning</button>
                <button class="btn btn-info">Info</button>
                <button class="btn btn-dark">Dark</button>
                <button class="btn btn-outline-primary">Outline</button>
                <button class="btn btn-link">Link</button>
            </div>

            <div class="mb-3 bg-black p-3">
                <button class="btn btn-dark-white rounded-pill btn-sm">Primary</button>
            </div>

            <pre>
                    <code>
                        &lt;button class="btn btn-primary"&gt;Primary&lt;/button&gt;
                    </code>
                </pre>

            <h6 class="mb-3">Sizes</h6>
            <div class="d-flex flex-wrap gap-3 mb-4">
                <button class="btn btn-primary btn-sm">Small</button>
                <button class="btn btn-primary">Medium (default)</button>
                <button class="btn btn-primary btn-lg">Large</button>
            </div>

            <h6 class="mb-3">Widths</h6>
            <div class="d-flex flex-column gap-3" style="max-width: 400px;">
                <button class="btn btn-primary w-100">Full Width</button>
                <button class="btn btn-secondary" style="width: 200px;">Fixed Width (200px)</button>
                <button class="btn btn-success px-5">Custom Padding</button>
            </div>
        </div>
    </div>

    <!-- TYPOGRAPHY -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Typography</h5></div>
        <div class="card-body">
            <h1>Heading 1</h1>
            <h2>Heading 2</h2>
            <h3>Heading 3</h3>
            <h4>Heading 4</h4>
            <h5>Heading 5</h5>
            <h6>Heading 6</h6>

            <p class="lead mt-4">This is a lead paragraph for emphasis.</p>
            <p>This is a regular paragraph. <span class="text-muted">This is muted text.</span></p>
            <p class="small">This is small text.</p>

            <p class="fw-bold">Bold text</p>
            <p class="fw-light">Light weight text</p>
            <p class="fst-italic">Italic text</p>
            <p class="text-uppercase">Uppercase text</p>

            <p class="text-start">Left aligned text</p>
            <p class="text-center">Center aligned text</p>
            <p class="text-end">Right aligned text</p>

            <a href="#" class="link-primary">Primary Link</a><br>
            <a href="#" class="link-secondary text-decoration-none">Secondary Link (no underline)</a>
        </div>
    </div>

    <!-- FORM INPUTS -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Forms</h5></div>
        <div class="card-body">
            <form>
            <div class="mb-3">
                <label class="form-label">Text Input</label>
                <input type="text" class="form-control" placeholder="Enter text">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Enter password">
            </div>

            <div class="mb-3">
                <label class="form-label">Textarea</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Select</label>
                <select class="form-select">
                <option>Option 1</option>
                <option>Option 2</option>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="check1">
                <label class="form-check-label" for="check1">Check this out</label>
            </div>

            <div class="mb-3">
                <label class="form-label">File Upload</label>
                <input type="file" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Validation States</label>
                <input type="text" class="form-control is-valid" placeholder="Valid input">
                <input type="text" class="form-control is-invalid mt-2" placeholder="Invalid input">
            </div>

            <div class="mb-3">
                <label class="form-label">Input Group</label>
                <div class="input-group">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" placeholder="username">
                </div>
            </div>

            <div class="mb-3">
                <p class="form-hint-danger">※ 계좌는 차량 소유주의 계좌번호만 입력가능 합니다.</p>
            </div>

            <div class="mb-3">
                <div class="custom-checkbox">
                <input type="checkbox" id="ch2">
                <label for="ch2"></label>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-unit-group">
                    <input type="text" class="form-control" placeholder="주행거리">
                    <span class="unit-label">Km</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="file-upload-btn">
                <input type="file" hidden>
                <i class="bi bi-upload me-2"></i> 파일 첨부
                </label>
            </form>
        </div>
        </div>

    <!-- CARDS -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Cards</h5></div>
        <div class="card-body">
            
        <!-- 기본 카드 -->
        <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">기본 카드</h5>
            <p class="card-text">기본 카드 설명 텍스트입니다.</p>
        </div>
        </div>

        <!-- 이미지 카드 -->
        <div class="card mb-4">
        <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">이미지 카드</h5>
            <p class="card-text">이미지를 포함한 카드입니다.</p>
        </div>
        </div>

        <!-- 헤더/푸터 포함 카드 -->
        <div class="card mb-4">
        <div class="card-header">카드 헤더</div>
        <div class="card-body">
            <h5 class="card-title">제목</h5>
            <p class="card-text">본문 내용</p>
        </div>
        <div class="card-footer text-muted">카드 푸터</div>
        </div>

        <!-- 배경색 카드 -->
        <div class="card bg-primary text-white mb-4">
        <div class="card-body">Primary 스타일 카드</div>
        </div>

        <!-- 미니 카드 예시 -->
        <div class="card text-center shadow-sm rounded mb-4" style="width: 10rem;">
        <div class="card-body">
            <div class="mb-2"><i class="bi bi-graph-up fs-3"></i></div>
            <h6 class="card-title">매출</h6>
            <p class="card-text fw-bold">₩1,200,000</p>
        </div>
        </div>

        </div>
    </div>

    <!-- IMAGES -->
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Images</h5></div>
        <div class="card-body d-flex gap-3">
            <img src="https://via.placeholder.com/100" class="img-fluid" alt="fluid">
            <img src="https://via.placeholder.com/100" class="img-thumbnail" alt="thumbnail">
            <img src="https://via.placeholder.com/100" class="rounded-circle" alt="rounded">
        </div>
    </div>
</div>
    
@endsection