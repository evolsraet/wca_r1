<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    public function definition(): array
    {
        $user = \App\Models\User::role('user')->inRandomOrder()->first();
        $status = $this->faker->randomElement(array_keys((new Auction)->enums['status']));

        $tmpCars = [
            [
                "makerId" => "001",
                "makerNm" => "현대",
                "classModelld" => "101",
                "classModelNm" => "쏘나타",
                "modelld" => "301",
                "modelNm" => "더 뉴 쏘나타",
                "carName" => "현대 더 뉴 쏘나타 가솔린 2000cc 프리미엄",
                "year" => "2022",
                "detailedModel" => "더 뉴 쏘나타 프리미엄",
                "grade" => "프리미엄",
                "subGrade" => "프리미엄 플러스",
                "firstRegistrationDate" => "2022-03-15",
                "engineCapacity" => "2000cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "0회",
                "recallHistory" => "없음",
                "currentPrice" => 1800, // 시세 숫자만 입력
                "km" => "2.4",
                "thumbnail" => "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2022%2F05%2Fhyundai-motor-company-sonata-discontinued-01.jpg?q=75&w=800&cbr=1&fit=max"
            ],
            [
                "makerId" => "002",
                "makerNm" => "기아",
                "classModelld" => "102",
                "classModelNm" => "스포티지",
                "modelld" => "302",
                "modelNm" => "더 뉴 스포티지",
                "carName" => "기아 더 뉴 스포티지 디젤 2000cc 노블레스",
                "year" => "2021",
                "detailedModel" => "더 뉴 스포티지 노블레스",
                "grade" => "노블레스",
                "subGrade" => "노블레스 스페셜",
                "firstRegistrationDate" => "2021-08-10",
                "engineCapacity" => "2000cc",
                "fuelType" => "디젤",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "1회",
                "recallHistory" => "없음",
                "currentPrice" => 2000, // 시세 숫자만 입력,
                "km" => "3.6",
                "thumbnail" => "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2021%2F07%2FKia-release-new-sportage-suv-model-design-price-spec-info-twtw.jpg?w=960&cbr=1&q=90&fit=max"
            ],
            [
                "makerId" => "003",
                "makerNm" => "르노코리아",
                "classModelld" => "103",
                "classModelNm" => "SM6",
                "modelld" => "303",
                "modelNm" => "SM6",
                "carName" => "르노코리아 SM6 가솔린 1600cc LE",
                "year" => "2020",
                "detailedModel" => "SM6 LE",
                "grade" => "LE",
                "subGrade" => "LE 프리미엄",
                "firstRegistrationDate" => "2020-11-25",
                "engineCapacity" => "1600cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "1회",
                "tuningHistory" => "0회",
                "recallHistory" => "1회",
                "currentPrice" => 1200, // 시세 숫자만 입력,
                "km" => "1.5",
                "thumbnail" => "https://file.carisyou.com/upload/2020/07/15/EDITOR_202007150443005100.jpg"
            ],
            [
                "makerId" => "004",
                "makerNm" => "쌍용",
                "classModelld" => "104",
                "classModelNm" => "코란도",
                "modelld" => "304",
                "modelNm" => "코란도",
                "carName" => "쌍용 코란도 디젤 2200cc 어드벤처",
                "year" => "2019",
                "detailedModel" => "코란도 어드벤처",
                "grade" => "어드벤처",
                "subGrade" => "어드벤처 플러스",
                "firstRegistrationDate" => "2019-06-30",
                "engineCapacity" => "2200cc",
                "fuelType" => "디젤",
                "transmission" => "자동",
                "useChangeHistory" => "2회",
                "tuningHistory" => "1회",
                "recallHistory" => "없음",
                "currentPrice" => 1000, // 시세 숫자만 입력,
                "km" => "3.2",
                "thumbnail" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqgDnOXZPCRL7PDb1Kln4-MPxmAfWa8zzSZA&s"
            ],
            [
                "makerId" => "005",
                "makerNm" => "제네시스",
                "classModelld" => "105",
                "classModelNm" => "G80",
                "modelld" => "305",
                "modelNm" => "G80",
                "carName" => "제네시스 G80 가솔린 3300cc AWD",
                "year" => "2023",
                "detailedModel" => "G80 AWD 프리미엄",
                "grade" => "프리미엄",
                "subGrade" => "프리미엄 럭셔리",
                "firstRegistrationDate" => "2023-02-15",
                "engineCapacity" => "3300cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "0회",
                "recallHistory" => "없음",
                "currentPrice" => 4500, // 시세 숫자만 입력,
                "km" => "2.6",
                "thumbnail" => "https://cdn.motorgraph.com/news/photo/202211/30950_97376_454.jpg"
            ]
        ];

        $car = $tmpCars[array_rand($tmpCars)];

        $result = [
            'user_id' => $user->id,
            'owner_name' => $this->faker->name,
            'car_no' => $this->faker->bothify('??###'),
            'status' => $status,
            'region' => $this->faker->word,
            'addr_post' => $this->faker->postcode,
            'addr1' => $this->faker->address,
            'addr2' => '112',
            'bank' => $this->faker->word,
            'account' => $this->faker->bankAccountNumber,
            'memo' => $this->faker->text,
            'verified' => $this->faker->boolean,
            'hit' => $this->faker->randomDigit,
            'diag_first_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'diag_second_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'car_maker' => $car['makerNm'],
            'car_model' => $car['modelNm'],
            'car_model_sub' => $car['subGrade'],
            'car_grade' => $car['grade'],
            'car_grade_sub' => $car['subGrade'],
            'car_year' => $car['year'],
            'car_first_reg_date' => $this->faker->randomNumber(5, true),
            'car_mission' => $car['transmission'],
            'car_fuel' => $car['fuelType'],
            'car_price_now' => '0',
            'car_price_now_whole' => '0',
            'car_km' => $car['km'],
            'car_thumbnail' => $car['thumbnail'],
        ];

        if ($status === 'chosen') {
            $result['choice_at'] = $this->faker->dateTimeBetween('-1 month', '+1 month');
            $result['final_price'] = $this->faker->randomNumber(5, true);
        }

        if($status === 'ing') {
            $result['final_at'] = now()->addDays(env('REAUCTION_DAY'));
        }

        if($status === 'done') {
            $result['final_price'] = $this->faker->randomNumber(5, true);
        }

        return $result;
    }
}
