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
     * Constructor
     *
     * @param AuthorizeServiceInterface $authorizeService
     */
    public function __construct(AuthorizeServiceInterface $authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }

    /**
     * @return AuthorizeServiceInterface
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }
}