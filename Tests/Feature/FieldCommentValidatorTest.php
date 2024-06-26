<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Validators\FieldCommentValidator;
use Illuminate\Support\Facades\DB;

class FieldCommentValidatorTest extends TestCase
{
    public function testValidationErrorMessage()
    {
        $translator = app('translator');
        $data = ['car_no' => '']; // 빈 값을 설정하여 'required' 규칙을 트리거
        $rules = ['car_no' => 'required'];
        $messages = [];
        $customAttributes = [];
        $validator = new FieldCommentValidator($translator, $data, $rules, $messages, $customAttributes);
        $validator->setTable('auctions');

        $this->assertFalse($validator->passes(), 'Validator should fail due to empty required field.');
        $errors = $validator->errors();
        echo $errors->first('car_no'); // 에러 메시지 출력
    }
}
