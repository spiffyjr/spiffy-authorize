<?php

namespace SpiffyAuthorize;

use SpiffyAuthorize\Service\AuthorizeServiceInterface;
use Zend\EventManager\Event;

class AuthorizeEvent extends Event
{
    const EVENT_INIT = 'init';

    /**
     * @var AuthorizeServiceInterface
     */
    protected $authorizeService;

    /**
     * @param AuthorizeServiceInterface $authorizeService
     * @return mixed
     */
    public function setAuthorizeService(AuthorizeServiceInterface $authorizeService)
    {
        $this->authorizeService = $authorizeService;
        return $this;
    }

    /**
     * @return \SpiffyAuthorize\Service\AuthorizeServiceInterface
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }
}