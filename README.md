# Soneto
*Version 0.1*

![alt tag](logo.png?raw=true)

##### Author notes
Please, have in mind that this project started as a personal study.
The original idea was an attempt to mimic the most important features of a web-framework into one cohesive, modular and minimalistic platform.

### Next goals
The features that must be contained in the first release are:
- Basic routing system
- Extensible *Controller* class
- The basics of what will become the independent modules

## Getting Started
Download and unzip or clone Soneto project from Github to your host directory. You can rename Soneto folder and then open it with your favourite IDE.

### Setup and configuration
JSON files are used to keep the configuration options.

Basically everything can be stored in a JSON file in the root folder called *application.json*. You can also create any sort of environment types. An additional environment JSON file, *development.json*, comes with Soneto to encourage the use of configuration files. All configuration written in the active environment file will replace the fallback values in *application.json*.

The most important options are:
- **environment**: the current environment
- **databases**: a list of database connection data. Each must contain `id` (an arbitrary string), `host`, `name`, `port`, `username`, `password`
- **database_id**: the default database connection
- **modules**: a list of installed and active modules
- **installation_path**: the path the Soneto application in the current host

### Directory structure
Initially Soneto comes with a very simple directory structure:
- **/config** where most of your configuration preferences will be set
- **/controllers** you will create your custom controllers here
- **/core** don't touch any file here, you know why
- **/models** model files go in this folder
- **/modules** all installed modules must reside in this folder
- **/views** the default folder for views

### Lifecycle
The common lifecyle of a Soneto instance is very much like the following steps:
- Actual birth of **Soneto** instance and initial **setups**
- Loading of external **modules**
- Creation of **HTTP** object that contains the client request
- Tries to find a **route** that matches the request
- Resolution of **middleware** stack *pre-action*
- Calling **action** in **controller**
- Rendering a view or sending a response back to client

### Routes
You define routes so that your application can be accessed by the external world. We'll be slowly implementing the recommendations for a RESTful service to work properly.

Soneto routing system is tremendously basic (so far):
- Pick a **method**/**verb**. Your options are *GET*, *POST*, *PUT*, *PATCH* and *DELETE*.
- Define a URL pattern. You can use regular expressions; the wildcards *(:any)* or *(:number)* that matches any string or integers respectively; and setting parameters that will be extracted from the URL as *:param*. Parameters may also be optional, just add a question mark (**?**) after it
- Choose an **action** or pass a **closure**. Actions are methods in the controllers that respond to endpoints. They must receive an `$http` object.

Routes are defined in */config/routes.php* in the `$routes` array. Take this example:

```php
$routes = [
  [
    'method' => 'get',
    'path' => '/user/:name/:lastname?',
    'action' => 'User#getName'
  ]
];
```

In this example we wrote one route, with the verb **GET**, it expects to match this `/user/:name/:lastname?` URL pattern. If it succeeds the action `getName` of the `User` controller will be invoked.

If no matches are found Soneto will respond the default *404.php* view with a 404 HTTP status code.

### Middlewares stack
Another amazing Soneto functionality is the ability for setting up middlewares that can intercept and modify, as well as reject, client requests. Middlewares will run sequentially until the stack is empty and only then the **action** is called.

To start creating middlewares, just open */config/middlewares* and pass a callback function that receives both an `$http` object and the `$next` function to `Middleware::use` method. `$next` is a reference for the next middleware. If you don't call it passing the `$http`, the next middleware will not get called and the route **action** will never be reached. Sometimes this is the expected behavior.

```php
use \Core\Middleware as Middleware;
$middleware = Middleware::getInstance();

$middleware->use(function($http,$next){
    if($http->method !== 'get') $http->render('errors/default',['message'=>'This operation is not valid']);
    else $next($http);
});
```

### Modules
Soneto was designed to be simple. Most features are provided by modules. There are official and third-party modules.

##### Creating a module
To create a module, place everything in a folder whose name is the name of the module. The only required file is the *index.php*. The *index.php* must be namespaced as `Module` and make a call to the `Soneto::installModule($name,$callback)`.

```php
namespace Module;
use \Core\Soneto as Soneto;
Soneto::installModule('router',function($soneto){
  require(dirname(__FILE__).'/Router.php');

  return Router::getInstance();
});
```

The `installModule` method receives both the name of the module as the first parameter and a callback function used to install the module. The Soneto instance `$soneto` will be sent when the callback function is invoked. Anything that is returned by the callback function will be stored in the Soneto instance and can be retrieved with `Soneto::module($name)`.

##### Installing a module
To install a module, just place the module folder in */modules* and add the name of the module to the `modules` key in the */application.json*.

```php
$modules = ['router'];
```

Remember to be very careful when installing third-party modules since we are never sure what it could be doing on background.

### Controllers and views
Controllers are the layer in a MVC framework responsible for **handling requests**, applying some **logic** and giving a **response** to the client. If this response contains a human-friendly **output**, such as a webpage, table, lists or any information representation, it may be achieved by rendering a **view**.

In terms of Soneto framework, a Controller is a class that must extend the core class `Controller` and also be namespaced as `Controller`. Actions are public methods in a controller that receives the HTTP request (as an object in the parameter list). All controllers' files go in the */controllers* folder and must be named the same as the classes.

```php
// /controllers/User.php
namespace Controller;

class User extends \Core\Controller{
    public function myAction($http){
        $http->render('hello',['title'=>'User page', 'message'=>'Hello World!']);
    }
}
```

Note the action invoking the `HTTP::render` method. This is how views are sent to the client. The `hello` view is in the `/views` folder and the .php extension is intentionally omitted. Optionally an associative array can be passed as the second parameter and the keys will turn into variables in the view context.

```php
<!-- /views/hello.php -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title ?></title>
    </head>
    <body>
        <?php echo $message ?>
    </body>
</html>
```

### Introduction to models
Models represent the data and business layer. Everything that is related to persisting, querying and evaluating the traffic of data in the application should be abstracted in a Model. All entities that your project contains or interact with can be translated into models.

In Soneto, a Model is a class that must extend the core class `Model` and also be namespaced as `Model`.

```php
// /models/User.php
namespace Model;

class User extends \Core\Model{

  public $schema = [
    'name'=>['type'=>'string'],
    'lastname'=>['type'=>'string'],
    'birthday'=>['type'=>'date']
  ];

  public function __construct(){

  }
}
```

##### The connection
No connection to a DBMS is provided by default. The developer should implement it by himself/herself or use a module.

The core Model class contains a static `$connection` attribute that is inherited by all app's models. It is used to store an interface to a connection with the database. The `Model::$connection` attribute must be an instance of a class that contains at least the `query`, `affected` and `toArray` methods, that will respectively, query the database with the provided *SQL* string, returns the number of affected rows by the previous operation and returns the result set in the array format. This implementation is optional, but for calling the native `Model::insert`, `Model::update`, `Model::delete` and `Model::select` methods, it is necessary.

Modules should provide this interface by default, including the **official *MySQL Connection***.

The `Model::setConnection` method must be used to attach the connection to the Model.

##### The driver
In Soneto, a driver is an artifact that will help handling the database. No driver is provided by default. The developer should implement it by himself/herself or use a module.

The core Model class contains a static `Model::$driver` attribute that is inherited by all app's models. No implementation is directly recommended, but usually developers would want to store in the `Model::$driver` an instance of a class that has generic methods such as `find`, `findOne`, `all`, `createOrEdit`.

The **official module *Active Record*** contains a basic driver like the one described above.

The `Model::setDriver` method must be used to attach the driver to Model.

### Official modules
Soneto was made to be simple and to scale with the project complexity. For very small projects, a clean installation is enough. For more robust ones, external modules and heavy programming may be required.

The official modules in this section are created by the Soneto team.

- Active Record
- MySQL Connection
- Router
