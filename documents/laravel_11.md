# Create a like feature

## Learning
Here, through the creation of the like function,  
1. How to use Ajax with Laravel
2. Many-to-many relationships
Learn the two.  

## Procedure
Work is performed roughly according to the following flow.  
1. Create a table
2. Relation
3. Route setting
4. Create Like Button
5. Like function
6. Like release function
7. Button display switching when the list screen is displayed


## Creating a table
First, create a table to store the liked data.  

Execute the following command.  
`php artisan make: migration create_likes_table --create = likes`

This table keeps track of ** who ** and ** which posts ** you like.   
Therefore, modify the created file as follows.  

```php
// database / migirations / yyyy_mm_dd_xxxxxx_create_likes_table

Schema :: create ('likes', function (Blueprint $ table) {
    $ table-> increments ('id');
    $ table-> integer ('post_id');
    $ table-> integer ('user_id');
    $ table-> timestamps ();
});

php artisan migrate

```
## relation
Since the table has been added, the model represents the relationship including the added table.  

The existing tables now
-A `users` table representing the users
-`Posts` table for post
-`Likes` table for likes
is.  

It is easy to understand if you think about SNS such as Instagram and Twitter,  
Users can ** like ** many posts (posts).  

Also, your post may be ** liked ** by multiple users.  

Such a relationship is called many-to-many.  
And the intervening table, such as the `likes` table for ** likes **, is called the ** intermediate table **.  

It is as follows in the code.  
This is because the `posts` table and the` users` table use the `likes` table as an intermediate table.  
Indicates a many-to-many relationship.  

```php
// app / Post.php
class Post extends Model
{
    public function likes ()
    {
        return $ this-> belongsToMany ('App \ User', 'likes')-> withTimestamps ();
    }
}
```

### Reference link
[Many-many | relations] (https://laravel.com/docs/6.x/eloquent-relationships#many-to-many)

## Route setting
Now that we have finished writing the code around the DB that records the likes, we will define the route.  
Prepare another route for the like function and the like release.  
Like function is only for logged-in user,  
Write it in `Route :: group (['middleware' => 'auth']`.  

```php
// routes / web.php
Route :: group (['middleware' => 'auth'], function () {
    
    // omitted
    Route :: post ('post/{id}/like', 'PostController@like');
    Route :: post ('post/{id}/dislike', 'PostController@dislike');
});
```
## Create Like Button
Next, the like button is displayed on the screen.  
There is no problem with the word "like",  
Here, icons are displayed like Twiiter or Facebook.

### Font Awesome
Font Awesome is a tool that can easily use various icons such as Facebook and Twitter.  
Icons can be treated as text, so you can change the size and color to suit your website.  

By reading as follows, the icon will be displayed if you attach the specified class.  
```php
// resources / views / layouts / app.blade.php

<link rel = "stylesheet" href = "https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity = "sha384-oS3vJWv + 0UjzBfQzYUhtDYW + Pj2yciDJxpsK1OYPAYjqT085Qq / 1cq5FLXAZQ7Ay" crossorigin >

```

#### Reference link
[Font Awesome] (https://fontawesome.com/)

### Adding a button
Add a like button below the edit and delete buttons.  

```php
// resources / views / post / index.blade.php

@if (Auth :: check () && Auth :: user ()-> id === $post-> user_id)
   // omitted
@endif
<div class = "mt-3 ml-3">
    <i class = "far fa-heart fa-lg text-danger js-like"> </ i>
    <input class = "post-id" type = "hidden" value = "{{$post-> id}}">
    <span class = "js-like-num"> 200 </ span>
</ div>

```

`<i class =" far fa-heart fa-lg text-danger js-like "> </ i>` uses Font Awesome to display the heart icon.  
* `Text-danger js-like` is a class unrelated to Font Awesome.

## Like function
Next, describe the process when the like button is pressed.  
When the like button is pressed, I do not want to change the screen, so I use Ajax.  

### Loading JavaScript / jQuery
Load jQuery and JS files to use Ajax.  

First, create a file named `post.js` in` public / js`.

Since there is a place where JS is loaded in `resources / views / layouts / app.blade.php`,  
Write the following there.  

```php
// resources / views / layouts / app.blade.php

// load jQuery
<script src = "https://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js" defer> </ script> // add
<script src = "{{asset ('js / app.js')}}" defer> </ script>
 // Load JS
<script src = "{{asset ('js / post.js')}}" defer> </ script> // Add
```

### Check the operation of the like button
Now that the JS file is ready, check the button operation.  

After adding the code below, click the Like button.  
The `button was clicked on the console. If it is displayed, it is working properly.  

```JavaScript
// public / js / post.js

$ (document) .on ('click', '.js-like', function () {
    console.log ('The button was clicked.');
});

```

### Send data to server
I was able to confirm that the JavaScript code works correctly when the button is pressed,  
Write the actual likes code.  

```JavaScript

$(document).on('click', '.js-like', function() {
    let postId = $(this).siblings('.post-id').val();
    like(postId, $(this));
});

function like(postId, clickedBtn) {

    $.ajax({
        url: 'post/' + postId + '/like',
        type: 'POST',
        dataType: 'json',
       
        headers: {
            'X-CSRF-TOKEN': 
            $('meta[name="csrf-token"]').attr('content')
        }
    }).done((data) => {
        console.log(data);
       
        let num = clickedBtn.siblings('.js-like-num').text();

        num = Number(num);

        clickedBtn.siblings('.js-like-num').text(num + 1);

        changeLikeBtn(clickedBtn);

    }).fail((error) => {
        console.log(error);
    });
}

// like, change button color with like dismiss,
// js-like, js-dislike is good, class change to switch like release
function changeLikeBtn(btn) {
    btn.toggleClass('fa-thumbs-o-up').toggleClass('fa-thumbs-up');
    btn.toggleClass('js-like').toggleClass('js-dislike');
}

```

### Processing on server
Since the route has been set in advance, write the processing in the controller.  

```php
// app / Http / Controllers / PostController

public function like (int $ id)
{
    $post = Post :: where ('id', $ id)-> with ('likes')-> first ();

    $post-> likes ()-> attach (Auth :: user ()-> id);
}

```

This confirms the following:
1. Data is created in the likes table.  
2. Like heart symbol is colored
3. The number of likes increases.

### Correct the number of likes displayed
Modify the number of likes on the screen to show the correct number of likes.  

Since `App \ Post` is named` likes () `and uses a method to represent relations,  
You can get related table records like `Post :: with ('likes')`.  

```php
// app / Http / Controllers / PostController

public function index ()
{
  $post = Post :: with ('likes')-> orderBy ('id', 'desc')-> get (); // fix

  return view ('post.index', [
      'post' => $post,
  ]);
}
```

```php

// resources / views / post / index.blade.php

<input class = "post-id" type = "hidden" value = "{{$ post-> id}}">
<span class = "js-like-num"> {{$ post-> likes-> count ()}} </ span> // Fix

```

By using count (), in this case,  
The number of records linked is displayed and the number of likes is displayed.  

#### Reference link
[count () | collection] (https://readouble.com/laravel/5.7/ja/collections.html#method-count)

## Like release function

### Send data to server

```JavaScript
// public / js / post.js

// when the like release button is pressed
$(document).on('click', '.js-dislike', function() {
    console.log('dislike');
    let postId = $(this).siblings('.post-id').val();

    dislike(postId, $(this));

});

// Like release processing
function dislike(postId, clickedBtn) {

    $.ajax({
        url: 'post/' + postId + '/dislike',
        type: 'POST',
        dataType: 'json',
        
        headers: {
            'X-CSRF-TOKEN': 
            $('meta[name="csrf-token"]').attr('content')
        }
    }).done((data) => {
        console.log(data);
        let num = clickedBtn.siblings('.js-like-num').text();
        num = Number(num);
        clickedBtn.siblings('.js-like-num').text(num - 1);
        changeLikeBtn(clickedBtn);
    }).fail((error) => {
        console.log(error);
    });
}
```

### Processing on server
Since the route has been set in advance, write the processing in the controller.

```php

// app / Http / Controllers / PostController

public function dislike (int $ id)
{
    $post = Post :: where ('id', $ id)-> with ('likes')-> first ();

    $post-> likes ()-> detach (Auth :: user ()-> id);
}

```

This confirms the following:
1. Data is deleted from the likes table.  
2. The color disappears from the good heart symbol.
3. The number of likes decreases.

## Button display switching when the list screen is displayed
If you liked it the last time you viewed the page,  
If you don't like it,  
Switch the buttons so that  

```php
// resources / views / post / index.blade.html

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
```

When you can write it, display the page, and the post where the like button is pressed,  
Let's confirm that the display of the like button is different in the post that is not pressed.  

Check the official documentation for the `contains` method used in the if statement.  
The meaning of the if statement here is that you are logged in and  
If the id of the logged-in user is included in the id of the user who likes the post.  
It means.

### Reference link
[contains | collections] (https://readouble.com/laravel/5.7/ja/collections.html#method-contains)


## Summary
This completes the good feature.  
This time it was a nice feature,
Not only the bookmark function, but also comments on posts,  
Tag-like features are also the ** many-to-many ** relationships we learned this time,  
You can create it in a similar way.  