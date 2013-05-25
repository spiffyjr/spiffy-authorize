<?php

namespace SpiffyAuthorize;

use SpiffyAuthorize\Service\AuthorizeServiceAwareTrait;
use Zend\EventManager\Event;

class AuthorizeEvent extends Event
{
    use AuthorizeServiceAwareTrait;

    const EVENT_INIT = 'init';
}