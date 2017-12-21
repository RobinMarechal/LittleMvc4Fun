
# Starting Guide
First of all, you need to specify some informations about your database in the `/config/database.php` file.

## Views
Views are stored in the `/views/` folder.

To render a view, you can use the `view('path.to.view')` function. 

Example: `view('news.list')` to load the view `list.php` inside of the folder `/views/news/`.

You can also use slashes instead of points in the path. 

DO NOT add `.php` to your view path, it will be added automatically.

## Templating
The framework does not provide any template engine. But you can use the file `/views/template.php` as a generic layout, inside of which your view will be included. 

## Routes
All your routes are stored inside of `/routes/web.php`.

Syntax : 
```
Route::<httpMethod>('<url_path]>', '<controller>@<method>', (optional)<'middleware' => <'middleware alias'>);

Route::<httpMethod>('<url_path>', function(<parameters>){
    //...
});
```

Where `<httpMethod>` is one of `get, put, post, patch, delete`.

You can also put parameters int your url, for example:

```
Route::get('users/{userId}/posts/{postId}', function($userId, $postId){
    // ...
});
```

## Middlewares
Middlewares are stored at `/app/Http/Middlewares/`.

You can apply middleware(s) to your routes, which will be checked just before calling your controller method. If the user does not fullfill the conditions you specified, you can for example return a specific view or redirect the user to another URL.

To create you own middleware, create a new class and override the `handle` function:

```
class TestMiddleware extends \Lib\Middleware{
	
	public function handle()
	{
		// Do something
	}
}
``` 

You can access to a `$request` class attribute which contains some useful information like the currently logged user, the GET params, or post/patch/put data...

## Controllers 
All your controllers are stored at `/app/Http/Controllers/`.

Your controllers need to extend the `\Lib\Controller` class.

You can access to a `$request` class attribute that can be really useful.

## Models
You models are stored at `/app/` folder.

The framework does not provide (maybe soon, maybe not...) any ORM. You are free to build your models as you want.

## Authentication
The authenticated user is stored inside of the instance of `\Lib\Request` which is accessible almost everywhere (`$request`).

You need to add this line just below the namespace definition : `use \Lib\Auth;`.

### Login
`Auth::login($user);`, $user is an instance of `App\User`.

### Logout
`Auth::logout();`

### Get the currently logged user
`Auth::user();`: returns an instance of `App\User`.

### Check if the user is logged
`Auth::check();`. Returns `true` if logged, `false` otherwise.

## Helpers
The framework provides some helper functions you can use without including anything.

they are defined inside of `/lib/helpers.php`.

### dd()
Dump and die.

Variadic function. For each parameter: 
- If it's an array or an object: it displays the array/object using `print_r()`.
- Else: it displays the var using `var_dump()`.
Then, it stops the program.

Return: none.

### view()
Display a view.

Parameters:
- The view path (without the `.php`) from `view/` folder.
- (optional) An array containing the parameters to send to the view.

Return: the created `View` instance.

### redirect()
Redirect to a location.

Parameter:
- The url you want to redirect the user to

Return: none.

### e404()
Throws a 404 error, displays the view `views/errors/404.php` (if exists) and stops the program.

Return: none.

### config()
Get a config array.

Parameter:
- the path of the wanted config data, with `.` as separator. e.g `app` or `app.debug`.

Return `null|array|mixed`.

### firstLetterUpperCase()
Get the equivalent string with an upper case first letter

Parameter:
- The string

Return: `string`

### camelCase()
Get the camel case equivalent of a string

Parameter: 
- The string to compute,
- A boolean indicating if the first letter should be transformed to upper case (default is `true`)

Return: `string`: The camel case equivalent.