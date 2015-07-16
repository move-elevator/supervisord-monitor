<?php

namespace MoveElevator\Supervisord\Monitor\XmlRpc;

/**
 * Class Client
 */
class Client
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getProcesses($id)
    {
        return $this->getResponse($this->parser->getServer($id));
    }

    /**
     * @param array $server
     *
     * @return array
     */
    private function getResponse(array $server)
    {
        $file = file_get_contents(
            $server['xmlRpcUrl'],
            false,
            $this->getContext($server['username'], $server['password'])
        );

        return xmlrpc_decode($file);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return resource
     */
    private function getContext($username, $password)
    {
        return stream_context_create(['http' => [
            'method' => 'POST',
            'header' => $this->getHeader($username, $password),
            'content' => $this->getRequest()
        ]]);
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return array
     */
    private function getHeader($username, $password)
    {
        return [
            'Content-Type: text/xml',
            'Authorization: Basic ' . base64_encode(sprintf('%s:%s', $username, $password))
        ];
    }

    /**
     * @return string
     */
    private function getRequest()
    {
        return xmlrpc_encode_request("supervisor.getAllProcessInfo", []);
    }
}
