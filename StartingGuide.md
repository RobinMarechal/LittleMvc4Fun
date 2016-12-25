
# Starting Guide
First of all, you need to specify some informations about your database in the `../config/database.php` file.

## Views
Views are stored in the `../views/` folder.

To render a view, you can use the `view('path.to.view')` function. 

Example: `view('news.list')` to load the view `list.php` inside of the folder `../views/news/`.

You can also use slashes instead of points in the path. 

DO NOT add `.php` to your view path, it will be added automatically.

## Templating
The framework does not provide any template engine. But you can use the file `../views/template.php` as a generic layout, inside of which your view will be included. 

## Routes
All your routes are stored inside of `../App/Http/routes.php`.

Syntax : `Route::[http_method]([url_path], [controller]@[method], (optional)['middleware' => [middleware's alias]);`.

The http methods supported are GET, POST, PUT, PATH and DELETE.

Middlewares are stored inside of `../App/Http/Middlewares/` folder and aliases are specified inside of `../App/Http/middlewares.php` (See below)

It's possible to send a function (closure) instead of a method in a controller to your route. eg:

`Route::get('home', function() { // do something });`

At the moment, the framework does not provide any parameter handling. To do this, you need to use URL parameters (after a `?` in the URL: `http://website.ext/path?param1=value1&param2=value2...`), which will be stocked into an instance of `\Lib\Request` accessible almost everywhere (inside of the controllers, for example, you can use the `$request` class attribute).

## Middlewares
Middlewares are stored at `../App/Http/Middlewares/`.

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
All your controllers are stored at `../App/Http/Controllers/`.

Your controllers need to extend the `\Lib\Controller` class.

You can access to a `$request` class attribute that can be really useful.

## Models
You models are stored at `../App/` folder.

The framework does not provide (maybe soon, maybe not...) any ORM. You are free to build your models as you want.

## Authentification
The authentified user is stored inside of the instance of `\Lib\Request` which is accessible almost everywhere (`$request`).

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

they are defined inside of `../lib/helpers.php`.

### dd()
This function takes an optional parameter.
- If it's an array or an objet: it displays the array/object using `print_r()`.
- Else: it displays the var using `var_dump()`.
Then, it stops the program.

### view()
Parameters:
- The view path (without the `.php`) from `view/` folder.
- (optional) An array containing the parameters to send to the view.

### redirect()
Parameter:
- The url you want to redirect the user to

### e404()
Throws a 404 error and displays the view `www/errors/404.php`.
