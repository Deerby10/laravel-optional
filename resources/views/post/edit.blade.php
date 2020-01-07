@extends('layouts.app')

@section('title', 'edit screen')

@section('content')

<section class="container m-5">
  <div class="row justify-content-center">
      <div class="col-8">

        @if($errors->any())

          <ul>
            @foreach($errors->all() as $message)

              <li class="alert alert-danger">{{$message}}</li>

            @endforeach
          </ul>

        @endif

        <form action="{{ route('post.update', ['id' => $post->id ]) }}" method="POST">
          @csrf
          @method('put')

          <div class="form-group">
            <label for="body">Edit post</label>
            <textarea id="body" class="form-control" name="body">{{ old('body', $post->body) }}</textarea>
          </div>

          <div class="text-right">
            <button type="submit" class="btn btn-primary">EDIT</button>
          </div>

        </form>

      </div>
  </div>
</section>

@endsection