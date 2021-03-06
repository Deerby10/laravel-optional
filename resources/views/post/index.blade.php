@extends('layouts.app')

@section('title', 'Post')

@section('content')
    
        <div class="row justify-content-center">
            <div class="col-8">

              @if($errors->any())

                <ul>
                  @foreach($errors->all() as $message)

                    <li class="alert alert-danger">{{$message}}</li>

                  @endforeach
                </ul>

              @endif

              <form action="{{ route('post.store') }}" method="POST">
                @csrf

                <div class="form-group">
                  <label for="body">Create Post</label>
                  <textarea id="body" class="form-control m-3 p-2 post-text" name="body"></textarea>
                </div>

                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Add post</button>
                </div>

              </form>

            </div>
        </div>

        @foreach ($post as $post)
        <div class="m-4 p-4 border border-primary">
          <div class="name">
            <h1>{{$post->user->name}}</h1>
            {{-- Auth::check() ： true, false --}}
              @if (Auth::check() && $post->user_id == Auth::user()->id)
            <button type="button" class="btn dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h fa-lg"></i>  
            </button>
            
            <div class="dropdown-menu">
              <a href="{{ route('post.edit', ['post' => $post->id]) }}" class="dropdown-item">edit</a>
              <form action="{{ route('post.destroy', ['id' => $post->id ]) }}" method="POST" class="dropdown-item">
                  @csrf
                  @method('delete')
                  <button class="text-button">delete</button>
                </form>
            </div>
            @endif
          </div>
            
            <p>{{$post->created_at}}</p>
            <p>{{$post->post}}</p>
            
            <div class="mt-3 ml-3">
              @if (Auth::check() && $post->likes->contains(function ($user) {
                return $user->id === Auth::user()->id;
              }))
              
              <i class="fa fa-thumbs-o-up fa-lg js-dislike"></i>
              @else
      
              <i class="fa fa-thumbs-up fa-lg js-like"></i>
              @endif

              <input type="hidden" class="post-id" value="{{ $post->id }}">
              <span class="js-like-num">{{ $post->likes->count() }}</span>
            </div>
            
            <p style="font-weight: bold;"> COMMENTS: </p>
            @foreach ($post->comments as $comment)
            
            <div class="m-1 border border-primary">
              <p style="font-weight: bold;">{{$comment->user->name}}:</p>
                {{$comment->comment}}
            </div>
            @endforeach
            
            <div class="comment-section">
              

              <form action="{{ route('comment.store') }}" method="POST">
                @csrf
                  <div class="comment">
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <textarea id="body" class="form-control p-2" name="comment"></textarea>
                    <div class="button-holder">
                      <button type="submit" class="btn btn-secondary btn-sm button">comment</button>
                    </div>
                  </div>
              </form>
            </div>
        </div>


        

        @endforeach
@endsection