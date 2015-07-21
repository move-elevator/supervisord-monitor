# supervisord-monitor
Web-based dashboard for supervisord written in PHP with [Silex](silex.sensiolabs.org){:target="_blank"}.

![Screenshot] (https://raw.githubusercontent.com/move-elevator/supervisord-monitor/master/examples/supervisord-monitor.png)

## Requirements

[supervisor](http://supervisord.org/){:target="_blank"} has a [xmlrpc API](http://supervisord.org/api.html){:target="_blank"}. 
So sure that you have installed [xmlrpc for PHP](http://php.net/manual/de/book.xmlrpc.php){:target="_blank"}.

You also need [composer](https://getcomposer.org/){:target="_blank"}.

## Installation

Clone supervisord-monitor via ```git```:

```
git clone https://github.com/move-elevator/supervisord-monitor.git
```

or download the latest relase here:

```
https://github.com/move-elevator/supervisord-monitor/releases
```

After a ```composer update``` in the project-folder you can copy or move the 
```app/config.yml.dist``` file to ```app/config.yml``` and add your servers:

```
servers:
  id:
    name: localhost
    url: http://localhost:9001/
    xmlRpcUrl: http://localhost:9001/RPC2
    username: user
    password: 123456
```

## ToDo's

* write tests
* connect TravisCI
