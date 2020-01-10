# Create editing function
##  Learn
In this curriculum, you will learn the following through creating editing features:  
1. How to send data from a form (review)
2. How to update existing data

Performing this function is almost the same as a adding a post.

##  Route setting
The first is the route setting as usual.
This time, create the edit screen and update process.  

```php
Route::get('/post/{post}/edit', 'PostController@edit')->name('post.edit'); //edit screen
Route::put('/post/{id}/update', 'PostController@update')->name('post.update'); //update process
```

##  Add edit button to list screen
Create an edit button so that you can move from the list screen to the edit screen.
Put it above the delete button

```php
// view / post / index.blade.php
<a href="{{ route('post.edit', ['post' => $post->id]) }}" class="dropdown-item">edit</a>
<form action="{{ route('post.destroy', ['id' => $post->id ]) }}" method="POST" class="dropdown-item">
    @csrf
    @method('delete')
    <button class="text-button">delete</button>
</form>
```

##  Edit controller (edit screen)
Create a process when the edit button of the list screen is clicked.  
First, make sure that it works correctly until the controller is called.
```php
// app / Http / Controllers / PostController
public function edit (int $id)
{
    dd ($id);
}
```

Click the edit link on the list screen,  
After confirming that the ID of the clicked data is displayed on the screen,  
Edit as follows.  

```php
// app / Http / Controllers / PostController
public function edit (int $id)
{
     // Use the Post model to get data from the posts table with an id that matches $id
    $ post = Post :: find ($id); 
    return view ('post.edit', [
        'post' => $post,
    ]);
}
```

##  Create view (edit screen)
Next, create a view that will be called from the controller.

```php
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
            <label for="post">Edit post</label>
            <textarea id="post" class="form-control" name="post">{{ old('post', $post->post) }}</textarea>
          </div>

          <div class="text-right">
            <button type="submit" class="btn btn-primary">EDIT</button>
          </div>

        </form>

      </div>
  </div>
</section>

@endsection
```

You have now created an edit screen.  
The function is almost the same as adding new post.  
There are three different points.  
1. How to specify the form method
2. The route has a second argument.
3. Preservation of input field value

### Preserving  input field values
The value is retained,  
On the edit screen, I want to display the content registered in the DB as the default value ** .  
Also, like new registration, I want to keep the input values ​​when you are caught in validation .  

To achieve this, we used the validation of the new posting function,  
Use `{{old}}` .  
Write the contents of ② in the first argument of old and the contents of ① in the second argument of old.

##  Edit controller (update screen)
Finally, describe the processing when the update button on the edit page is pressed.  

```php
public function update(Request $request, Post $post, int $id)
    {
        $post = Post::find($id); // Instantiate Post model    
        
        $post->post = $request->post; // get the new Post value that is entered on the screen

        $post->save(); // Save to DB


        return redirect()->route('post.index');
    }
```

The `updated_at` column set in diaries is updated automatically.  
The content of the process is almost the same as a new post.

The different parts are 
In the case of a new post, while saving data in a new DB,  
For updates, we will use existing data,  
`$ post = Post :: find ( $id);` of as `find` using the method  
Retrieving existing data.

##  Summary
In the curriculum so far, the following has been implemented.  
1. Install Laravel
2. Prepare DB
3. Migration
4. Preparation of test data
5. CRUD processing

The content implemented up to this curriculum will be the foundation,  
In particular, ** Implementation of CRUD function ** is important so let's review it.  
You can easily review by cutting a new branch.  