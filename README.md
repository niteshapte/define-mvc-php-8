# Define MVC PHP 8 #

Define MVC is a ***Front Controller*** based light weight MVC framework for developing web based applications. It is an open source and will remain always.

### Define MVC PHP 5.3 - 7.2 ### 
First release of Define MVC supports PHP 5.3 to PHP 7.2. You can get it from <a href="https://github.com/niteshapte/define-mvc/">***HERE***</a>. 

## Contribute ##
Please feel free to contribute. But try to keep the implementation simple so that a developer working for first time shouldn't find it difficult to understand and use it.

## Requirement ##
PHP 8.0+

## Virtual Host ##
While developing I created a virtual host for this framework. Request you guys to do the same. Probably it will not work if you try to access it like _htt<area>p://localhost/define-mvc-php-8/**_. 

#### Creating a Virtual Host ####
Add below line in `/etc/apache2/apache2.conf` <br>
`Include vhost.conf`

Create a file `vhost.conf` in `/etc/apache2/` folder and add below lines -
<pre>
NameVirtualHost *:80

&lt;VirtualHost *:80&gt;
    ServerAdmin admin@define.mvc
    ServerName define.mvc
    ServerAlias www.define.mvc
    DocumentRoot /var/www/html/define-mvc-php-8/
    &lt;Directory "/var/www/html/define-mvc-php-8"&gt;
        AllowOverride All
    &lt;/Directory&gt;
    DirectoryIndex bootstrap.php
&lt;/VirtualHost&gt;
</pre>

Add below line in `/etc/hosts` file - <br> 
`127.0.0.1      www<area>.define.mvc`

Run below command on terminal to restart apache - <br>
`service apache2 restart`

---
_Please note above configurations were done in my linux machine. You must do according to your setup._ 

---

## How to use it ##
Define MVC is a front controller based MVC framework just like any other MVC framework. So, all the request are redirected to **bootstrap.php** page.  Please have a look at `.htaccess` file.

### Folder Structure ###
Please don't skip this section. Go through the below folder structure to understand how Define MVC and project / application classes and files will be organized.
```
define-mvc-php-8
|  - README.md
|  - bootstrap.php      // Front controller file. All request are redirected to this file. Redirect configuration is in .htaccess
|  - .htaccess   
└─── application        // project / application files
|   └─── controller     // controller files
|   └─── dto            // DTOs related to project
|   └─── exceptions     // Exceptions related to projects. 
|   └─── i18n           // Internationalization / Localization
|   └─── repository     // DAO classes of project
|   └─── service        // Service classes of project
|   └─── view           // view / html / html + php files
└─── configuration      
|   - application.php   // configuration for project
|   - define.php        // configuration for framework
└─── docs               // contains configuration files for both application and framework 
└─── lib                
|   └─── define             // DEFINE MVC files
|   |   └─── core           // Framework's core classes
|   |   └─── dbdrivers      // Database Driver classes
|   |   └─── exceptions     // Exceptions for framework 
|   |   └─── traits         // Traits for frameork
|   |   └─── utilities      // Utility classes for framework
|   └─── vendors            // vendor libraries
|       └─── phpmailer      
|       └─── json-object-mapper
|       └─── etc.
└─── logs                   // logs generated by logger
└─── scripts (optional)     // Scripts like DB scripts or shell scripts
    └─── db
    └─── shell
```
**All the files related to your project will be inside 'application' folder**. However, you can change the configurations defined in `configuration/define.php`.

### Controller ###
By default, Define MVC follow below URL pattern:

**_http<area>://www<area>.domain.com/controller/action/param1-param2/_**

For example, if the URL is htt<area>p://www<area>.example.com/user/profile/33-90/, or you want to create this url, then craete `UserController` class in `application/controller` with below code.

<pre>
class UserController extends ApplicationController {
	public function profileAction(string $param1, string $param2) {
		// logic goes here
	}
}
</pre>
Check IndexController inside application/controller to get more details. 

### View ###
All the view files will be inside `application/view/` folder.

You can add display value in view by using View object. For example:

<pre>
class UserController extends ApplicationController {
	public function profileAction($param1, $param2) {
		$this->view->addObject("msg", "I am the value to be displayed.");
		$this->view->render('user');
	}
}
</pre>

In `application/view/` folder, create a file named `user.php`, and add the following code:
```xml
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8"/>
<title>Define MVC Index Controller with Index View</title>
<body>
<?php echo $msg ?? "No value set";?>
</body>
</html>
```

### Repository ###
Refer to `application/repository/IndexRepository` class to get an idea how to query and fetch result from a database. Please note DB config should be set in `bootstrap.php` file.

As a good practice, call repository classes from service classes.

### Service 
Refer to `application/service/IndexService` class to get an idea how to call a repository class or do other things. 


### Test ###
After setting up define-mvc-php-8 in your local server, try accessing the following:

http://www.define.mvc

http://www.define.mvc/index/

http://www.define.mvc/index/default/me-you/

http://www.define.mvc/index/test-from-service/

### Configuration

Define MVC is completely configurable.

For example, you want your UserController to be UserXYZ go to `configuration/define.php` and change CONTROLLER_SUFFIX to XYZ. Similarly, you can change other configuration properties.


## TODO ##
Write unit tests




