# Soneto
*Version 0.1*

![alt tag](https://github.com/victorsferreira/soneto/master/soneto.png)

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

#### Directory structure
Initially Soneto comes with a very simple directory structure:
**/config** where most of your configuration preferences will be set
**/controllers** you will create your custom controllers here
**/core** don't touch any file here, you know why
**/modules** all installed modules must reside in this folder
**/views** the default folder for views

#### Lifecycle
The common lifecyle of a Soneto instance is very much like the following steps:
- Actual birth of **Soneto** instance and initial **setups**
- Loading of external **modules**
- Creation of **HTTP** object that contains the client request
- Tries to find a **route** that matches the request
- Resolution of **middleware** stack *pre-action*
- Calling **action** in **controller**
- Rendering a view or sending a response back to client
