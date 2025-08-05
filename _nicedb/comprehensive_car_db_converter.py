#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
신차 DB Excel 파일을 모든 차량 관련 테이블 SQL INSERT 문으로 변환하는 종합 스크립트
"""

import pandas as pd
import os
from datetime import datetime

class CarDBConverter:
    def __init__(self, excel_file):
        self.excel_file = excel_file
        self.df = None
        
    def read_excel_file(self):
        """Excel 파일을 읽어서 DataFrame으로 반환"""
        try:
            self.df = pd.read_excel(self.excel_file, engine='openpyxl')
            print(f"Excel 파일 읽기 완료: {len(self.df)} rows, {len(self.df.columns)} columns")
            print(f"컬럼명: {list(self.df.columns)}")
            
            # 첫 번째 행이 헤더인 경우 제거
            if self.df.iloc[0]['제조사코드'] == 'makerId':
                self.df = self.df.iloc[1:]
                print("헤더 행 제거 완료")
                
            return True
        except Exception as e:
            print(f"Excel 파일 읽기 오류: {e}")
            return False

    def generate_car_makers_sql(self, output_file="car_makers_insert.sql"):
        """car_makers 테이블용 SQL INSERT 문 생성"""
        print("=== car_makers 테이블 SQL 생성 중... ===")
        
        # 제조사 정보 추출 및 중복 제거
        maker_columns = ['제조사코드', '제조사명', '제조사영문명', '제조국가', '국산/수입구분', '이미지URL']
        makers_df = self.df[maker_columns].drop_duplicates(subset=['제조사코드'])
        makers_df = makers_df.dropna(subset=['제조사코드'])
        
        print(f"추출된 제조사 수: {len(makers_df)}")
        
        sql_statements = []
        header = f"""-- car_makers 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(makers_df)}개 제조사

"""
        sql_statements.append(header)
        
        for index, row in makers_df.iterrows():
            try:
                maker_id = int(row['제조사코드']) if pd.notna(row['제조사코드']) and str(row['제조사코드']).isdigit() else 'NULL'
            except:
                continue
                
            name = f"'{str(row['제조사명']).replace("'", "''")}'" if pd.notna(row['제조사명']) else 'NULL'
            name_en = f"'{str(row['제조사영문명']).replace("'", "''")}'" if pd.notna(row['제조사영문명']) else 'NULL'
            country = f"'{str(row['제조국가']).replace("'", "''")}'" if pd.notna(row['제조국가']) else 'NULL'
            import_yn = f"'{str(row['국산/수입구분'])}'" if pd.notna(row['국산/수입구분']) else 'NULL'
            logo_url = f"'{str(row['이미지URL']).replace("'", "''")}'" if pd.notna(row['이미지URL']) else 'NULL'
            
            sql = f"INSERT INTO car_makers (id, name, name_en, country, import_yn, logo_url) VALUES ({maker_id}, {name}, {name_en}, {country}, {import_yn}, {logo_url});"
            sql_statements.append(sql)
        
        self._save_sql_file(sql_statements, output_file)
        return len(makers_df)

    def generate_car_models_sql(self, output_file="car_models_insert.sql"):
        """car_models 테이블용 SQL INSERT 문 생성"""
        print("=== car_models 테이블 SQL 생성 중... ===")
        
        # 모델 정보 추출 및 중복 제거
        model_columns = ['대표모델코드', '대표모델명', '제조사코드']
        models_df = self.df[model_columns].drop_duplicates(subset=['대표모델코드'])
        models_df = models_df.dropna(subset=['대표모델코드'])
        
        print(f"추출된 모델 수: {len(models_df)}")
        
        sql_statements = []
        header = f"""-- car_models 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(models_df)}개 모델

"""
        sql_statements.append(header)
        
        for index, row in models_df.iterrows():
            try:
                model_id = int(row['대표모델코드']) if pd.notna(row['대표모델코드']) and str(row['대표모델코드']).isdigit() else None
                maker_id = int(row['제조사코드']) if pd.notna(row['제조사코드']) and str(row['제조사코드']).isdigit() else None
                
                if model_id is None or maker_id is None:
                    continue
                    
            except:
                continue
                
            name = f"'{str(row['대표모델명']).replace("'", "''")}'" if pd.notna(row['대표모델명']) else 'NULL'
            
            sql = f"INSERT INTO car_models (id, name, maker_id) VALUES ({model_id}, {name}, {maker_id});"
            sql_statements.append(sql)
        
        self._save_sql_file(sql_statements, output_file)
        return len(models_df)

    def generate_car_details_sql(self, output_file="car_details_insert.sql"):
        """car_details 테이블용 SQL INSERT 문 생성"""
        print("=== car_details 테이블 SQL 생성 중... ===")
        
        # 세부 모델 정보 추출 및 중복 제거  
        detail_columns = ['모델명코드', '모델명', '대표모델코드', '짧은모델명', '세대코드', '모델출시일', '모델단종일', '이미지URL']
        details_df = self.df[detail_columns].drop_duplicates(subset=['모델명코드'])
        details_df = details_df.dropna(subset=['모델명코드'])
        
        print(f"추출된 세부 모델 수: {len(details_df)}")
        
        sql_statements = []
        header = f"""-- car_details 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(details_df)}개 세부 모델

"""
        sql_statements.append(header)
        
        for index, row in details_df.iterrows():
            try:
                detail_id = int(row['모델명코드']) if pd.notna(row['모델명코드']) and str(row['모델명코드']).isdigit() else None
                model_id = int(row['대표모델코드']) if pd.notna(row['대표모델코드']) and str(row['대표모델코드']).isdigit() else None
                
                if detail_id is None or model_id is None:
                    continue
                    
            except:
                continue
                
            name = f"'{str(row['모델명']).replace("'", "''")}'" if pd.notna(row['모델명']) else 'NULL'
            short_name = f"'{str(row['짧은모델명']).replace("'", "''")}'" if pd.notna(row['짧은모델명']) else 'NULL'
            generation_name = f"'{str(row['세대코드']).replace("'", "''")}'" if pd.notna(row['세대코드']) else 'NULL'
            start_date = f"'{str(row['모델출시일'])}'" if pd.notna(row['모델출시일']) else 'NULL'
            end_date = f"'{str(row['모델단종일'])}'" if pd.notna(row['모델단종일']) else 'NULL' 
            image_url = f"'{str(row['이미지URL']).replace("'", "''")}'" if pd.notna(row['이미지URL']) else 'NULL'
            
            sql = f"INSERT INTO car_details (id, name, model_id, short_name, generation_name, start_date, end_date, image_url) VALUES ({detail_id}, {name}, {model_id}, {short_name}, {generation_name}, {start_date}, {end_date}, {image_url});"
            sql_statements.append(sql)
        
        self._save_sql_file(sql_statements, output_file)
        return len(details_df)

    def generate_car_bps_sql(self, output_file="car_bps_insert.sql"):
        """car_bps 테이블용 SQL INSERT 문 생성"""
        print("=== car_bps 테이블 SQL 생성 중... ===")
        
        # 제원 정보 추출 및 중복 제거
        bp_columns = ['제원코드', '제원명', '모델명코드']
        bps_df = self.df[bp_columns].drop_duplicates(subset=['제원코드'])
        bps_df = bps_df.dropna(subset=['제원코드'])
        
        print(f"추출된 제원 수: {len(bps_df)}")
        
        sql_statements = []
        header = f"""-- car_bps 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(bps_df)}개 제원

"""
        sql_statements.append(header)
        
        for index, row in bps_df.iterrows():
            try:
                bp_id = int(row['제원코드']) if pd.notna(row['제원코드']) and str(row['제원코드']).isdigit() else None
                detail_id = int(row['모델명코드']) if pd.notna(row['모델명코드']) and str(row['모델명코드']).isdigit() else None
                
                if bp_id is None or detail_id is None:
                    continue
                    
            except:
                continue
                
            name = f"'{str(row['제원명']).replace("'", "''")}'" if pd.notna(row['제원명']) else 'NULL'
            
            sql = f"INSERT INTO car_bps (id, name, detail_id) VALUES ({bp_id}, {name}, {detail_id});"
            sql_statements.append(sql)
        
        self._save_sql_file(sql_statements, output_file)
        return len(bps_df)

    def generate_car_grades_sql(self, output_file="car_grades_insert.sql"):
        """car_grades 테이블용 SQL INSERT 문 생성"""
        print("=== car_grades 테이블 SQL 생성 중... ===")
        
        # 등급 정보 추출 및 중복 제거
        grade_columns = ['등급ID', '등급명', '제원코드', '등급구분명', '차종코드', '차종명', 
                        '차형코드', '차형', '용도코드', '용도명', '배기량', '변속기', 
                        '변속기(자동)', '변속기(수동)', '연료', '승차정원', '등급신차가격', 
                        '등급시작일', '등급종료일']
        grades_df = self.df[grade_columns].drop_duplicates(subset=['등급ID'])
        grades_df = grades_df.dropna(subset=['등급ID'])
        
        print(f"추출된 등급 수: {len(grades_df)}")
        
        sql_statements = []
        header = f"""-- car_grades 테이블 INSERT 문
-- 생성일: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
-- 총 {len(grades_df)}개 등급

"""
        sql_statements.append(header)
        
        for index, row in grades_df.iterrows():
            try:
                grade_id = int(row['등급ID']) if pd.notna(row['등급ID']) and str(row['등급ID']).isdigit() else None
                bp_id = int(row['제원코드']) if pd.notna(row['제원코드']) and str(row['제원코드']).isdigit() else None
                
                if grade_id is None or bp_id is None:
                    continue
                    
            except:
                continue
            
            # 각 컬럼 값 처리
            name = f"'{str(row['등급명']).replace("'", "''")}'" if pd.notna(row['등급명']) else 'NULL'
            type_name = f"'{str(row['등급구분명']).replace("'", "''")}'" if pd.notna(row['등급구분명']) else 'NULL'
            car_type_id = f"'{str(row['차종코드'])}'" if pd.notna(row['차종코드']) else 'NULL'
            car_type_name = f"'{str(row['차종명']).replace("'", "''")}'" if pd.notna(row['차종명']) else 'NULL'
            shape_category_id = f"'{str(row['차형코드'])}'" if pd.notna(row['차형코드']) else 'NULL'
            shape_category_name = f"'{str(row['차형']).replace("'", "''")}'" if pd.notna(row['차형']) else 'NULL'
            purpose_id = f"'{str(row['용도코드'])}'" if pd.notna(row['용도코드']) else 'NULL'
            purpose_name = f"'{str(row['용도명']).replace("'", "''")}'" if pd.notna(row['용도명']) else 'NULL'
            displacement = f"'{str(row['배기량'])}'" if pd.notna(row['배기량']) else 'NULL'
            gearbox = f"'{str(row['변속기']).replace("'", "''")}'" if pd.notna(row['변속기']) else 'NULL'
            gearbox_auto = f"'{str(row['변속기(자동)'])}'" if pd.notna(row['변속기(자동)']) else 'NULL'
            gearbox_manual = f"'{str(row['변속기(수동)'])}'" if pd.notna(row['변속기(수동)']) else 'NULL'
            fuel = f"'{str(row['연료']).replace("'", "''")}'" if pd.notna(row['연료']) else 'NULL'
            seating_capacity = f"'{str(row['승차정원'])}'" if pd.notna(row['승차정원']) else 'NULL'
            grade_newcar_price = f"'{str(row['등급신차가격'])}'" if pd.notna(row['등급신차가격']) else 'NULL'
            start_date = f"'{str(row['등급시작일'])}'" if pd.notna(row['등급시작일']) else 'NULL'
            end_date = f"'{str(row['등급종료일'])}'" if pd.notna(row['등급종료일']) else 'NULL'
            
            sql = f"""INSERT INTO car_grades (id, name, bp_id, type_name, car_type_id, car_type_name, shape_category_id, shape_category_name, purpose_id, purpose_name, displacement, gearbox, gearbox_auto, gearbox_manual, fuel, seating_capacity, grade_newcar_price, start_date, end_date, created_at) VALUES ({grade_id}, {name}, {bp_id}, {type_name}, {car_type_id}, {car_type_name}, {shape_category_id}, {shape_category_name}, {purpose_id}, {purpose_name}, {displacement}, {gearbox}, {gearbox_auto}, {gearbox_manual}, {fuel}, {seating_capacity}, {grade_newcar_price}, {start_date}, {end_date}, NOW());"""
            sql_statements.append(sql)
        
        self._save_sql_file(sql_statements, output_file)
        return len(grades_df)

    def _save_sql_file(self, sql_statements, output_file):
        """SQL 문을 파일로 저장"""
        try:
            with open(output_file, 'w', encoding='utf-8') as f:
                f.write('\n'.join(sql_statements))
            print(f"✅ SQL 파일 생성 완료: {output_file}")
        except Exception as e:
            print(f"❌ SQL 파일 생성 오류: {e}")

    def generate_all_tables(self):
        """모든 차량 관련 테이블의 SQL INSERT 문 생성"""
        if not self.read_excel_file():
            return False

        print(f"\n🚀 총 {len(self.df)}개 레코드로부터 차량 DB SQL 생성 시작")
        print("=" * 60)

        results = {}
        results['car_makers'] = self.generate_car_makers_sql()
        results['car_models'] = self.generate_car_models_sql()
        results['car_details'] = self.generate_car_details_sql()
        results['car_bps'] = self.generate_car_bps_sql()
        results['car_grades'] = self.generate_car_grades_sql()

        print("\n" + "=" * 60)
        print("🎉 모든 테이블 SQL 생성 완료!")
        print("-" * 60)
        for table, count in results.items():
            print(f"{table:15}: {count:6,}개 레코드")
        print("-" * 60)
        
        return True

def main():
    """메인 함수"""
    excel_file = "nice_db_20250424.xlsx"
    
    if not os.path.exists(excel_file):
        print(f"❌ 오류: {excel_file} 파일을 찾을 수 없습니다.")
        return

    converter = CarDBConverter(excel_file)
    converter.generate_all_tables()

if __name__ == "__main__":
    main()