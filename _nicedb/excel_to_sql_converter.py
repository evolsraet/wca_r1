#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
신차 DB Excel 파일을 SQL INSERT 문으로 변환하는 스크립트
"""

import pandas as pd
import os
from datetime import datetime

def read_excel_file(file_path):
    """Excel 파일을 읽어서 DataFrame으로 반환"""
    try:
        # Excel 파일 읽기 (첫 번째 시트)
        df = pd.read_excel(file_path, engine='openpyxl')
        print(f"Excel 파일 읽기 완료: {len(df)} rows, {len(df.columns)} columns")
        print(f"컬럼명: {list(df.columns)}")
        return df
    except Exception as e:
        print(f"Excel 파일 읽기 오류: {e}")
        return None

def extract_unique_makers(df):
    """DataFrame에서 중복 제거된 제조사 정보 추출"""
    # 한글 컬럼명을 기준으로 제조사 정보 추출
    # 제조사코드, 제조사명, 제조사영문명, 제조국가, 국산/수입구분, 이미지URL
    
    maker_columns = ['제조사코드', '제조사명', '제조사영문명', '제조국가', '국산/수입구분', '이미지URL']
    
    # 필요한 컬럼이 있는지 확인
    available_columns = [col for col in maker_columns if col in df.columns]
    print(f"사용 가능한 제조사 관련 컬럼: {available_columns}")
    
    # 제조사 정보만 추출하고 중복 제거
    makers_df = df[available_columns].drop_duplicates(subset=['제조사코드'])
    makers_df = makers_df.dropna(subset=['제조사코드'])  # 제조사코드가 NULL인 행 제거
    
    # 첫 번째 행이 헤더인 경우 제거 (makerId 같은 값이 들어있을 수 있음)
    if makers_df.iloc[0]['제조사코드'] == 'makerId':
        makers_df = makers_df.iloc[1:]
    
    print(f"추출된 제조사 수: {len(makers_df)}")
    return makers_df

def generate_car_makers_sql(makers_df, output_file):
    """car_makers 테이블용 SQL INSERT 문 생성"""
    sql_statements = []
    
    # SQL 파일 헤더
    header = f"""-- car_makers 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(makers_df)}개 제조사

"""
    sql_statements.append(header)
    
    # INSERT 문 생성
    for index, row in makers_df.iterrows():
        # 값 처리 (NULL 값 처리 포함)
        try:
            maker_id = int(row['제조사코드']) if pd.notna(row['제조사코드']) and str(row['제조사코드']).isdigit() else 'NULL'
        except:
            maker_id = 'NULL'
            
        name = f"'{str(row['제조사명']).replace("'", "''")}'" if pd.notna(row['제조사명']) else 'NULL'
        name_en = f"'{str(row['제조사영문명']).replace("'", "''")}'" if pd.notna(row['제조사영문명']) else 'NULL'
        country = f"'{str(row['제조국가']).replace("'", "''")}'" if pd.notna(row['제조국가']) else 'NULL'
        import_yn = f"'{str(row['국산/수입구분'])}'" if pd.notna(row['국산/수입구분']) else 'NULL'
        logo_url = f"'{str(row['이미지URL']).replace("'", "''")}'" if pd.notna(row['이미지URL']) else 'NULL'
        
        # makerId가 'makerId'인 헤더 행은 건너뛰기
        if str(row['제조사코드']) == 'makerId':
            continue
            
        sql = f"INSERT INTO car_makers (id, name, name_en, country, import_yn, logo_url) VALUES ({maker_id}, {name}, {name_en}, {country}, {import_yn}, {logo_url});"
        sql_statements.append(sql)
    
    # 파일에 저장
    try:
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write('\n'.join(sql_statements))
        print(f"SQL 파일 생성 완료: {output_file}")
        return True
    except Exception as e:
        print(f"SQL 파일 생성 오류: {e}")
        return False

def main():
    """메인 함수"""
    # 파일 경로 설정
    excel_file = "nice_db_20250424.xlsx"
    output_file = "car_makers_insert.sql"
    
    # 현재 디렉토리 확인
    current_dir = os.getcwd()
    print(f"현재 작업 디렉토리: {current_dir}")
    
    # Excel 파일 존재 확인
    if not os.path.exists(excel_file):
        print(f"오류: {excel_file} 파일을 찾을 수 없습니다.")
        return
    
    # Excel 파일 읽기
    print("Excel 파일 읽는 중...")
    df = read_excel_file(excel_file)
    
    if df is None:
        print("Excel 파일 읽기 실패")
        return
    
    # 데이터 미리보기
    print("\n=== 데이터 미리보기 ===")
    print(df.head())
    print(f"\n=== 컬럼 정보 ===")
    print(df.info())
    
    # 제조사 정보 추출
    print("\n제조사 정보 추출 중...")
    makers_df = extract_unique_makers(df)
    
    if len(makers_df) == 0:
        print("제조사 정보를 찾을 수 없습니다.")
        return
    
    # 제조사 데이터 미리보기
    print("\n=== 제조사 데이터 미리보기 ===")
    print(makers_df.head(10))
    
    # SQL INSERT 문 생성
    print(f"\ncar_makers 테이블용 SQL INSERT 문 생성 중...")
    success = generate_car_makers_sql(makers_df, output_file)
    
    if success:
        print(f"\n✅ 완료! {output_file} 파일을 확인하세요.")
        
        # 생성된 파일의 첫 몇 줄 미리보기
        try:
            with open(output_file, 'r', encoding='utf-8') as f:
                lines = f.readlines()[:10]
                print("\n=== 생성된 SQL 파일 미리보기 ===")
                for line in lines:
                    print(line.strip())
                if len(lines) < 10:
                    print("...")
        except Exception as e:
            print(f"파일 미리보기 오류: {e}")
    else:
        print("❌ SQL 파일 생성 실패")

if __name__ == "__main__":
    main()