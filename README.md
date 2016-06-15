# AngularZF1

by [Rosina Bignall](http://rosinabignall.com) and [Gregory Wilson](http://drakos7.net)

AngularZF1 is a [Zend Framework version 1](http://framework.zend.com/manual/1.12/en/manual.html) resource plugin to enable [AngularJs](https://angularjs.org/) in your views.
It is a fork from the ZendX_JQuery component. In contrast to ZendX_JQuery, this plugin automatically
switches between non and SSL depending on your server configuration.


## Usage

Modify your application config ini to include

    autoloadernamespaces[] = "AngularZF1_"
    pluginPaths.AngularZF1_Application_Resource = "AngularZF1/Application/Resource"
    resources.Angular.enable = true
    resources.Angular.render_mode = 255
    resources.Angular.version = "1.0.3"
    resources.Angular.minified = false

Add the following line to the head section of your layout.phtml

    <?php echo $this->angular() . "\n"; ?>

Like the Zend Url helper for making easy links and getting urls that depend on the
routes and router, use helper angularUrl to create urls from routes which include
angular data-binding.

    <?php echo $this->angularUrl(array('name' => '{{name}}'),'routename'); ?>


## Plugins

Current plugins include angular-resource(https://docs.angularjs.org/api/ngResource), 
angular-sanitize(https://docs.angularjs.org/api/ngSanitize), 
angular-ui(http://angular-ui.github.io/), 
angular-uibootstrap(https://angular-ui.github.io/bootstrap/), 
angular-animate(https://docs.angularjs.org/api/ngAnimate), 
angular-aria(https://docs.angularjs.org/api/ngAria), 
angular-messages(https://docs.angularjs.org/api/ngMessages),
angular-material(https://material.angularjs.org/), 
and angular-filter(https://github.com/a8m/angular-filter#angular-filter-----). 
You can include additional Angular plugins by modifying your config.ini to include

    ; Angular resource
    resources.Angular.plugin.resource.enable = false
    ; Angular route
    resources.Angular.plugin.route.enable = false
    ; Angular sanitize
    resources.Angular.plugin.sanitize.enable = false
    ; Angular UI
    resources.Angular.plugin.ui.enable = false
    resources.Angular.plugin.ui.base = '/js'
    ; Angular UI Bootstrap
    resources.Angular.plugin.uibootstrap.enable = false
    resources.Angular.plugin.uibootstrap.base = '/js'
    ; Angular animate
    resources.Angular.plugin.animate.enable = false
    ; Angular aria
    resources.Angular.plugin.aria.enable = false
    ; Angular messages
    resources.Angular.plugin.messages.enable = false
    ; Angular material
    resources.Angular.plugin.material.enable = false
    ; Angular filter
    resources.Angular.plugin.filter.enable = false
    

Each plugin has a .enable.  If true, the scripts will be added to the head
section when you use $this->angular().   If .enable is false, you can include
the scripts by adding the following lines to your view script.

    <?php $this->angularResource(); ?>
    <?php $this->angularRoute(); ?>
    <?php $this->angularSanitize(); ?>
    <?php $this->angularUi(); ?>
    <?php $this->angularUiBootstrap(); ?> 
    <?php $this->angularAnimate(); ?>
    <?php $this->angularAria(); ?>
    <?php $this->angularMessages(); ?>
    <?php $this->angularMaterial(); ?>
    <?php $this->angularFilter(); ?>
  
The Angular plugin uses the default google apis CDN as shown on the Angular
homepage (https://angularjs.org/) to create the script tags. You can override it by setting 
resources.Angular.base in your application config. 
    
Plugins for standard Angular modules (angular-resource, angular-sanitize, angular-animate,
angular-aria, and angular-messages) use the same base as the Angular 
plugin. The angular-material and angular-filter use the default CDNs 
as shown in their documentation. You can override the CDN by setting
the .base in the resource configuration.  
    
The angular-ui and angular-uibootstrap plugins do not have a default base url, so the 
.base for those plugins must be set in the application configuration.

The Angular plugin uses a default version. You can override the default 
version by setting resources.Angular.version in your application config.
The angular-filter and angular-material plugins also use default versions
and can be overridden by setting the .version for the plugin.