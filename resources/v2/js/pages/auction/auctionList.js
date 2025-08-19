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

    // 하위 카테고리 상태 관리
    subCategoryStates: {},
    
    // 하위 카테고리 데이터 저장
    subCategoryData: {},
    
    // 로딩 상태 관리
    loadingStates: {},
    
    // 이벤트 리스너 추적 (메모리 누수 방지)
    eventListenersMap: new WeakMap(),
    
    // DOM 변경 감지를 위한 MutationObserver
    mutationObserver: null,

    // 선택된 필터 정보
    selectedFilter: {
      type: null, // 'maker', 'model', 'detail', 'bp', 'grade'
      id: null,
      name: null,
      value: null
    },
    
    // 카테고리 타입 매핑 상수 (성능 최적화)
    CATEGORY_TYPE_MAP: {
      maker: 'model',
      model: 'detail',
      detail: 'bp', 
      bp: 'grade',
      default: 'grade'
    },
    

    init() {
      const params = new URLSearchParams(window.location.search);
      const page = parseInt(params.get('page')) || 1;
      const search = params.get('search_text') || '';

      this.form.page = page;
      this.form.search_text = search;
      
      // DOM 변경 감지 및 이벤트 위임 초기화
      this.initEventDelegation();
      this.initMutationObserver();

      this.getAuctionList();

      window.addEventListener('beforeunload', () => this.removeEventListeners());
      window.addEventListener('pagehide', () => this.removeEventListeners());
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

        // console.log('response', response);

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
    
    // 카테고리 선택 처리 (중복 로직 분리 및 최적화)
    async handleCategorySelection(categoryId, event) {
      event.stopPropagation();
      
      const selectedRadio = event.target;
      if (!selectedRadio.checked) return;
      
      // 필터 정보 파싱 (공통 로직)
      const filterInfo = this.parseFilterInfo(selectedRadio.value, selectedRadio);
      console.log('filterInfo', filterInfo);
      if (!filterInfo) return;
      
      this.selectedFilter = filterInfo.selectedFilter;
      
      // 하위 카테고리 상태 토글 (Alpine.js 반응성 보장)
      this.subCategoryStates[categoryId] = !this.subCategoryStates[categoryId];
      
      // 하위 카테고리 로드 (중간 단계)
      if (filterInfo.searchType && filterInfo.filterValue && this.subCategoryStates[categoryId]) {
        await this.fetchSubCategory(filterInfo.searchType, filterInfo.filterValue, categoryId);
        
        // Alpine.js 반응성 강제 트리거
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
    
    // API를 통해 하위 카테고리 데이터 가져오기
    async fetchSubCategory(filterType, filterValue, parentCategoryId) {
      // 로딩 상태 시작
      this.loadingStates[parentCategoryId]    = true;
      this.subCategoryData[parentCategoryId]  = [];
      
      try {        
        const response = await Alpine.store('api').get('/api/auctions/car-sub-category', {
          car_filter_type: filterType,
          car_filter_value: filterValue
        });
        
        if (response.data && response.data.data) {
          this.subCategoryData[parentCategoryId] = response.data.data;
          
          // Alpine.js 반응성 트리거 - 데이터 변경 알림
          this.$nextTick(() => {
            // 수동으로 DOM 업데이트 강제 실행
            this.triggerSubCategoryRender(parentCategoryId);
          });
        }
      } catch (error) {
        // alert('하위 카테고리를 불러오는데 실패했습니다. 다시 시도해주세요.');
      } finally {
        this.loadingStates[parentCategoryId] = false;
      }
    },
    
    // 하위 카테고리 렌더링 강제 트리거
    triggerSubCategoryRender(parentCategoryId) {
      
      // 해당 카테고리의 x-html 영역 찾기
      const subCategoryElement = document.querySelector(`[x-html*="renderSubCategory('${parentCategoryId}'"]`);
      
      if (subCategoryElement) {
        // Alpine.js의 x-html 다시 평가 강제 실행
        const html = this.renderSubCategory(parentCategoryId, 1);
        subCategoryElement.innerHTML = html;
      }
    },
    
    // 하위 카테고리 HTML 생성
    renderSubCategory(parentCategoryId) {
      
      const data      = this.subCategoryData[parentCategoryId] || [];
      const isLoading = this.loadingStates[parentCategoryId] || false;
      
      if (isLoading) {
        return '<div class="text-muted px-3 py-2"><i class="mdi mdi-loading mdi-spin"></i> 로딩 중...</div>';
      }
      
      if (data.length === 0) {
        return '<div class="text-muted px-3 py-2">하위 항목이 없습니다.</div>';
      }
      
      let html = '';
      
      data.forEach((item, index) => {
        const categoryType = this.getNextCategoryType(parentCategoryId);
        const categoryId = `${categoryType}_${item.id}`;
        const hasSubItems = categoryType !== 'grade';
        
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
        
        // 하위 카테고리 영역 (재귀적으로 처리 가능하도록 준비)
        if (hasSubItems) {
          html += `
            <div class="sub-category" :class="{ show: subCategoryStates?.['${categoryId}'] || false }">
              <div x-html="renderSubCategory('${categoryId}')"></div>
            </div>
          `;
        }
      });
      
      return html;
    },
    
    // 이벤트 위임 패턴으로 초기화 (성능 최적화)
    initEventDelegation() {
      const sidebarContainer = document.querySelector('.sider-content');
      if (!sidebarContainer) {
        console.warn('사이드바 컨테이너를 찾을 수 없습니다.');
        return;
      }
      
      // 이벤트 위임을 통한 단일 이벤트 리스너
      const delegatedHandler = this.createDelegatedEventHandler();
      sidebarContainer.addEventListener('click', delegatedHandler);
      
      // WeakMap을 사용하여 이벤트 리스너 추적
      this.eventListenersMap.set(sidebarContainer, {
        eventType: 'click',
        handler: delegatedHandler,
        element: sidebarContainer
      });
      
    },
    
    // MutationObserver로 DOM 변경 감지 및 자동 처리
    initMutationObserver() {
      const sidebarContainer = document.querySelector('.sider-content');
      if (!sidebarContainer) return;
      
      this.mutationObserver = new MutationObserver((mutations) => {
        let hasNewDynamicElements = false;
        
        mutations.forEach(mutation => {
          if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
            // 새로 추가된 노드 중 dynamic-sub-category가 있는지 확인
            mutation.addedNodes.forEach(node => {
              if (node.nodeType === Node.ELEMENT_NODE) {
                const dynamicElements = node.querySelectorAll?.('.dynamic-sub-category') || [];
                if (dynamicElements.length > 0 || node.classList?.contains('dynamic-sub-category')) {
                  hasNewDynamicElements = true;
                }
              }
            });
          }
        });
      });
      
      // 하위 트리 전체를 관찰
      this.mutationObserver.observe(sidebarContainer, {
        childList: true,
        subtree: true
      });
      
    },
    
    // 이벤트 위임을 위한 핸들러 생성
    createDelegatedEventHandler() {
      return (event) => {
        const target = event.target;
        
        // 이벤트 중복 처리 방지 플래그 확인
        if (target.dataset.eventProcessed === 'true') {
          return;
        }
        
        // dynamic-sub-category 클래스가 있는 input인지 확인
        if (target.matches('.dynamic-sub-category') || target.closest('.dynamic-sub-category')) {
          const input           = target.matches('.dynamic-sub-category') ? target : target.closest('.dynamic-sub-category');
          const categoryId      = input.dataset.categoryId;
          const hasSubCategory  = input.dataset.hasSub === 'true';
          
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
        
        // 기존 정적 요소들 처리 (car_filter 라디오) - 동적 요소가 아닌 경우만
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
    
    // 메모리 누수 방지를 위한 이벤트 리스너 정리
    removeEventListeners() {
      this.eventListenersMap.forEach((listenerInfo, element) => {
        try {
          element.removeEventListener(listenerInfo.eventType, listenerInfo.handler);
        } catch (error) {
          console.warn('이벤트 리스너 제거 실패:', error);
        }
      });
      
      this.eventListenersMap.clear();
      
      if (this.mutationObserver) {
        this.mutationObserver.disconnect();
        this.mutationObserver = null;
      }
    },
    
    // 최종 레벨 필터 처리 (등급 등)
    async handleFinalSelection(event) {
      if (!event.target.checked) return;
      
      const filterInfo = this.parseFilterInfo(event.target.value, event.target);
      if (!filterInfo) return;
      
      this.selectedFilter = filterInfo.selectedFilter;
      
      this.form.page = 1;
      this.getAuctionList();
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
    
    // 같은 레벨 카테고리 닫기 (분리된 로직)
    closeSameLevelCategories(categoryId) {
      if (categoryId.startsWith('maker_')) {
        Object.keys(this.subCategoryStates).forEach(key => {
          if (key.startsWith('maker_') && key !== categoryId) {
            this.subCategoryStates[key] = false;
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