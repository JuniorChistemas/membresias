<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\TypeMembership;
use App\Models\PaymentMethod;
use App\Models\Coach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MonthlyPayment>
 */
class MonthlyPaymentFactory extends Factory
{
    public function definition(): array
    {
        $typeMembership = TypeMembership::inRandomOrder()->first() ?? TypeMembership::factory()->create();
        $months = $this->faker->numberBetween(1, 12);
        $price = $typeMembership->price;
        $total = $price * $months;

        return [
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'type_membership_id' => $typeMembership->id,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()?->id ?? PaymentMethod::factory(),
            'coach_id' => Coach::inRandomOrder()->first()?->id ?? Coach::factory(),

            'months_total' => $months,
            'price' => $price,
            'total' => $total,
            'date_registration' => $this->faker->date(),
            'status' => $this->faker->boolean(),
        ];
    }
}
