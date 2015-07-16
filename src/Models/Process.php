<?php

namespace MoveElevator\Supervisord\Monitor\Models;

/**
 * Class Process
 */
class Process
{
    /**
     * @var bool
     */
    private $status = false;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var string
     */
    private $uptime;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $group;

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return string
     */
    public function getUptime()
    {
        return $this->uptime;
    }

    /**
     * @param string $uptime
     */
    public function setUptime($uptime)
    {
        $this->uptime = $uptime;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
}
