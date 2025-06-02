// 게시판 공통 기능 및 유틸리티 (Alpine Store)

export default {
    // 기본 상태
    boardId: null,
    loading: false,
    errors: {},

    // 초기화
    init(boardId) {
        this.boardId = boardId;
    },
};
