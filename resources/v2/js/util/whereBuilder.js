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

    // where 절 문자열을 객체로 파싱
    parse(whereString, modelName = 'articles') {
        const result = {};

        if (!whereString || typeof whereString !== 'string') {
            return result;
        }

        // '|'로 구분된 where 절들을 분리
        const whereClauses = whereString.split('|');

        whereClauses.forEach(clause => {
            const trimmedClause = clause.trim();
            if (!trimmedClause) return;

            // ':'로 컬럼과 값을 분리
            const colonIndex = trimmedClause.indexOf(':');
            if (colonIndex === -1) return;

            const column = trimmedClause.substring(0, colonIndex).trim();
            const value = trimmedClause.substring(colonIndex + 1).trim();

            // 모델명 제거하여 필드명만 추출 (예: articles.category -> category)
            const fieldName = column.includes('.') ? column.split('.').pop() : column;

            if (fieldName) {
                result[fieldName] = value;
            }
        });

        return result;
    },

    // 비교 연산자 확인
    isOperator(value) {
        const operators = ['>', '<', '>=', '<=', 'like', 'whereIn', 'orWhere'];
        return operators.includes(value.split(':')[0]);
    }
}
