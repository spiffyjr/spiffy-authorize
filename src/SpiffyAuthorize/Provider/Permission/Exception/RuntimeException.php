<?php

namespace SpiffyAuthorize\Provider\Permission\Exception;

use SpiffyAuthorize\Exception;

class RuntimeException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
}
