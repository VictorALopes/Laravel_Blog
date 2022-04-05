<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
    public $title;
    public $excerpt;
    public $date;
    public $body;
    public $slug;

    public function __construct(string $title,string $excerpt, string $date, string $body, string $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }
    public static function All()
    {
        return cache()->rememberForever('posts.all', function(){
            return collect(File::files(resource_path("posts")))->map(function($file){
                $document = YamlFrontMatter::parseFile($file);
                return new Post(
                    $document->title,
                    $document->excerpt,
                    $document->date,
                    $document->body(),
                    $document->slug
                );
            })->sortByDesc('date');
        });
    }

    public static function find($slug)
	{
        return static::All()->firstWhere('slug',$slug);
	}

    public static function findOrFail($slug)
	{
        $post = static::find($slug);
        if (!$post)
        {
            throw new ModelNotFoundException();
        }

        return $post;
	}
    //find que eu fiz sozinho e deu certo de primeira
    //08/09/2021 
	// public static function find($slug)
	// {
    //     //of all blog posts, find the one with a slug that matches the one that was requested

    //     $files = File::files(resource_path("posts"));
    //     $posts = collect($files)->map(function($file){
    //         $document = YamlFrontMatter::parseFile($file);
    //         return new Post(
    //             $document->title,
    //             $document->excerpt,
    //             $document->date,
    //             $document->body(),
    //             $document->slug
    //         );
    //     });
    //     $posts = $posts->where("slug","=",$slug);
    //     ddd($posts);
    //     return $posts;
	// }

    //primeiro find que foi feito
    // public static function find($slug)
	// {
    //     if (!file_exists($path = resource_path("posts/{$slug}.html"))) {
    //         throw new ModelNotFoundException();
    //         //return redirect('/');
    //         // abort('404');
    //     }

    //     return cache()->remember("posts.{slug}", 5, function () use ($path){
    //         //var_dump("awoba"); //dá pra ver quando entrou no cache ou não
    //         return file_get_contents($path);
    //     });

    //     // only php 7.4 and higher. Dá pra deixar numa linha só com "fn() ..."
    //     // $post = cache()->remember("posts.{slug}", 5, fn() => file_get_contents($path));
	// }
}