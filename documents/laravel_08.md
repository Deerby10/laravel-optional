#  Authentication function (preparatory)

##  Learn
In this curriculum, you will learn the following while preparing to create an authentication function (sign up, login).  
1. Adding columns to existing tables
2. Table relations
3. Creation of test data

Learn in the following order.  
1. Added user_id column to posts table
2. Definition of Relationship between User and Post
3. Prepare to create test data in users table
4. Prepare to create test data in posts table
5. Create test data

##  Add user_id column to posts table
The procedure for adding a column is basically the same as creating a table.
1. Create migration file
2. Edit migration file
3. Execution of migration

Help users find out who posted the post.

###  Migration file creation
Execute the following command.
```php
// Create migration file to add user_id to posts table
php artisan make: migration add_user_id_to_posts --table = posts
```

A file called `yyyy_mm_dd_hhmmii_add_user_id_to_posts.php` is created in the` database` directory . 

This time, because it is a file to add id to the posts table,  
It is called `add_user_id_to_posts` .

###  Edit migration file
Edit the `up` method as follows.  

```php
// yyyy_mm_dd_hhiiss_add_user_id_to_posts
 public function up ()
 {
     Schema :: table ('posts', function (Blueprint $ table) {
         $ table-> integer ('user_id')-> unsigned ();
         // Set to foreign key
         $ table-> foreign ('user_id')-> references ('id')-> on ('users');
     });
 }
```

###  Execution of migration
```php
php artisan migrate: fresh
```

Explain the reason for `php artisan migrate: fresh` instead of` php artisan migrate` when migrating .    
When adding a new column, the value of the added column will be `NULL` in the data already saved .  
However, since `user_id` contains the ID of the user who posted, do not allow NULL.  
Therefore, if you do `php artisan migrate` normally ,  
It is an error that the `user_id` column does not allow` NULL` .

`php artisan migrate: fresh` deletes all tables and creates a new one.   

##  Defining the relationship between User and Post
Since a User can have multiple posts, the relationship between the tables is ** one to many ** .  
Laravel makes it easy to represent table relationships.

To represent a one-to-many relationship:

```php
// app / User
public function posts ()
{
    return $ this-> hasMany ('App \ Post');
}
```

###  Reference link
[ One-to-many relationship ] (https://laravel.com/docs/6.x/eloquent-relationships)

1. Preparation for creating test data in the users table
##  Creating Seeder to insert data into users table

Use the following command to create a file for creating test data.  
`php artisan make:seeder UsersTableSeeder`

Change the contents of the created file as follows.
```php
// database / seeds / UsersTableSeeder.php
use Illuminate \ Database \ Seeder;
use Carbon \ Carbon;
use Illuminate \ Support \ Facades \ DB;
class UsersTableSeeder extends Seeder
{
    / **
     * Run the database seeds.
     *
     * @return void
     * /
    public function run ()
    {
        DB :: table ('users')-> insert ([
            'name' => 'pikopoko',
            'email' => 'pikopoko@gmail.com',
            'password' => bcrypt ('123456'),
            'created_at' => Carbon :: now (),
            'updated_at' => Carbon :: now (),
        ]);
    }
}
```

##  Preparation for creating test data in post table

Because we added a column to the posts table,  
Change the test data to be input.

```php
// database / seeds / PostTableSeeder.php
public function run ()
{
    $ user = DB :: table ('users')-> first (); // add
    $ post = [
        [
            'body' => 'If you notice, its almost 2 months',
        ],
        [
            'body' => 'Went to Oslob and swim with whale sharks',
        ],
        [
            'body' => 'Programming is life',
        ],
    ];
    foreach ($posts as $ post) {
        DB :: table (posts')-> insert ([
            'body' => $ post ['body'],
            'user_id' => $ user-> id, // add
            'created_at' => Carbon :: now (),
            'updated_at' => Carbon :: now (),
        ]);
    }
}
```

##  Creating test data

In order to simultaneously create the two test data created this time,  
Edit the `run` method of` DatabaseSeeder.php` as follows.  
```php
// database / seeds / DatabaseSeeder.php
public function run ()
{
    $ this-> call (UsersTableSeeder :: class);
    $ this-> call (PostTableSeeder :: class);
}
```

Finally, execute the following command to confirm that data has been created in the DB.  
`php artisan db: seed`