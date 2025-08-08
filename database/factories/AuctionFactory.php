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

        $carGrade  = \App\Models\CarGrade::inRandomOrder()->first();
        $carBp     = \App\Models\CarBp::where('id', $carGrade->bp_id)->first();
        $carDetail = \App\Models\CarDetail::where('id', $carBp->detail_id)->first();
        $carModel  = \App\Models\CarModel::where('id', $carDetail->model_id)->first();
        $carMaker  = \App\Models\CarMaker::where('id', $carModel->maker_id)->first();

        $car = [
            "car_maker_id"        => $carMaker->id,
            "car_model_id"        => $carModel->id,
            "car_detail_id"       => $carDetail->id,
            "car_bp_id"           => $carBp->id,
            "car_grade_id"        => $carGrade->id,
            'car_maker'           => $carMaker->name,
            'car_model'           => $carModel->name,
            'car_model_sub'       => $carDetail->name,
            'car_grade'           => $carBp->name,
            'car_grade_sub'       => $carGrade->name,
            'car_year'            => $this->faker->year,
            'car_first_reg_date'  => $this->faker->dateTimeBetween('-10 years', '+1 month'),
            'car_mission'         => $this->faker->randomElement(['자동', '수동']),
            'car_fuel'            => $this->faker->randomElement(['가솔린', '디젤', '하이브리드', '전기']),
            'car_price_now'       => $this->faker->randomNumber(8),
            'car_price_now_whole' => $this->faker->randomNumber(8),
            'car_km'              => $this->faker->randomNumber(6, true),
            'car_thumbnail'       => $this->faker->randomElement([
                "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2022%2F05%2Fhyundai-motor-company-sonata-discontinued-01.jpg?q=75&w=800&cbr=1&fit=max",
                "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2021%2F07%2FKia-release-new-sportage-suv-model-design-price-spec-info-twtw.jpg?w=960&cbr=1&q=90&fit=max",
                "https://file.carisyou.com/upload/2020/07/15/EDITOR_202007150443005100.jpg",
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqgDnOXZPCRL7PDb1Kln4-MPxmAfWa8zzSZA&s",
                "https://cdn.motorgraph.com/news/photo/202211/30950_97376_454.jpg",
            ]),
        ];

        $result = [
            "car_maker_id"        => $carMaker->id,
            "car_model_id"        => $carModel->id,
            "car_detail_id"       => $carDetail->id,
            "car_bp_id"           => $carBp->id,
            "car_grade_id"        => $carGrade->id,
                        
            'auction_type'        => $this->faker->boolean,
            'user_id'             => $user->id,
            'owner_name'          => $this->faker->name,
            'car_no'              => $this->faker->bothify('##??####'),
            'status'              => $status,
            'region'              => $this->faker->word,
            'addr_post'           => $this->faker->postcode,
            'addr1'               => $this->faker->address,
            'addr2'               => '112',
            'bank'                => $this->faker->word,
            'account'             => $this->faker->bankAccountNumber,
            'memo'                => $this->faker->text,
            'verified'            => $this->faker->boolean,
            'hit'                 => $this->faker->randomDigit,
            'diag_first_at'       => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'diag_second_at'      => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'car_maker'           => $car['car_maker'],
            'car_model'           => $car['car_model'],
            'car_model_sub'       => $car['car_model_sub'],
            'car_grade'           => $car['car_grade'],
            'car_grade_sub'       => $car['car_grade_sub'],
            'car_year'            => $car['car_year'],
            'car_first_reg_date'  => $this->faker->randomNumber(5, true),
            'car_mission'         => $car['car_mission'],
            'car_fuel'            => $car['car_fuel'],
            'car_price_now'       => $this->faker->randomNumber(8),
            'car_price_now_whole' => $this->faker->randomNumber(8),
            'car_km'              => $car['car_km'],
            'car_thumbnail'       => $car['car_thumbnail'],
            'personal_id_number'  => $this->faker->bothify('#############'),
        ];

        if ($status === 'chosen') {
            $result['choice_at'] = $this->faker->dateTimeBetween('-1 month', '+1 month');
            $result['final_price'] = $this->faker->randomNumber(5, true);
        }

        if($status === 'ing') {
            $result['final_at'] = now()->addDays(config('days.auction_day'));
        }

        if($status === 'done' || $status === 'dlvr') {
            $result['final_price'] = $this->faker->randomNumber(5, true);
        }

        if($status !== 'ask') {
            $result['is_accident'] = 1;
        }

        return $result;
    }
}
