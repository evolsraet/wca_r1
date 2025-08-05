<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dealer>
 */
class DealerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dealerUserId = \App\Models\User::role('dealer')->inRandomOrder()->first()->id;

        return [
            'user_id' => $dealerUserId,
            'name' => $this->faker->name, // 이름
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d'), // 30~20년 전 랜덤 데이터
            'biz_check' => $this->faker->boolean, // 사업자등록증 확인
            'company' => $this->faker->company, // 회사명 랜덤
            'company_duty' => $this->faker->jobTitle, // 직위 랜덤
            'company_post' => $this->faker->postcode, // 우편번호
            'company_addr1' => $this->faker->streetAddress, // 주소
            'company_addr2' => '123123', // 상세주소
            'receive_post' => $this->faker->postcode, // 받는 사람 우편번호
            'receive_addr1' => $this->faker->streetAddress, // 받는 사람 주소
            'receive_addr2' => '123123', // 받는 사람 상세주소
            'introduce' => $this->faker->paragraph, // 문장, 여기서는 단락으로 대체
            'business_registration_number' => $this->faker->numerify('###-##-#####'), // 사업자등록번호
        ];
    }
}
