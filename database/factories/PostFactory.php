<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $file = $this->faker->image();
        $fileName = basename($file);

        Storage::putFileAs('images/posts', $file, $fileName);
        File::delete($file);

        return [
            'title' => $this->faker->realText(15, 5),
            'user_id' => Arr::random(Arr::pluck(User::all(), 'id')),
            'category_id' => Arr::random(Arr::pluck(Category::all(), 'id')),
            'body' => $this->faker->realText(200, 5),
            'image' => $fileName
        ];
    }
}
