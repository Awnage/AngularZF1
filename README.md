# AngularZF1

by [Gregory Wilson](http://drakos7.net) and [Rosina Bignall](http://rosinabignall.com)

AngularZF1 is a [Zend Framework version 1]() resource plugin to enable [AngularJs]() in your views.
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

Current plugins include angular-resource and angular-ui. You can include
additional Angular plugins by modifying your config.ini to include

    ; Angular resource
    resources.Angular.plugin.resource.enable = false
    ; Angular UI
    resources.Angular.plugin.ui.enable = false
    resources.Angular.plugin.ui.base = '/js'

Each plugin has a .enable.  If true, the scripts will be added to the head
section when you use $this->angular().   If .enable is false, you can include
the scripts by adding the following lines to your view script.

    <?php $this->angularResource(); ?>
    <?php $this->angularUi(); ?>