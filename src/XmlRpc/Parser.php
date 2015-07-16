<?php

namespace MoveElevator\Supervisord\Monitor\XmlRpc;

use MoveElevator\Supervisord\Monitor\Exceptions\ServerNotFoundException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Parser
 */
class Parser
{
    /**
     * @var array
     */
    private $servers;

    /**
     * @param string $config
     */
    public function __construct($config)
    {
        $servers = Yaml::parse(file_get_contents($config));
        $this->servers = $servers['servers'];
    }

    /**
     * @param $id
     *
     * @return array
     * @throws ServerNotFoundException
     */
    public function getServer($id)
    {
        if (!isset($this->servers[$id])) {
            throw new ServerNotFoundException;
        }

        return $this->servers[$id];
    }

    /**
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }
}
