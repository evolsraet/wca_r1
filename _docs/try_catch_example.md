<?php

public function test()
{
    try {
        // 디비 연결 확인
        if( !DB::connection()->getPdo() ) {
            throw new \Exception("디비 연결 실패", 1);
        }

        // 인풋 확인
        $input = $request->all();
        if( !isset($input['id']) OR !isset($input['password'])) {
            throw new \Exception("필수값이 없습니다.", 400);

        // 계정 있는지 확인
        if( !User::where('id', $input['id'])->exists() ) {
            throw new \Exception("계정이 없습니다.", 401);
        }

        // 유효기간 확인
        $user = User::where('id', $input['id'])->first();
        if( $user->valid_period < now() ) {
            throw new \Exception("유효기간이 만료되었습니다.");
        }   

        // 비밀번호 여부
        if( $input['password'] !== $user->password ) {
            throw new \Exception("비밀번호가 다릅니다.");
        }

        // 활성화 여부
        // TODO: 활성화 여부 확인
        if( $user->status == 0 ) {
            throw new \Exception("활성화 상태가 아닙니다.");
        }

        return response()->api(null, "정상.", 'ok', 200);
    } catch (\Exception $e) {
        if( $e->getCode() == 1 ) {
            Log::error("디비 연결 실패", ['message' => $e->getMessage()]);
            return response()->api(null, "컨트롤러 response" . $e->getMessage(), 'error', 500);
        }
        return response()->api(null, "컨트롤러 response" . $e->getMessage(), 'error', $e->getCode());
    }
}

