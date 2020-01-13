# Create authentication function

## Learning
Learn about creating authentication features.  

Laravel allows you to create authentication functions with a single command.
The authentication function can be created by executing the following command.  
`php artisan make: auth` but this command has been removed in laravel 6.0 release.

The New Way
Authentication support is now added with the help of a package now. you can read in detail about it in here
Command to implement the Auth are as follows.
`composer require laravel/ui`
`php artisan ui vue --auth`

## Reference link
[Authentication] (https://laravel.com/docs/6.x/authentication)

## Contents changed by command execution
### File to be edited

```php
// routes // web.php

// Auth :: routes (); is added.  
Auth :: routes ();

```
Route to member registration screen and login screen
You can check which controller is used in `php artisan route: list`.  

## Modify file
Make some changes and deletes according to the apps created this time.
 
```php

// app / Http / Controllers / Auth / RegisterController

// Change the redirect destination after account registration from / home to /
protected $ redirectTo = '/';

```

```php

// app / Http / Controllers / Auth / LoginController

// Change the redirect destination after login from / home to /
protected $ redirectTo = '/';

```

```php

// app / Http / Middleware / RedirectIfAuthenticated.php

return redirect ('/');

```

Modify `web.php` as follows.  
`Route :: get ('/ home', 'HomeController @ index')-> name ('home');` is unnecessary and removed  

```php

// routes // web.php
Route::get('/', function () {
    return view('auth/login');
});

Route :: get ('/', 'DiaryController @ index')-> name ('diary.index');


// Change the page other than the list so that it cannot be displayed (executed) unless you are logged in
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'PostController@index')->name('post.index');
    Route::post('/post/store', 'PostController@store')->name('post.store');
    Route::get('/post/{post}/edit', 'PostController@edit')->name('post.edit');
    Route::put('/post/{id}/update', 'PostController@update')->name('post.update');
    Route::delete('/post/{id}', 'PostController@destroy')->name('post.destroy');
 
});

Auth :: routes ();

```

The route enclosed by `Route :: group (['middleware' => 'auth'], function () {})`  
If you are not logged in, you will not be able to view.  

#### Reference link
[Route] (https://laravel.com/docs/5.7/routing)


## Delete unnecessary files
Delete the following views because they are not used.  
`resources / views / home.blade.php`  
`resources / views / welcome.blade.php`  


## Show link to authentication screen
The first command executed,  
Since a template with a link to the authentication screen has been created,  
Modify the following files to use it.  

```php
// resources / views / post / index.blade.php
// resources / views / post / edit.blade.php

@extends ('layouts.app') // change
```

## Changed to save the ID of the user who posted when posting
Although it is not directly related to the authentication function,  
Because we've changed the table to save the user who posted,  
We will change the posting process accordingly.  

```php

// app / Http / Controllers / PostController

use Illuminate \ Support \ Facades \ Auth;

public function store(Request $request)
    {
        $post = new Post();// Instantiates the Post model

        $post->post = $request->body; // Substitute the post/content entered on the screen
        $post->user_id = Auth::user()->id;// Add save id of logged in user

        $post->save();// Save to DB

        return redirect()->route('post.index'); // Redirect to list page
    }
```

## Summary
This completes the authentication function.  
Try signing up, logging in, and logging out.  

By using the framework in this way,  
Generic functions used in various applications can be easily created.  
