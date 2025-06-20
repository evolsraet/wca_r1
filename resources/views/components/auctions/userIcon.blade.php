<img 
x-data="{
    profileImage: '{{ asset('images/profile_dom.png') }}',
    init() {
        if(user && user.media && user.media.length > 0) {
            const userPhoto = user.media.find(media => media.collection_name === 'file_user_photo');
            this.profileImage = userPhoto ? userPhoto.original_url : '{{ asset('images/profile_dom.png') }}';
        } else {
            this.profileImage = '{{ asset('images/profile_dom.png') }}';
        }
    }
}"
:src="profileImage" alt="프로필 이미지" class="rounded-circle w-100 h-100">