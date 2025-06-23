<div x-data="addressBookAdd">
    <form @submit.prevent="isModify ? modify : submit" method="post">
        @csrf

        <x-forms.input
            type="text"
            name="name"
            label="이름"
            placeholder="이름을 입력해주세요."
            required
            :errors="true"
        />

        <x-forms.address
            label="주소"
            postName="addr_post"
            addr1Name="addr1"
            addr2Name="addr2"
            postCodeId="addr_post"
            required
            :errors="true"
        />

        <button type="submit" class="btn btn-primary w-100" x-text="isModify ? '수정' : '저장'"></button>

    </form>
</div>