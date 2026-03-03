export default function () {
  return {
    form: {
      with: 'bids,likes',
      page: 1,
      paginate: 12,
      lists: [],
      totalPages: 1,
      search_text: '',
    },
    errors: {},
    loading: false,
    
    // 드롭다운 상태 관리
    dropdownStates: {
      N: true, // 국산차
      Y: true, // 수입차
    },

    // 하위 카테고리 상태 관리 (명시적 초기화)
    subCategoryStates: {},
    
    // 하위 카테고리 데이터 저장
    subCategoryData: {},
    
    // 로딩 상태 관리
    loadingStates: {},

    // 선택된 필터 정보
    selectedFilter: {
      type: null, // 'maker', 'model', 'detail', 'bp', 'grade'
      id: null,
      name: null,
      value: null
    },
    
    // 카테고리 타입 매핑 상수
    CATEGORY_TYPE_MAP: {
      maker: 'model',
      model: 'detail',
      detail: 'bp', 
      bp: 'grade',
      default: 'grade'
    },
    

    init() {
      // 상태 초기화 보장 (Alpine.js 반응성 문제 해결)
      this.subCategoryStates = {};
      this.subCategoryData   = {};
      this.loadingStates     = {};
      
      const params = new URLSearchParams(window.location.search);
      const page = parseInt(params.get('page')) || 1;
      const search = params.get('search_text') || '';

      this.form.page = page;
      this.form.search_text = search;
      
      // 간소화된 이벤트 처리
      this.initEventDelegation();

      this.getAuctionList();

      // 정리 이벤트
      window.addEventListener('beforeunload', () => this.cleanup());
      window.addEventListener('pagehide', () => this.cleanup());
    },

    async getAuctionList() {
      this.loading = true;

      try {
        const params = {
          with: this.form.with,
          page: this.form.page,
          paginate: this.form.paginate,
          search_text: this.form.search_text,
        };
        
        // 선택된 필터가 있으면 추가
        if (this.selectedFilter.type && this.selectedFilter.value) {
          params.car_filter_type = this.selectedFilter.type;
          params.car_filter_value = this.selectedFilter.value;
        }
        
        const response = await Alpine.store('api').get('/api/auctions', params);

        this.form.lists = Array.isArray(response.data?.data) ? response.data.data : [];
        this.form.lists.forEach(item => {
          item.car_km = Number(item.car_km).toLocaleString('ko-KR');
        });

        const meta = response.data.meta;
        this.form.totalPages = meta?.last_page || 1;

        this.updateUrlParams();

      } catch (err) {
        console.error('경매 리스트 불러오기 실패:', err);
      } finally {
        this.loading = false;
      }
    },

    changePage(page) {
      if (page < 1 || page > this.form.totalPages || page === this.form.page) return;
      this.form.page = page;
      this.getAuctionList();

      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },

    handleSearch() {
      this.form.page = 1;
      this.getAuctionList();
    },

    toggleDropdown(type) {
      // 드롭다운 상태 토글
      this.dropdownStates[type] = !this.dropdownStates[type];
    },
    
    // API 독립적인 하위 카테고리 토글 함수 (핵심 해결책)
    toggleSubCategoryVisibility(categoryId) {
      // 1. input 요소를 명시적으로 찾기
      const inputElement = document.querySelector(`input.dynamic-sub-category[data-category-id="${categoryId}"]`);
      
      if (!inputElement) {
        // 입력 요소가 없으면 직접 sub-category 찾기 (정적 템플릿용)
        const container = document.querySelector(`div.sub-category[data-category-id="${categoryId}"]`);
        
        if (!container) {
          // 컨테이너가 없으면 상태만 토글 (API 요청으로 생성될 예정)
          this.subCategoryStates[categoryId] = !(this.subCategoryStates[categoryId] || false);
          return;
        }
        
        // 정적 컨테이너에 대한 토글
        const isCurrentlyShown = container.classList.contains('show');
        if (isCurrentlyShown) {
          container.classList.remove('show');
          this.subCategoryStates[categoryId] = false;
        } else {
          container.classList.add('show');
          this.subCategoryStates[categoryId] = true;
        }
        return;
      }
      
      // 2. 동적 요소: 형제 sub-category 요소 찾기
      const parentFormCheck = inputElement.closest('.form-check');
      let subCategoryContainer = null;
      
      // 형제 요소 중에서 .sub-category 찾기
      if (parentFormCheck && parentFormCheck.nextElementSibling) {
        const nextSibling = parentFormCheck.nextElementSibling;
        if (nextSibling.classList.contains('sub-category')) {
          subCategoryContainer = nextSibling;
        }
      }
      
      // 형제 요소에서 못 찾으면 data-category-id로 직접 찾기
      if (!subCategoryContainer) {
        subCategoryContainer = document.querySelector(`div.sub-category[data-category-id="${categoryId}"]`);
      }
      
      if (!subCategoryContainer) {
        // 컨테이너가 없으면 상태만 토글 (API 요청으로 생성될 예정)
        this.subCategoryStates[categoryId] = !(this.subCategoryStates[categoryId] || false);
        return;
      }
      
      // 3. 올바른 요소(div.sub-category)에 토글 적용
      const isCurrentlyShown = subCategoryContainer.classList.contains('show');
      
      if (isCurrentlyShown) {
        subCategoryContainer.classList.remove('show');
        this.subCategoryStates[categoryId] = false;
      } else {
        subCategoryContainer.classList.add('show');
        this.subCategoryStates[categoryId] = true;
      }
    },
    
    // 이전 작동 버전 기반 카테고리 선택 처리 (복원)
    async handleCategorySelection(categoryId, event) {
      
      event.stopPropagation();
      
      const selectedRadio = event.target;
      if (!selectedRadio.checked) return;
      
      // 필터 정보 파싱 (공통 로직)
      const filterInfo = this.parseFilterInfo(selectedRadio.value, selectedRadio);
      if (!filterInfo) return;
      
      this.selectedFilter = filterInfo.selectedFilter;
      
      // 즉시 토글 실행 (API 독립적)
      this.toggleSubCategoryVisibility(categoryId);
      
      // 하위 카테고리 로드 (중간 단계)
      if (filterInfo.searchType && filterInfo.filterValue && this.subCategoryStates[categoryId]) {
        await this.fetchSubCategory(filterInfo.searchType, filterInfo.filterValue, categoryId);
        
        // Alpine.js 반응성 강제 트리거 (이전 버전 방식)
        this.$nextTick();
      }
      
      // 최종 단계 검사 및 실행
      if (filterInfo.filterType !== 'maker') {
        this.form.page = 1;
        this.getAuctionList();
      }
      
      // 같은 레벨 카테고리 닫기
      this.closeSameLevelCategories(categoryId);
      
    },
    
    
    // 이전 작동 버전 기반 API 요청 및 렌더링 (복원)
    async fetchSubCategory(filterType, filterValue, parentCategoryId) {
      
      // 로딩 상태 시작
      this.loadingStates[parentCategoryId] = true;
      this.subCategoryData[parentCategoryId] = [];
      
      try {        
        const response = await Alpine.store('api').get('/api/auctions/car-sub-category', {
          car_filter_type: filterType,
          car_filter_value: filterValue
        });
        
        if (response.data && response.data.data) {
          this.subCategoryData[parentCategoryId] = response.data.data;
          
          // Alpine.js 반응성 트리거 - 데이터 변경 알림 (이전 버전 방식)
          this.$nextTick(() => {
            // 수동으로 DOM 업데이트 강제 실행 (이전 버전 핵심 기능)
            this.triggerSubCategoryRender(parentCategoryId);
          });
        }
      } catch (error) {        
        // API 실패 시 하위 카테고리 컨테이너 숨김 처리
        const container = document.querySelector(`[data-category-id="${parentCategoryId}"]`);
        if (container) {
          container.classList.remove('show');
        }
        
        // 상태도 초기화
        this.subCategoryStates[parentCategoryId] = false;
        
        // 에러를 다시 throw하여 상위 함수에서도 처리할 수 있도록 함
        throw error;
      } finally {
        this.loadingStates[parentCategoryId] = false;
      }
    },
    
    // 이전 버전의 수동 렌더링 함수 (복원) - 핵심 기능
    triggerSubCategoryRender(parentCategoryId) {
      
      // 1. 상태 강제 업데이트
      this.updateSubCategoryDisplay(parentCategoryId);
      
      // 2. CSS 클래스 강제 적용
      const { container } = this.findSubCategoryContainer(parentCategoryId);
      if (container && this.subCategoryStates[parentCategoryId]) {
        container.classList.add('show');
      }
      
    },
    
    // 최종 레벨 필터 처리 (등급 등) - 이전 버전 복원
    async handleFinalSelection(event) {
      
      if (!event.target.checked) return;
      
      const filterInfo = this.parseFilterInfo(event.target.value, event.target);
      if (!filterInfo) return;
      
      
      this.selectedFilter = filterInfo.selectedFilter;
      
      this.form.page = 1;
      this.getAuctionList();
      
    },
    
    // 수정된 DOM 요소 찾기 (동적 생성용 + 정적 템플릿용)
    findSubCategoryContainer(categoryId) {
      
      // 1. 우선 .sub-category 컨테이너 찾기 (가장 중요)
      let subCategoryContainer = document.querySelector(`.sub-category[data-category-id="${categoryId}"]`);
      
      if (subCategoryContainer) {
        // 컨테이너가 있으면 내부의 content 찾기
        const contentElement = subCategoryContainer.querySelector('.sub-category-content');
        return { container: subCategoryContainer, content: contentElement };
      }
      
      // 2. 컨테이너가 없으면 현재 선택된 부모 요소 기준으로 찾기
      
      // 현재 체크된 부모 요소 찾기 (정적 템플릿 요소)
      const checkedParentInput = document.querySelector('input[name="car_filter"]:checked:not(.dynamic-sub-category)');
      if (checkedParentInput) {
        const parentCategoryId = checkedParentInput.value;
        
        // 부모의 sub-category-content 찾기
        const parentContent = document.querySelector(`.sub-category-content[data-parent="${parentCategoryId}"]`);
        
        if (parentContent) {
          return { container: null, content: parentContent };
        }
      }
      
      // 3. 최후의 수단: 모든 가능한 셀렉터 시도
      const selectors = [
        `[data-category-id="${categoryId}"]`,
        `[data-parent="${categoryId}"]`,
        `.sub-category[data-category-id="${categoryId}"]`
      ];
      
      for (let selector of selectors) {
        const element = document.querySelector(selector);
        if (element) {
          
          if (element.classList.contains('sub-category')) {
            const contentElement = element.querySelector('.sub-category-content');
            return { container: element, content: contentElement };
          } else if (element.classList.contains('sub-category-content')) {
            const containerElement = element.closest('.sub-category');
            return { container: containerElement, content: element };
          }
        }
      }
      
      
      return { container: null, content: null };
    },
    
    // 부모 카테고리 ID 계산 헬퍼 함수
    getParentCategoryId(categoryId) {
      // model_1755 → maker_XXX 형태로 역계산
      const parts = categoryId.split('_');
      if (parts.length !== 2) return null;
      
      const [currentType] = parts;
      
      // 타입 역매핑
      const reverseMap = {
        'model': 'maker',
        'detail': 'model', 
        'bp': 'detail',
        'grade': 'bp'
      };
      
      const parentType = reverseMap[currentType];
      if (!parentType) return null;
      
      // 실제 부모 ID는 API 데이터나 DOM에서 찾아야 하지만
      // 임시로 현재 선택된 부모 요소에서 추출
      const checkedParent = document.querySelector(`input[name="car_filter"]:checked`);
      if (checkedParent && checkedParent.value.includes(parentType)) {
        return checkedParent.value;
      }
      
      return null;
    },
    
    // 하위 카테고리 DOM 업데이트 (강화된 로직 + 이전 버전 패턴)
    updateSubCategoryDisplay(parentCategoryId) {
      
      // 강화된 컨테이너 찾기
      const { container, content } = this.findSubCategoryContainer(parentCategoryId);
      
      if (!content) {
        return;
      }
      
      // 새 HTML 생성 및 삽입
      const html = this.renderSubCategory(parentCategoryId);
      content.innerHTML = html;
      
      // 상태에 따른 표시/숨김 처리 (DOM과 내부 상태 완전 동기화)
      if (container) {
        const isExpanded = this.subCategoryStates[parentCategoryId] || false;
        
        if (isExpanded) {
          container.classList.add('show');
        } else {
          container.classList.remove('show');
        }
        
        // 내부 상태를 DOM 상태와 동기화 (토글 정확성 보장)
        this.subCategoryStates[parentCategoryId] = container.classList.contains('show');
      }
    },
    
    // 하위 카테고리 HTML 생성 (Alpine.js 반응성 문제 해결)
    renderSubCategory(parentCategoryId) {
      const data = this.subCategoryData[parentCategoryId] || [];
      const isLoading = this.loadingStates[parentCategoryId] || false;
      
      if (isLoading) {
        return '<div class="text-muted px-3 py-2"><i class="mdi mdi-loading mdi-spin"></i> 로딩 중...</div>';
      }
      
      if (data.length === 0) {
        return '<div class="text-muted px-3 py-2">하위 항목이 없습니다.</div>';
      }
      
      let html = '';
      
      data.forEach((item) => {
        const categoryType = this.getNextCategoryType(parentCategoryId);
        const categoryId = `${categoryType}_${item.id}`;
        const hasSubItems = categoryType !== 'grade';
        const isExpanded = this.subCategoryStates[categoryId] || false;
        
        html += `
          <div class="form-check category-${categoryType}">
            <input class="form-check-input dynamic-sub-category" 
                   type="radio" 
                   name="car_filter" 
                   id="${categoryId}" 
                   value="${categoryId}" 
                   data-category-id="${categoryId}"
                   data-has-sub="${hasSubItems ? 'true' : 'false'}" />
            <label class="form-check-label d-flex justify-content-between" for="${categoryId}">
              ${item.name} <span class="text-secondary mx-2">${Number(item.count || 0).toLocaleString()}</span>
            </label>
          </div>
        `;
        
        // 하위 카테고리 영역 (JavaScript로 직접 제어, Alpine.js 표현식 제거)
        if (hasSubItems) {
          html += `
            <div class="sub-category ${isExpanded ? 'show' : ''}" data-category-id="${categoryId}">
              <div class="sub-category-content" data-parent="${categoryId}"></div>
            </div>
          `;
        }
      });
      
      return html;
    },
    
    // 이벤트 위임 초기화
    initEventDelegation() {
      const sidebarContainer = document.querySelector('.sider-content');
      if (!sidebarContainer) {
        return;
      }
      
      // 이벤트 위임을 통한 단일 이벤트 리스너
      const delegatedHandler = this.createDelegatedEventHandler();
      sidebarContainer.addEventListener('click', delegatedHandler);
      
      // 이벤트 핸들러 추적
      this.sidebarHandler = delegatedHandler;
      this.sidebarContainer = sidebarContainer;
    },
    
    // 이벤트 핸들러 생성
    createDelegatedEventHandler() {
      return (event) => {
        const target = event.target;
        
        // 이벤트 중복 처리 방지 플래그 확인
        if (target.dataset.eventProcessed === 'true') {
          return;
        }
        
        // 1. 동적 요소 우선 처리
        if (target.matches('.dynamic-sub-category') || target.closest('.dynamic-sub-category')) {
          const input = target.matches('.dynamic-sub-category') ? target : target.closest('.dynamic-sub-category');
          const categoryId = input.dataset.categoryId || input.value;
          const hasSubCategory = input.dataset.hasSub === 'true';
          
          // 이벤트 처리 플래그 설정
          input.dataset.eventProcessed = 'true';
          
          if (hasSubCategory) {
            this.handleCategorySelection(categoryId, event);
          } else {
            this.handleFinalSelection(event);
          }
          
          // 플래그 제거 (다음 클릭을 위해)
          setTimeout(() => {
            input.dataset.eventProcessed = 'false';
          }, 100);
          
          return;
        }
        
        // 2. 정적 요소 처리 (car_filter 라디오) - 동적 요소가 아닌 경우만
        if (target.name === 'car_filter' && !target.classList.contains('dynamic-sub-category')) {
          // 이벤트 처리 플래그 설정
          target.dataset.eventProcessed = 'true';
          
          const categoryId = target.value;
          this.handleCategorySelection(categoryId, event);
          
          // 플래그 제거
          setTimeout(() => {
            target.dataset.eventProcessed = 'false';
          }, 100);
        }
      };
    },
    
    // 정리 함수
    cleanup() {
      if (this.sidebarContainer && this.sidebarHandler) {
        this.sidebarContainer.removeEventListener('click', this.sidebarHandler);
        this.sidebarHandler = null;
        this.sidebarContainer = null;
      }
    },
    
    
    // 필터 정보 파싱
    parseFilterInfo(value, element) {
      const label = element.nextElementSibling?.textContent?.trim() || '';
      
      const parts = value.split('_');
      if (parts.length !== 2) {
        return null;
      }
      
      const [searchType, filterValue] = parts;
      const validTypes = ['maker', 'model', 'detail', 'bp', 'grade'];

      if (!validTypes.includes(searchType)) {
        return null;
      }
      
      return {
        searchType: searchType === 'grade' ? '' : searchType, // grade는 searchType 없음
        filterValue,
        filterType: searchType,
        selectedFilter: {
          type: searchType,
          id: value,
          name: label,
          value: filterValue
        }
      };
    },
    
    // 같은 레벨 카테고리 닫기 (상태와 DOM 동시 업데이트)
    closeSameLevelCategories(categoryId) {
      if (categoryId.startsWith('maker_')) {
        Object.keys(this.subCategoryStates).forEach(key => {
          if (key.startsWith('maker_') && key !== categoryId) {
            this.subCategoryStates[key] = false;
            
            // DOM에서도 숨김 처리
            const container = document.querySelector(`[data-category-id="${key}"]`);
            if (container) {
              container.classList.remove('show');
            }
          }
        });
      }
    },
    
    // 다음 카테고리 타입 결정
    getNextCategoryType(parentCategoryId) {
      const currentType = parentCategoryId.split('_')[0];
      const nextType = this.CATEGORY_TYPE_MAP[currentType] || this.CATEGORY_TYPE_MAP.default;
      
      return nextType;
    },
    

    updateUrlParams() {
      const url = new URL(window.location.href);
      url.searchParams.set('page', this.form.page);
      url.searchParams.set('search_text', this.form.search_text);
      history.replaceState({}, '', url);
    },

  };
}