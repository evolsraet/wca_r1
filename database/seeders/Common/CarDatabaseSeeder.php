<?php

namespace Database\Seeders\Common;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CarDatabaseSeeder extends Seeder
{
    /**
     * 차량 DB 시드 실행
     * 
     * 신차 DB Excel 파일에서 변환된 SQL INSERT 문을 실행합니다.
     * 외래키 제약조건을 고려하여 순서대로 실행됩니다.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('🚀 차량 DB 시드 시작...');
        
        // SQL 파일들의 경로
        $sqlPath = database_path('../_nicedb/');
        
        // 실행 순서 (외래키 의존성 고려)
        $sqlFiles = [
            'car_makers_insert.sql',   // 제조사 (91개)
            'car_models_insert.sql',   // 대표 모델 (942개)
            'car_details_insert.sql',  // 세부 모델 (1,813개)
            'car_bps_insert.sql',      // 제원 (8,071개)
            'car_grades_insert.sql',   // 등급 (25,288개)
        ];
        
        $totalRecords = 0;
        
        foreach ($sqlFiles as $sqlFile) {
            $filePath = $sqlPath . $sqlFile;
            
            if (!File::exists($filePath)) {
                $this->command->error("❌ 파일을 찾을 수 없습니다: {$sqlFile}");
                continue;
            }
            
            $this->command->info("📋 실행 중: {$sqlFile}");
            
            try {
                // 파일 내용 읽기
                $sql = File::get($filePath);
                
                // 헤더 주석 제거 및 SQL 문 추출
                $statements = $this->extractSqlStatements($sql);
                
                if (empty($statements)) {
                    $this->command->warn("⚠️  실행할 SQL 문을 찾을 수 없습니다: {$sqlFile}");
                    continue;
                }
                
                // 배치로 실행 (메모리 절약)
                $batchSize = 100;
                $batches = array_chunk($statements, $batchSize);
                $recordCount = 0;
                
                DB::beginTransaction();
                
                foreach ($batches as $batchIndex => $batch) {
                    try {
                        foreach ($batch as $statement) {
                            DB::statement($statement);
                            $recordCount++;
                        }
                        
                        // 진행률 표시
                        if (($batchIndex + 1) % 10 === 0 || ($batchIndex + 1) === count($batches)) {
                            $progress = ($batchIndex + 1) * $batchSize;
                            $total = count($statements);
                            $this->command->info("   진행: {$progress}/{$total} 레코드");
                        }
                        
                    } catch (\Exception $e) {
                        $this->command->error("   배치 실행 오류: " . $e->getMessage());
                        break;
                    }
                }
                
                DB::commit();
                
                $totalRecords += $recordCount;
                $this->command->info("✅ 완료: {$sqlFile} ({$recordCount}개 레코드)");
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("❌ 실행 실패: {$sqlFile}");
                $this->command->error("   오류: " . $e->getMessage());
                
                // 중복키 오류인 경우 계속 진행
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $this->command->warn("   중복 데이터 감지 - 다음 파일로 계속 진행합니다.");
                    continue;
                }
                
                // 다른 오류인 경우 중단
                break;
            }
        }
        
        $this->command->info('');
        $this->command->info("🎉 차량 DB 시드 완료!");
        $this->command->info("📊 총 {$totalRecords}개 레코드가 삽입되었습니다.");
        
        // 결과 확인
        $this->showInsertionResults();
    }
    
    /**
     * SQL 파일에서 SQL 문 추출 (INSERT, UPDATE 포함)
     *
     * @param string $sql
     * @return array
     */
    private function extractSqlStatements(string $sql): array
    {
        $statements = [];
        $lines = explode("\n", $sql);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // SQL 문 추출 (INSERT, UPDATE, DELETE 등 + 주석과 빈 줄 제외)
            if ((str_starts_with($line, 'INSERT INTO') || 
                 str_starts_with($line, 'UPDATE ') || 
                 str_starts_with($line, 'DELETE FROM ')) && 
                str_ends_with($line, ';')) {
                $statements[] = $line;
            }
        }
        
        return $statements;
    }
    
    /**
     * 삽입 결과 확인 및 표시
     *
     * @return void
     */
    private function showInsertionResults()
    {
        $tables = [
            'car_makers' => '제조사',
            'car_models' => '대표 모델', 
            'car_details' => '세부 모델',
            'car_bps' => '제원',
            'car_grades' => '등급'
        ];
        
        $this->command->info('');
        $this->command->info('📋 테이블별 삽입 결과:');
        $this->command->info('────────────────────────────');
        
        foreach ($tables as $table => $description) {
            try {
                $count = DB::table($table)->count();
                $this->command->info(sprintf('%-15s: %6s개 (%s)', $table, number_format($count), $description));
            } catch (\Exception $e) {
                $this->command->error("❌ {$table}: 테이블 조회 실패");
            }
        }
        
        $this->command->info('────────────────────────────');
    }
}