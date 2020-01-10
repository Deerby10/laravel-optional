# Create  new post function
##  Learn
In this curriculum, you will learn the following through creating a new posting function:  
1. Flow from entering a URL from a browser until the screen is displayed (review)
2. How to submit data from a form
3. How to save data
4. Validation method

##  Route setting
First, set the route as well as the list.  
In the route, ** ① which URL (including method) ** , ** ② which controller ** , ** ③ which method to use **  

Please add post screen and save process route.
```php
// routes / web.php
Route :: get ('/', 'PostController @ index')-> name ('post.index');

Route :: post ('post / create', 'PostController @ store')-> name ('post.store'); // Save process
```

##  Create 'add post' form
Create a `add post` form. In my design I just put it on my `index.blade.php`.

```php
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
```

##  Edit controller (save data)
We will now create a function that will save our data came from the form that we have created.

```php
// app / Http / Controllers / PostController
    public function index ()
    {
        // omitted
    }
     public function store(Request $request)
    {
        $post = new Post(); // Instantiate Post model    

        $post->post = $request->body; // get the Post value that is entered on the screen
        $post->user_id = Auth::user()->id;

        $post->save(); // Save to DB

        return redirect()->route('post.index'); // Redirect to index screen or Post list screen
    }
```

After you press the `Add post` button
###  Regarding form action
First, let's talk about form actions.
It is not possible to enter the URL of the destination page in the form action  

The above is the current route.  
Each has `> name ('xxx.yyy')` ,  
In Laravel, when specifying the transition destination with `<form>` or `<a>` , by using `route ('xxx.yyy')` ,  
The link will be the corresponding URL.

###  CSRF measures
Laravel has security measures in place to prevent common attacks.  
One of them is CSRF measures.  
I will omit the details of CSRF because the explanation will deviate from this one,  
Simply put, vulnerabilities such as unauthorized writing by the user,  
Or an attack that exploits that vulnerability.  

Laravel makes it very easy to prevent that vulnerability,  
Just write `@ csrf` in the form .    
Also, if you do not write `@ csrf` so that there is no omission , an error will be displayed when you submit the form.  

####  Reference link
[ CSRF protection ] (https://laravel.com/docs/5.7/csrf)

###  Show error message in view

Add the following if statement.  
```php
// resources / views / post / create.blade.php
@if ($ errors-> any ())
   < ul >
       @foreach ($ errors-> all () as $ message)
            < li  class = " alert alert-danger " > {{$ message}} </ li >
       @endforeach
   </ ul >
@endif
```

Laravel stores all validation errors in the variable $errors,  
All errors are extracted and displayed by `foreach` from $errors .

##  Summary
This completes the creation of a new post function.
In this curriculum, I learned the following four things.  
1. Flow from entering a URL from a browser until the screen is displayed (review)
2. How to submit data from a form
3. How to save data
4. Validation method

I think there was a lot of new content, but there is no need to memorize it.
** If you only remember that you can do   this roughly **
There is no problem if you check the detailed writing when you actually write.  