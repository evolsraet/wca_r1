export default function() {
    return {
        pageInfo: {
            title: '',
            url: '',
            description: ''
        },
        copySuccess: false,
        showQR: false,
        supportsNativeShare: false,
        qrCodeInstance: null,

        init() {
            this.initPageInfo();
            this.checkNativeShareSupport();
        },

        initPageInfo() {
            this.pageInfo = {
                title: document.title || '위카옥션 - 쉽고 빠른 내차 팔기',
                url: window.location.href,
                description: document.querySelector('meta[name="description"]')?.content || 
                           '자동차 진단 전문가 위카옥션에서 높은 가격으로 내 차를 판매하세요!'
            };
        },

        checkNativeShareSupport() {
            this.supportsNativeShare = 'share' in navigator;
        },

        // 카카오톡 공유
        async shareToKakao() {
            try {
                // 개발환경에서는 콘솔 출력
                if (this.isDevelopment()) {
                    console.log('🔄 카카오톡 공유 (개발환경):', this.pageInfo);
                    this.$store.toastr.info('개발환경: 카카오톡 공유 기능 시뮬레이션');
                    return;
                }

                // 카카오 SDK 로드 확인
                if (typeof Kakao === 'undefined') {
                    await this.loadKakaoSDK();
                }

                if (!Kakao.isInitialized()) {
                    Kakao.init('YOUR_KAKAO_APP_KEY'); // 실제 앱키로 교체 필요
                }

                Kakao.Share.sendDefault({
                    objectType: 'feed',
                    content: {
                        title: this.pageInfo.title,
                        description: this.pageInfo.description,
                        imageUrl: window.location.origin + '/images/busan_wecar_logo.png',
                        link: {
                            mobileWebUrl: this.pageInfo.url,
                            webUrl: this.pageInfo.url
                        }
                    },
                    buttons: [{
                        title: '웹으로 보기',
                        link: {
                            mobileWebUrl: this.pageInfo.url,
                            webUrl: this.pageInfo.url
                        }
                    }]
                });
            } catch (error) {
                console.error('카카오톡 공유 실패:', error);
                this.$store.toastr.error('카카오톡 공유에 실패했습니다.');
            }
        },

        // 페이스북 공유
        shareToFacebook() {
            if (this.isDevelopment()) {
                console.log('🔄 페이스북 공유 (개발환경):', this.pageInfo);
                this.$store.toastr.info('개발환경: 페이스북 공유 기능 시뮬레이션');
                return;
            }

            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.pageInfo.url)}`;
            this.openShareWindow(shareUrl, '페이스북 공유');
        },

        // 트위터 공유
        shareToTwitter() {
            if (this.isDevelopment()) {
                console.log('🔄 트위터 공유 (개발환경):', this.pageInfo);
                this.$store.toastr.info('개발환경: 트위터 공유 기능 시뮬레이션');
                return;
            }

            const text = `${this.pageInfo.title} - ${this.pageInfo.description}`;
            const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(this.pageInfo.url)}`;
            this.openShareWindow(shareUrl, '트위터 공유');
        },

        // 라인 공유
        shareToLine() {
            if (this.isDevelopment()) {
                console.log('🔄 라인 공유 (개발환경):', this.pageInfo);
                this.$store.toastr.info('개발환경: 라인 공유 기능 시뮬레이션');
                return;
            }

            const text = `${this.pageInfo.title}\n${this.pageInfo.url}`;
            const shareUrl = `https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(this.pageInfo.url)}&text=${encodeURIComponent(text)}`;
            this.openShareWindow(shareUrl, '라인 공유');
        },

        // 네이티브 공유
        async shareNative() {
            try {
                if (this.isDevelopment()) {
                    console.log('🔄 네이티브 공유 (개발환경):', this.pageInfo);
                    this.$store.toastr.info('개발환경: 네이티브 공유 기능 시뮬레이션');
                    return;
                }

                if (this.supportsNativeShare) {
                    await navigator.share({
                        title: this.pageInfo.title,
                        text: this.pageInfo.description,
                        url: this.pageInfo.url
                    });
                }
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('네이티브 공유 실패:', error);
                    this.$store.toastr.error('공유에 실패했습니다.');
                }
            }
        },

        // 클립보드 복사
        async copyToClipboard() {
            try {
                if (this.isDevelopment()) {
                    console.log('🔄 클립보드 복사 (개발환경):', this.pageInfo.url);
                }

                if (navigator.clipboard) {
                    await navigator.clipboard.writeText(this.pageInfo.url);
                } else {
                    // 폴백: input 요소를 사용한 복사
                    const input = document.getElementById('shareUrl');
                    input.select();
                    document.execCommand('copy');
                }
                
                this.copySuccess = true;
                this.$store.toastr.success('클립보드에 복사되었습니다!');
                
                setTimeout(() => {
                    this.copySuccess = false;
                }, 3000);
            } catch (error) {
                console.error('클립보드 복사 실패:', error);
                this.$store.toastr.error('복사에 실패했습니다.');
            }
        },

        // QR 코드 토글
        async toggleQR() {
            this.showQR = !this.showQR;
            
            if (this.showQR) {
                // QR 코드 라이브러리 동적 로드
                await this.loadQRCodeLibrary();
                this.generateQRCode();
            } else {
                this.clearQRCode();
            }
        },

        // QR 코드 생성
        generateQRCode() {
            this.$nextTick(() => {
                const qrContainer = document.getElementById('qrcode');
                if (qrContainer && typeof QRCode !== 'undefined') {
                    // 기존 QR 코드 정리
                    qrContainer.innerHTML = '';
                    
                    this.qrCodeInstance = new QRCode(qrContainer, {
                        text: this.pageInfo.url,
                        width: 200,
                        height: 200,
                        colorDark: '#000000',
                        colorLight: '#ffffff'
                    });
                }
            });
        },

        // QR 코드 정리
        clearQRCode() {
            const qrContainer = document.getElementById('qrcode');
            if (qrContainer) {
                qrContainer.innerHTML = '';
            }
            this.qrCodeInstance = null;
        },

        // 공유 창 열기
        openShareWindow(url, title) {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth - width) / 2;
            const top = (window.innerHeight - height) / 2;
            
            window.open(
                url,
                title,
                `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
            );
        },

        // 개발환경 확인
        isDevelopment() {
            return window.location.hostname === 'localhost' || 
                   window.location.hostname === '127.0.0.1' ||
                   window.location.hostname.includes('local');
        },

        // 카카오 SDK 동적 로드
        async loadKakaoSDK() {
            return new Promise((resolve, reject) => {
                if (typeof Kakao !== 'undefined') {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://developers.kakao.com/sdk/js/kakao.min.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        },

        // QR 코드 라이브러리 동적 로드
        async loadQRCodeLibrary() {
            return new Promise((resolve, reject) => {
                if (typeof QRCode !== 'undefined') {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
                script.onload = () => {
                    // qrcode.js는 전역으로 QRCode 생성자를 제공하지 않으므로
                    // 대신 다른 라이브러리 사용
                    const qrScript = document.createElement('script');
                    qrScript.src = 'https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js';
                    qrScript.onload = resolve;
                    qrScript.onerror = reject;
                    document.head.appendChild(qrScript);
                };
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
    }
}