# Error handling

## Learning
In this curriculum you will learn about error handling.  
What is error handling?  
It is up to you to decide what to do when an error occurs.  
An error is what the user is supposed to do (here) and what the user does differently.  
For example, trying to display a page that doesn't exist.  

This time
1. When a non-existent page is accessed
2. A page that exists but is not accessible to the user is accessed
I will explain about the two.  

## When a non-existent page is accessed
If you visit a page that does n’t exist,  
To let users know they've visited a non-existent page,  
Displays the 404 page.  

With Laravel, you can implement this functionality with only minor changes to the route definition.  

1. In the route definition of web.php, change the part that is `{id}` to `{post}`.  
2. Change the formal parameter of the controller method corresponding to the route changed in 1 from `int $ id` to` Post $post`.  
3. Remove `$post = Post :: find ($ id)` from the method whose parameter was changed in step 2.  

First, I will explain above 1 and 2.  
As a function of Laravel,  
In the definition of the route of web.php, the part of `{post}` is the same as the formal argument name of the corresponding Controller method `($post)`,
If the argument is type specification `Post`, ** automatically creates an instance of the corresponding model. **
If an instance cannot be created (if there is no corresponding id), 404 will be returned.  

Also, since the model instance is created by the processing of 1 and 2,  
`$post = Post :: find ($ id)`, which is written in each method, is no longer necessary.


For example, in the case of the Edit method, it changes as follows. 
If you set an id that does not exist in the URL, a 404 error will be displayed.   

```php
// routes // web.php

Route :: get ('post / {post} / edit', 'PostController @ edit')-> name ('post.edit')

```

```php
// app / Http / Controllers / PostController

public function edit(Post $post)
{
    return view('post.edit', [
        'post' => $post
    ]);
}
```

## When a page that exists but is not authorized to be viewed by the user is accessed

### Hide edit and delete buttons

On the post list page,
You can edit and delete other people's posts, not just your own.  
Improve this.  

First, change so that the edit and delete buttons are not displayed except for your own post.  

```php
// resources / views / post / index.blade.php

@if (Auth :: check () && Auth :: user ()-> id === $post-> user_id) // add
    <a class="btn btn-success" href="{{ route('post.edit', ['id' => $post-> id])}} "> Edit </a>
    <form action = "{{route ('post.destroy', ['id' => $post-> id])}}" method = "post" class = "d-inline">
        @csrf
        @method ('delete')
        <button class = "btn btn-danger"> Delete </ button>
    </ form>
@endif // add

```
`Auth :: check ()` checks if you are logged in. Returns `true` if you are logged in.  
`Auth :: user ()-> id` returns the id of the logged in user.  

The condition of the if statement is that only if you are logged-in and the ID of the logged-in user is the same as the post ID.  

You may think that the condition (2) alone is not enough,  
Not only `Auth :: user ()-> id === $post-> user_id`,  
`Auth :: check () && Auth :: user ()-> id === $post-> user_id`  
If you are not logged in, this condition is because you cannot use `Auth :: user ()-> id`.  

Now the edit and delete buttons are no longer displayed, but they will be displayed if you enter the URL directly.  
Let's check. The next will improve this.  

## Do not display even if you enter the URL directly

Add the following to the top of the `edit`,` update`, and `destroy` methods.  

```php
// app / Http / Controllers / PostController

if (Auth :: user ()-> id! == $post-> user_id) {
    abort (403);
} 
```

## Reference link
[Error handling] (https://readouble.com/laravel/5.7/ja/errors.html)

## bonus

### Creating an error screen
1. Create `resources / views / errors /` directory
Create status code .blade.php in the directory created in 2.1 (Example: 404.blade.php)
By creating as above, if an error occurs, the corresponding status code screen will be displayed.  