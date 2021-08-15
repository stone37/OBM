<?php

namespace App\Event;

use App\Entity\Alert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class AlertEvent extends Event
{
    public const CREATE = 'app.alert.create';
    public const DELETE = 'app.alert.delete';

    /**
     * @var Alert
     */
    protected $alert;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    public function  __construct(Alert $alert, Request $request = null)
    {
        $this->request = $request;
        $this->alert = $alert;
    }

    /**
     * @return Alert
     */
    public function getAlert(): Alert
    {
        return $this->alert;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}

