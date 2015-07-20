# supervisord-monitor
Web-based dashboard for supervisord written in PHP with Silex.

![Screenshot] (https://raw.githubusercontent.com/move-elevator/supervisord-monitor/master/supervisord-monitor.png)

## Dependencies

Supervisord has a [XmlRpc Api](http://supervisord.org/api.html). First you have to install [XmlRpc for PHP](http://php.net/manual/de/book.xmlrpc.php).

## Installation

Clone supervisord-monitor via ```git```:

```
git clone https://github.com/move-elevator/supervisord-monitor.git
```

Copy the ```app/config.yml.dist``` file to ```app/config.yml``` and add your servers:

```
servers:
  id:
    name: localhost
    url: http://localhost:9001/
    xmlRpcUrl: http://localhost:9001/RPC2
    username: user
    password: 123456
```

