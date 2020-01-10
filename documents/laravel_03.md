# Creating post list screen
##  Learn
In this curriculum, you will learn the following through creating a post list screen.  
1. Flow from entering the URL from the browser until the screen is displayed
2. How to create a table in the database
3. How to save test data in the database

Basically, all future curriculums will be created basically as follows.  
1. Route
2. Controller
3. model
4. View

This is the flow from when the user enters the URL to when the screen is displayed.  
Depending on the curriculum, we will create test data in the DB and so on.  
In this curriculum, test data is created after model creation.

##  Route setting
First, set the route.  
In the route, ** ① which URL (including method) ** , ** ② which controller ** , ** ③ which method to use **  

Example:
1. `/` when the URL is GET accessed by the method, (2) and PostController(3) index methods are used.

`/` Is the root directory, in this case `localhost: 8000` .

```php
// routes / web.php
Route :: get ('/', 'PostController @ index')-> name ('post.index'); // Add
//Delete
Route :: get ('/', function () {
    return view ('welcome');
});
```


* The part of `-> name ('post.index')` is used when creating a link on the screen.  
Details will be described later. 

###  Reference link
[ Routing ] (https://laravel.com/docs/5.7/routing)

##  Create controller
Next, create a controller.  
Laravel lets you create controllers with a single command.

`php artisan make: controller PostController`

`app / Http / Controllers` to ` PostController.php` file that is created.

Once the file is created,  
Let's add an index method to `PostController` . 

```php
// app / Http / Controllers / PostController
class PostController extends Controller
{
    // add to
    public function index ()
    {
        return 'Hello World';
    }
}
```

Check the operation from the browser.  
It is OK if Hello World is displayed on the screen.

The flow from entering the URL until the screen is displayed,    
1. Check the controller and method used in web.php
2. Execute the method of the controller specified in 1.
It becomes.

###  Reference link
[ Controller ] (https://laravel.com/docs/6.x/controllers)

##  Create model
Next, create the model.  
Laravel allows you to create models with a single command.

`php artisan make: model Post`

`app` to ` Post.php` file that is created.

In the model, write code to operate the DB. 

###  Reference link
[ Model ] (https://laravel.com/docs/5.8/eloquentl)

##  Migration
Before creating a View,  
Do the following things:      

1. Create table in DB
2. Insert test data into the table created.

Laravel also has features to support these.

Follow the procedure below to create a table in the DB.  
1. Create migration file
2. Edit migration file
3. Execution of migration  

Migration is, in a nutshell,  
Create tables and add columns,  
This function allows you to easily add tables to the DB and make changes.

By using this feature,  
You can create tables without writing your own SQL.  
Also, for example, when a new development member joins,  
By using the migration file, you can easily prepare the same DB environment.

###  Create Migration file
First, create the migration file,  
You can do this with a single command.  

By executing the following command,  
`php artisan make: migration create_posts_table --create = posts` 

A file called `yyyy_mm_dd_hhmmii_create_posts_table.php` is created in the` database` directory . 

This time, because it is a file to create the posts table,  
`create_posts_table` . 

###  Edit migration file
Next, edit the created file.  
In this case of creating a table like this time,  
Enter the name of the column to be created. 

```php
// database / yyyy_mm_dd_hhmmii_create_posts_table.php
public function up ()
{
    Schema :: create ('posts', function (Blueprint $ table) {
        $ table-> increments ('id'); 
        $ table-> string ('title', 30); // add
        $ table-> text ('body'); // add
        $ table-> timestamps ();
    });
}
```

###  Execute
Running the last edited migration is also a command.
`php artisan migrate`

By executing the above command, the contents entered in the file (table creation this time) will be executed.  
Let's check the DB to see if the table has been created.

###  Reference link
[ Migration ] (https://laravel.com/docs/5.8/migrations)

##  Creating test data
Next, create Database test data.  
Some functions cannot be confirmed without data even if only tables are created.  
For example, the function to display the post list needs the post data to be displayed.

The procedure for creating test data is as follows.
1. Create test data creation file
2. Edit the test data creation file
3. Execution of test data creation file

###  Creating a test data creation file
Creating a file is easy.
This can also be done with a single command.

`php artisan make: seeder PostTableSeeder`

When you run the above command, `Database / Seeds` to ` PostTableSeeder` file that is created.

###  Editing the test data creation file

```php
// database / seeds / PostTableSeeder
<? php
use Illuminate \ Database \ Seeder;
use Carbon \ Carbon; // add
use Illuminate \ Support \ Facades \ DB; // add
class PostTableSeeder extends Seeder
{
    / **
     * Run the database seeds.
     *
     * @return void
     * /
    public function run ()
    {
        $ posts = [
            [
                'title' => 'Program in Cebu',
                'body' => 'If you notice, it's almost 2 months',
            ],
            [
                'title' => 'Travel on the weekend',
                'body' => 'Went to Oslob and swim with whale sharks',
            ],
            [
                'title' => 'English class',
                'body' => 'fun',
            ],
        ];
        foreach ($ posts as $ post) {
            DB :: table ('posts')-> insert ([
                'title' => $ post ['title'],
                'body' => $ post ['body'],
                'created_at' => Carbon :: now (),
                'updated_at' => Carbon :: now (),
            ]);
        }
    }
}
```

###  Execution of test data creation file
You can do this with a single command.  
`php artisan db: seed --class = PostTableSeeder`

Let's make sure that the data is stored in the posts table.

###  Reference link
[ Seeding ] (https://laravel.com/docs/5.8/seeding)

##  Add logic to controller
Earlier, I displayed `Hello world` to check the operation of the controller ,  
Change to get the data to be displayed on the list screen.
To add
1.  `use App \ Post;`
2.  `$ post = Post :: all ();`
3.  `Dd ($post);`
There are three places.

###  About use
Although it is `use` used in 1 ,  
This is used when using other classes.

In this case, by using `use App \ Post` ,  
When you write `Post` in` PostController` , you can use the `app / Post` class.  

```php
// app / Http / Controllers / PostController
use App \ Post; // Declaration to use App / Post class
use Illuminate \ Http \ Request;
class PostController extends Controller
{
    public function index ()
    {
        // Get all data of posts table
        // Implement all methods of Post you are using
        // all () is a method to get all table data
        $ posts = Post :: all (); 
        dd ($ posts); // A method combining var_dump () and die (). Check variables + stop processing
    }
```
Up to this point, the flow from inputting the URL to displaying the screen is

1. Check controller and method used in web.php
2. Execute the method of the controller specified in 1
3. Data will be obtained using the all method of the model.

##  Create view
Finally, create the screen.  
Display the data acquired by the model on the screen.  

You must create the view yourself, not the command.  
1.  Create a new folder named `post` in the` resources / views` directory
2.  inside in your `post` folder create `index.blade.php`.

The data is not displayed immediately, but proceed in the following order.
1. Verify that the view displays correctly
2. Confirm that the data acquired by the model is displayed

###  Verify that the view displays correctly

```php
// resources / views / post / index.blade.php
Hello View
```

```php
// app / Http / Controllers / PostController
public function index ()
{
    $ posts = Post :: all ();
    // display view / posts / index.blade.php
    return view ('posts.index');
}
```

`return view ( 'posts.index') ;` you, but,  
`view ()` is a method for opening files in `resources / views /` .  
`posts.index` represents the path of the file.  
In this case, it represents index.blade.php in the posts directory.

After confirming that the screen is displayed,
Displays data obtained from the model in a view.

```php
// app / Http / Controllers / PostController
public function index ()
{
    $ posts = Post :: all ();
    return view ('posts.index', ['posts' => $posts]);
}
```

The argument of the `view ()` method has been increased to two.  
In the second argument, `['posts' => $ posts]` ,  
The variable `$ posts` is passed to the first argument page.  
In the page specified as the first argument, the key name of the associative array of the second argument will be the variable name. 

```php
// view / post / index.blade.php
<! DOCTYPE html>
< html  lang = " en " >
< head >
    < meta  charset = " UTF-8 " >
    < meta  name = " viewport "  content = " width = device-width, initial-scale = 1.0 " >
    < meta  http-equiv = " X-UA-Compatible "  content = " ie = edge " >
    < link  rel = " stylesheet "  href = " /css/app.css " >
    < title > List display screen </ title >
</ head >
< body >
    @foreach ($ posts as $ post)
        < div  class = " m-4 p-4 border border-primary " >
            < p > {{$ post-> body}} </ p >
            < p > {{$ post-> created_at}} </ p >
        </ div >
    @endforeach
</ body >
</ html >
```

###  Reference link
[ Blade template ] (https://laravel.com/docs/5.8/blade)

Up to this point, the flow from inputting the URL to displaying the screen is   
1. Check the controller and method used in web.php
2. Execute the method of the controller specified
3. Retrieve data using the model's all method
4. The data obtained in step 3 will be displayed in the view.

##  Summary
This completes the list screen display process.  

In this curriculum, I learned three things:  
1. Flow from entering the URL from the browser until the screen is displayed
2. How to create a table in the database
3. How to save test data in the database

What is particularly important is the flow until screen 1 is displayed.
We will create several new functions in the future, but
basically this flow will be based on this one.

At first, it may be difficult to understand some parts because it deals with multiple files, but as the
scale increases , some parts may become
easier to manage.

##  Extra

###  Post order
Currently the order is ascending ID (first created first).  
If you modify as follows, it will be in descending order of ID.  

```php
// app / Http / Controllers / PostController
public function index ()
{
    $ post = Post :: orderBy ('id', 'desc')-> get ();
```

Other than `where ()` You can also like to narrow the using conditions.  
The following links have more details.  

####  Reference link
[ Query Builder ] (https://laravel.com/docs/5.8/queries)