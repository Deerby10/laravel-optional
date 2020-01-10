# Create delete function
## Learning
In this curriculum, you will learn the following through creating a delete function:  
1. Flow until the screen is displayed after inputting the URL from the browser (review)
2. How to delete data

## Route setting
Make the same route settings as before.  

```php
// routes / web.php

Route :: delete ('post / {id} ', 'PostController @ destroy')-> name ('post.destroy'); // Delete processing
```

There is one different part.  
`{id}` of `post / {id} ` can be any value.  
This time, we assume the id of the posts table.  
When deleting, you need to identify the record to delete.  
Include the id of each record in the URL. 

## Add delete button to list screen
The method of creating a delete button is almost the same as that of a post button.

There are two differences.  
1. How to specify the form method
2. route has second argument

### How to specify a form method
Because methods other than GET and POST cannot be used due to HTML specifications,  
The method of the form is POST, and the method you actually want to use is  
Write it in the form like `@method ('delete')`.

```php
<form action="{{ route('post.destroy', ['id' => $post->id ]) }}" method="POST" class="dropdown-item">
    @csrf
    @method('delete')
    <button class="text-button">delete</button>
</form>
```

### route has second argument
In the form of {$ id}  
When receiving an arbitrary value, when creating a URL on the screen,  
`{{route ('post.destroy', ['id' => $post-> id])}}`  
Write the second argument as an associative array. 

## Edit controller (deletion process)
This will be the process if you will press the delete button.

```php
 public function destroy(int $id)
    {
        $post = Post::find($id); // Use the Post model to get data from the posts table with an id that matches $id

        $post->delete(); // Delete the acquired data
 
        return redirect()->route('post.index');
    }
```

`$ id` of` destroy (int $ id) `  
Enter the id specified in the URL of the screen, that is, the id of the selected data.  
Get data from DB based on that id and delete it. 

## Summary
This completes the creation of the delete function.  

In this curriculum, you learned:  
1. Flow until the screen is displayed after inputting the URL from the browser (review)
2. How to delete data