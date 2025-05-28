@props([
    'label' => '',
    'postName' => '',
    'addr1Name' => '',
    'addr2Name' => '',
    'postCodeId' => '',
    'errors' => null
])

<div class="mb-3">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group mb-2">
        <input
            type="text"
            class="form-control"
            x-model="form.{{ $postName }}"
            readonly
            placeholder="우편번호"
            :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $postName) }}?.length > 0 }"
        >
        <button
            type="button"
            class="btn btn-outline-secondary"
            @click="async () => {
                try {
                    const result = await $store.address.openPostcode('{{ $postCodeId }}');
                    if (result) {
                        form.{{ $postName }} = result.zonecode;
                        form.{{ $addr1Name }} = result.address;
                    }
                } catch (error) {
                    console.error('주소 검색 오류:', error);
                }
            }"
        >주소 검색</button>
    </div>

    <div id="{{ $postCodeId }}" class="mb-2"></div>

    <input
        type="text"
        class="form-control mb-2"
        x-model="form.{{ $addr1Name }}"
        readonly
        placeholder="주소"
        :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $addr1Name) }}?.length > 0 }"
    >

    <input
        type="text"
        class="form-control"
        x-model="form.{{ $addr2Name }}"
        placeholder="상세주소"
        :class="{ 'is-invalid': errors?.{{ str_replace('.', '?.', $addr2Name) }}?.length > 0 }"
    >

    @if($errors)
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $postName) }}?.[0]"></div>
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $addr1Name) }}?.[0]"></div>
        <div class="invalid-feedback" x-text="errors?.{{ str_replace('.', '?.', $addr2Name) }}?.[0]"></div>
    @endif
</div>
