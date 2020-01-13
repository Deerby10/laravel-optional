# Create  Add comment function
In this curriculum it is just like adding a new post.
1. 
2. Table relations (review)

###  Migration file creation
First, create new migration file for the comments table
Execute the following command.
```php
// Create migration file for comments table
php artisan make:migration create_comments_table --table = comments
```
###  Edit migration file
Edit the `up` method as follows.  

```php
// yyyy_mm_dd_hhiisscreate_comments_table
 public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comment');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade'); // setting up FK refer to posts table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // setting up FK refer to users table 
            $table->timestamps();
        });
    }
```
###  Execution of migration
```php
php artisan migrate: fresh
```

##  Defining the relationship of Comment, User and Post
Since a User can have multiple posts and comment, the relationship between the tables is ** one to many ** .  
Laravel makes it easy to represent table relationships.

```php
// app / User
public function comment(){
        return $this->hasMany('App\Comment');
    }
```
set it also in the Post model because can have a multiple comments.
```php
// app / Post
public function comments(){
        return $this->hasMany('App\Comment');
    }
```
###  Reference link
[ One-to-many relationship ] (https://laravel.com/docs/6.x/eloquent-relationships)

##  Route setting
```php
// routes / web.php
Route::post('/comment/store', 'CommentController@store')->name('comment.store');// Save process
```

##  Create controller
Next, create a controller.  

`php artisan make: controller CommentController`

Once the file is created,  
Let's add an index method to `CommentController` . 

```php
// app / Http / Controllers / CommentController
class CommentController extends Controller
{
    // add to
   public function store(Request $request)
    {
        $this->validate($request,[ 'comment' => 'required' ],
        [
            'comment.required' => 'Comment cannot be empty',
        ]);

        $comment = new Comment();
        
        $comment->comment = $request->comment; // the comment value
        $comment->post_id = $request->post_id; // get the post_id on where the user puts the comment
        $comment->user_id = Auth::user()->id; // get the current user_id who log in
        $comment->save(); // save to DB

        return redirect()->route('post.index');
    }
}
```
## Comment form
```php
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
```

## display the comments to view
display the comments next to like counts.
```php
<input type="hidden" class="post-id" value="{{ $post->id }}">
    <span class="js-like-num">{{ $post->likes->count() }}</span>
</div> //omitted

<p style="font-weight: bold;"> COMMENTS: </p> //display the comments
    @foreach ($post->comments as $comment)
    
    <div class="m-1 border border-primary">
        <p style="font-weight: bold;">{{$comment->user->name}}:</p>
        {{$comment->comment}}
    </div>
@endforeach
```