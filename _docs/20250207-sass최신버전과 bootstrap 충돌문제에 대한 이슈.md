## 20250207-sass최신버전과 bootstrap 충돌문제에 대한 이슈 

### 문제 발생 이유

- sass 최신버전과 bootstrap 충돌문제가 발생했습니다.
- 이 문제를 해결하기 위해 여러 방법을 시도했지만 해결되지 않았습니다.
- 문제의 이유는 최신버전의 sass 에서는 import 를 지향하고 use 를 사용하도록 권고 하는 경고 메시지가 발생하였습니다.
- 부트스트랩에서 내부에서는 import 를 사용하고 있는데, 현재 가장 최신버전인 5.3.3 버전에서는 sass의 최신버전에 맞춰 업데이트가 되어 있지 않습니다. 
- 그러하여, sass를 최신버전으로 업데이트하기전에 부트스트랩이 최신버전이 나올시, use 를 완벽하게 지원할 경우 sass 를 최신버전으로 업데이트 하고 부트스트랩 버전을 최신버전으로 업데이트 하는 것을 권장합니다. 

1. 본서버에서의 npm list 결과 

```bash
+-- @casl/ability@6.7.1
+-- @casl/vue@2.2.2
+-- @ckeditor/ckeditor5-build-classic@40.2.0
+-- @ckeditor/ckeditor5-vue@5.1.0
+-- @popperjs/core@2.11.8
+-- @vitejs/plugin-vue@4.6.2
+-- @vuepic/vue-datepicker@10.0.0
+-- axios@1.7.7
+-- bootstrap@5.3.3
+-- date-fns@3.6.0
+-- dayjs@1.11.13
+-- flatpickr@4.6.13
+-- gsap@3.12.5
+-- hammerjs@2.0.8
+-- js-cookie@3.0.5
+-- laravel-vite-plugin@0.8.1
+-- laravel-vue-pagination@4.1.3
+-- lodash@4.17.21
+-- nouislider@15.8.1
+-- postcss@8.4.49
+-- sass@1.80.4
+-- sweetalert2@11.14.4
+-- swiper@11.1.15
+-- vee-validate@4.14.6
+-- vite@4.5.5
+-- vue-chart-3@3.1.8
+-- vue-datepicker@1.3.0
+-- vue-i18n@9.14.1
+-- vue-multiselect-listbox@0.4.5
+-- vue-router@4.4.5
+-- vue-select@4.0.0-beta.6
+-- vue-slider-component@3.2.24
+-- vue-sweetalert2@5.0.11
+-- vue@3.5.13
+-- vue3-datepicker@0.4.0
+-- vue3-slider@1.10.1
+-- vue3-timepicker@1.0.0-beta.2
+-- vuex-persistedstate@4.1.0
+-- vuex@4.1.0
``` 

2. sass 버전을 다운그레이드 하여, sass@1.68.0 버전으로 변경 

```bash
npm install sass@1.68.0
```


