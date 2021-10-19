<?php

namespace Database\Factories;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $width = 500;
        $height = random_int(250, 600);

        $file = $this->faker->image(null, $width, $height);
        $path = Storage::putFile('articles', $file);
        File::delete($file);

        return [
            'article_id' => \App\Models\Article::Factory()->create(),
            'org_name' => basename($file),
            'name' => basename($path),
        ];
    }
}
