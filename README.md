# AngularZF1

by [Gregory Wilson](http://drakos7.net)

AngularZF1 is a [Zend Framework version 1]() resource plugin to enable [AngularJs]() in your views.
It is a fork from the ZendX_JQuery component. In contrast to ZendX_JQuery, this plugin automatically
switches between non and SSL depending on your server configuration.


## Usage

Modify your application config ini to include

    autoloadernamespaces[] = "AngularZF1_"
    pluginPaths.AngularZF1_Application_Resource = "AngularZF1/Application/Resource"
    resources.Angular.enable = true
    resources.Angular.render_mode = 255
    resources.Angular.version = "1.0.2"

Add the following line to the head section of your layout.phtml

    <?php echo $this->angular() . "\n"; ?>

