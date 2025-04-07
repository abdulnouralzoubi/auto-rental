<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // 'user_id',
    // 'car_id',
    // 'rating',
    // 'comment',
    public function run(): void
    {
        $reviews = [
            [
                'user_id' => 3,
                'car_id' => 1,
                'rating' => 5,
                'comment' => 'The car was in perfect condition and made my trip amazing!',
                'created_at' => now()->setDate(2025, 1, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 3,
                'car_id' => 2,
                'rating' => 4,
                'comment' => 'Great car, but the fuel efficiency could be better.',
                'created_at' => now()->setDate(2025, 1, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 3,
                'car_id' => 3,
                'rating' => 5,
                'comment' => 'Absolutely loved the car! Perfect for long drives.',
                'created_at' => now()->setDate(2025, 2, rand(1, 28))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 4,
                'car_id' => 1,
                'rating' => 4,
                'comment' => 'Smooth ride, but the air conditioning was a bit weak.',
                'created_at' => now()->setDate(2025, 2, rand(1, 28))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 4,
                'car_id' => 2,
                'rating' => 5,
                'comment' => 'The car was spotless and very comfortable!',
                'created_at' => now()->setDate(2025, 3, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 4,
                'car_id' => 3,
                'rating' => 3,
                'comment' => 'The car was decent, but the brakes felt a bit worn out.',
                'created_at' => now()->setDate(2025, 3, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 5,
                'car_id' => 1,
                'rating' => 3,
                'comment' => 'The car was okay, but the pickup process took too long.',
                'created_at' => now()->setDate(2025, 1, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 5,
                'car_id' => 2,
                'rating' => 4,
                'comment' => 'Good car, but the GPS system was outdated.',
                'created_at' => now()->setDate(2025, 2, rand(1, 28))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
            [
                'user_id' => 5,
                'car_id' => 3,
                'rating' => 5,
                'comment' => 'Fantastic experience! The car exceeded my expectations.',
                'created_at' => now()->setDate(2025, 3, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
                'created_at' => now()->setDate(2025, 3, rand(1, 31))->setTime(rand(0, 23), rand(0, 59), rand(0, 59)),
            ],
        ];

        foreach ($reviews as $review) {
            DB::table('reviews')->insert([
                'user_id' => $review['user_id'],
                'car_id' => $review['car_id'],
                'rating' => $review['rating'],
                'comment' => $review['comment'],
                'created_at' => $review['created_at'],
                'updated_at' => now(),
            ]);
        }
    }
}
