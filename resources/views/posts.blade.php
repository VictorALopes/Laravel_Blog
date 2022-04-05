
@extends('layout')

@section('content')
    <h1>Batata</h1>
    @foreach ($posts as $post)
    <article>
        <h1>
            <a href="/posts/{{$post->slug}}">
            {{$post->title}}
            </a>
        </h1>
            
        <div>
            {{$post->excerpt}}
        </div>
    </article>
    @endforeach
@endsection