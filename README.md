# supervisord-monitor
Web-based dashboard for supervisord written in PHP with Silex.

![Screenshot] (https://raw.githubusercontent.com/move-elevator/supervisord-monitor/master/supervisord-monitor.png)

## Installation

Clone supervisord-monitor via ```git```:

```
git clone https://github.com/move-elevator/supervisord-monitor.git
```

Copy the ```app/config.yaml.dist``` file to ```app/config.yaml``` and add your servers:

```
servers:
  id:
    name: localhost
    url: http://localhost:9001/
    xmlRpcUrl: http://localhost:9001/RPC2
    username: user
    password: 123456
```

