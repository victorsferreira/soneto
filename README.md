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

### Directory structure
Initially Soneto comes with a very simple directory structure:
- **/config** where most of your configuration preferences will be set
- **/controllers** you will create your custom controllers here
- **/core** don't touch any file here, you know why
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
To install a module, just place the module folder in */modules* and add the name of the module to the `$modules` array in */config/modules*.

```php
$modules = ['router'];
```

Remember to be very careful when installing third-party modules since we are never sure what it could be doing on background.

### Controllers and views
Controllers are the layer in a MVC framework responsible for **handling requests**, applying some **logic** and giving a **response** to the client. If this response contains a human-friendly **output**, such as a webpage, table, lists or any information representation, it may be achieved by rendering a **view**. 

### Official modules
