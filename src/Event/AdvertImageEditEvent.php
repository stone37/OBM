<?php

namespace App\Event;

use App\Entity\Advert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertImageEditEvent
{
    /**
     * @var Advert
     */
    private $advert;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;

    public function  __construct(Advert $advert, Request $request)
    {
        $this->request = $request;
        $this->advert = $advert;
    }

    /**
     * @return Advert
     */
    public function getAdvert()
    {
        return $this->advert;
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
    public function getRequest()
    {
        return $this->request;
    }
}

