# Soneto
*Version 0.1*

![alt tag](https://github.com/victorsferreira/soneto/master/logo.png)

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

### Middlewares stack

### Modules
Soneto was designed to be simple. Most features are provided by modules. There are official and third-party modules.

#### Creating a module
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

#### Installing a module
To install a module, just place the module folder in */modules* and add the name of the module to the `$modules` array in */config/modules*.

```php
$modules = ['router'];
```

Remember to be very careful when installing third-party modules since we are never sure what it could be doing on background.
