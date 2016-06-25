# AngularZF1

by [Rosina Bignall](http://rosinabignall.com) and [Gregory Wilson](http://drakos7.net)

AngularZF1 is a [Zend Framework version 1](http://framework.zend.com/manual/1.12/en/manual.html) resource 
plugin to enable [AngularJs](https://angularjs.org/) and other Javascript libraries in your views.
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

    resources.Js.enable = true
    resources.Js.render_mode = 255 ; default
    resources.Js.minified = false
 
    resources.Js.javascriptfile = "/some/file.js"
    resources.Js.javascriptfiles.0 = "/some/file.js"

Add the following line to the head section of your layout.phtml

    <?php $this->angular(); ?>
    <?php $this->js(); ?>
    
Note: These are not needed if .enable is set to true in your config ini. 
Also <?php $this->js(); ?> is optional if none of the Javascript 
plugins are enabled in the config and there are no javascript files included 
in the resources config.

Like the Zend Url helper for making easy links and getting urls that depend on the
routes and router, use helper angularUrl to create urls from routes which include
angular data-binding.

    <?php echo $this->angularUrl(array('name' => '{{name}}'),'routename'); ?>


## Plugins

Current Angular plugins include 
angular-resource(https://docs.angularjs.org/api/ngResource), 
angular-route(https://docs.angularjs.org/api/ngRoute), 
angular-sanitize(https://docs.angularjs.org/api/ngSanitize), 
angular-ui(http://angular-ui.github.io/), 
angular-ui-router(https://github.com/angular-ui/ui-router), 
angular-uibootstrap(https://angular-ui.github.io/bootstrap/), 
angular-animate(https://docs.angularjs.org/api/ngAnimate), 
angular-aria(https://docs.angularjs.org/api/ngAria), 
angular-messages(https://docs.angularjs.org/api/ngMessages),
angular-material(https://material.angularjs.org/), 
angular-filter(https://github.com/a8m/angular-filter#angular-filter-----),
Restangular (https://github.com/mgonto/restangular) 

Current Javascript plugins include 
and Lodash (https://lodash.com/). 

You can include additional plugins by modifying your config.ini to include

    ; Angular resource
    resources.Angular.plugin.resource.enable = false
    ; Angular route
    resources.Angular.plugin.route.enable = false
    ; Angular sanitize
    resources.Angular.plugin.sanitize.enable = false
    ; Angular UI
    resources.Angular.plugin.ui.enable = false
    resources.Angular.plugin.ui.base = '/js'
    ; Angular UI Router
    resources.Angular.plugin.uirouter.enable = false
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
    ; Angular Restangular
    resources.Angular.plugin.restangular.enable = false
    ; Js lodash
    resources.Js.plugin.lodash.enable = false
    

Each plugin has a .enable.  If true, the scripts will be added to the head
section when you use $this->angular() or $this->js() or automatically if 
the rescources .enable is true.   If .enable is false, you can include
the scripts by adding the following lines to your view script.

    <?php $this->angularResource(); ?>
    <?php $this->angularRoute(); ?>
    <?php $this->angularSanitize(); ?>
    <?php $this->angularUi(); ?>
    <?php $this->angularUirouter(); ?>
    <?php $this->angularUiBootstrap(); ?> 
    <?php $this->angularAnimate(); ?>
    <?php $this->angularAria(); ?>
    <?php $this->angularMessages(); ?>
    <?php $this->angularMaterial(); ?>
    <?php $this->angularFilter(); ?>
    <?php $this->angularRestangular(); ?>
    <?php $this->jsLodash(); ?>
  
The Angular plugin uses the default google apis CDN as shown on the Angular
homepage (https://angularjs.org/) to create the script tags. You can override it by setting 
resources.Angular.base in your application config. 
    
Plugins for standard Angular modules (angular-resource, angular-sanitize, angular-animate,
angular-aria, and angular-messages) use the same base as the Angular 
plugin. The angular-material, angular-filter and angular-ui-router use the default CDNs 
as shown in their documentation. You can override the CDN by setting
the .base in the resource configuration.  
    
The angular-ui and angular-uibootstrap plugins do not have a default base url, so the 
.base for those plugins must be set in the application configuration.

The Angular plugin uses a default version. You can override the default 
version by setting resources.Angular.version in your application config.
The angular-filter and angular-material plugins also use default versions
and can be overridden by setting the .version for the plugin.

Notes:
Lodash isn't an angular module, but it's required by Restangular so
we make it easy by making a plugin for it. The Restangular plugin will
automatically include Lodash in the view.

Aria, Messages and Animate are required by Angular Material, so they are
automatically included in the view when Material is included.