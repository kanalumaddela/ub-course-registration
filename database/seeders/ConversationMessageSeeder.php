<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ConversationMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return;

        $faker = Factory::create();

        if (env('APP_SEED')) {
            $users = User::inRandomOrder()->limit(30)->get();

            foreach ($users as $i => $user) {
                $author_id = $i % 2 ? 1 : $user->id;
                $recipient_id = $i % 2 ? $user->id : 1;

                $conversation = Conversation::create([
                    'author_id' => $author_id,
                    'recipient_id' => $recipient_id,
                ]);

                $ids = [$author_id, $recipient_id];
                $messages = [];

                foreach (array_fill(0, rand(3, 12), '') as $y) {
                    $messages[] = new Message([
                        'user_id' => $ids[array_rand($ids)],
                        'content' => rand(0, 1) ? $faker->sentence(10) : $faker->sentences(3, true),
                    ]);
                }

                $conversation->messages()->saveMany($messages);
            }
        }
    }
}
