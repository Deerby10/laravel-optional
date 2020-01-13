#  Template of view

##  Template inheritance
In the curriculum up to now,  
List, post and edit pages have been created.  
In these pages, the same is stated in the header.  
Not only in this web application, but in many web applications,  
There are many common parts such as headers and footers.  
This time, we will share them.  

As a rough flow,
1. Create a file (template) that describes the common part
2. Create different parts (sections) for each page
is.

##  Creating a file (template) describing the common part
Create a file called `layout.blade.php` in` resources / views / ` .
```php
// resources / views / layout.blade.php
<! DOCTYPE html>
< html  lang = " en " >
< head >
    < meta  charset = " UTF-8 " >
    < meta  name = " viewport "  content = " width = device-width, initial-scale = 1.0 " >
    < meta  http-equiv = " X-UA-Compatible "  content = " ie = edge " >
    < link  rel = " stylesheet "  href = " /css/app.css " >
    < title > @yield ('title') </ title >
</ head >
< body >
    @yield ('content')
</ body >
</ html >
```
### @ yield 
`@ yield` has newly appeared.  
Put `@ yield` where you want to put different content for each page .  

The inside of `()` after `@ yield` will be described later.  

##  Creating different parts (sections) for each page
Since we were able to create a common template throughout,  
Then create different parts of each page.  
Here we will use the list page.  
Change the contents of the list page to the following contents and display the page.  
It is OK if the display on the screen has not changed.  
```php
// resources / views / post / index.blade.php
@extends ('layout')
@section ('title')
List
@endsection
@section ('content')
< A  href = " {{route ( 'post.Create')}} "  class = " btn btn-primary btn-block " >
    New post
</ a >
@foreach ($ posts as $ post)
    < div  class = " m-4 p-4 border border-primary " >
        < p > {{$ post-> title}} </ p >
        < p > {{$ post-> body}} </ p >
        < p > {{$ post-> created_at}} </ p >
        < A  class = " btn btn-success "  href = " {{route ( 'post.Edit', [ 'id' => $ post-> id])}} " > Edit </ a >
        < form  action = " {{route ('post.destroy', ['id' => $ post-> id])}} "  method = " post "  class = " d-inline " >
            @csrf
            @method ('delete')
            < button  class = " btn btn-danger " > Delete </ button >
        </ form >
    </ div >
@endforeach
@endsection
```
New `@ extends` and ` @ section` so came out, describes them.  
### @ extends 
Decide which template to use.  
Write `@extends (template name)` .  
The template name is the part of the file name before `.blade.php` .
In the present case, `Attoextends ( 'Layout')` a, `Layout.Blade.Php` uses.  

### @ section 
Decide which section to display in the template.  
Write `@section (section name)` .
Write `@ endsection` at the end of the section .  
The section name is displayed in the `@yield (section name)` part of the template .  

In this case,  
`@section ('title')` appears in the `@yield ('title')` section,    
`@section ('content')` is displayed in the `@yield ('content')` section.  

Let's execute create.blade.php and edit.blade.php in the same way.

##  Reference link
[ Blade template ] (https://laravel.com/docs/5.8/blade)