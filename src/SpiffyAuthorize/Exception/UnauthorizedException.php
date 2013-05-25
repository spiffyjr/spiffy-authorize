<?php

namespace SpiffyAuthorize\Exception;

class UnauthorizedException extends \Exception implements ExceptionInterface
{
    public function __construct(\Exception $previous = null)
    {
        parent::__construct('Unauthorized', 404, $previous);
    }
}