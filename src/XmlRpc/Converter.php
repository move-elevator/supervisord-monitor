<?php

namespace MoveElevator\Supervisord\Monitor\XmlRpc;

use MoveElevator\Supervisord\Monitor\Models\Process;
use MoveElevator\Supervisord\Monitor\Models\Server;

/**
 * Class Converter
 */
class Converter
{
    /**
     * @param array $serverData
     *
     * @return Server
     */
    public function getServer(array $serverData)
    {
        $server = new Server();
        $server->setName($serverData['name']);
        $server->setUrl($serverData['url']);

        return $server;
    }

    /**
     * @param array $serversData
     *
     * @return array
     */
    public function getServers(array $serversData)
    {
        $servers = [];

        foreach ($serversData as $id => $serverData) {
            $servers[$id] = $this->getServer($serverData);
        }

        return $servers;
    }

    /**
     * @param array $processesData
     *
     * @return array
     */
    public function getProcesses(array $processesData)
    {
        $processes = [];

        foreach ($processesData as $processData) {
            $process = new Process();
            $process->setIsRunning($this->isRunning($processData['state']));
            $process->setName($processData['name']);
            $process->setGroup($processData['group']);
            $process->setUptime($this->getProcessUptime($processData['start'], $processData['now']));
            $process->setPid($processData['pid']);

            $processes[] = $process;
        }

        return $processes;
    }

    /**
     * @param int $state
     *
     * @return bool
     */
    private function isRunning($state)
    {
        if (20 === intval($state)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $start
     * @param int $now
     *
     * @return string
     */
    private function getProcessUptime($start, $now)
    {
        $start = \DateTime::createFromFormat('U', $start);
        $now = \DateTime::createFromFormat('U', $now);

        return $start->diff($now)->format('%R%a days');
    }
}
