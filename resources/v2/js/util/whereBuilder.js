// Where 절 빌더 유틸리티
export default {
    // 메인 빌더 함수
    build(whereFilters, modelName = 'articles') {
        const whereClauses = [];

        Object.entries(whereFilters).forEach(([key, value]) => {
            if (value == null) return;

            const stringValue = String(value).trim();
            if (stringValue === '') return;

            // _null 값 처리 (null 비교)
            if (stringValue === '_null') {
                whereClauses.push(`${modelName}.${key}:_null`);
                return;
            }

            // 비교 연산자 포함된 값 처리
            if (stringValue.includes(':') && this.isOperator(stringValue)) {
                whereClauses.push(`${modelName}.${key}:${stringValue}`);
            } else {
                // 단순 등호 비교
                whereClauses.push(`${modelName}.${key}:${stringValue}`);
            }
        });

        // '|'로 구분하여 반환 (API 스펙)
        return whereClauses.join('|');
    },

    // 비교 연산자 확인
    isOperator(value) {
        const operators = ['>', '<', '>=', '<=', 'like', 'whereIn', 'orWhere'];
        return operators.includes(value.split(':')[0]);
    }
}
