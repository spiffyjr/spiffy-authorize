<?php

namespace SpiffyAuthorize\View;

use SpiffyAuthorize\Service\AuthorizeServiceInterface;
use Zend\View\Helper\AbstractHelper;

class IsAuthorizedHelper extends AbstractHelper
{
    /**
     * @var AuthorizeServiceInterface
     */
    protected $authorizeService;

    /**
     * @param AuthorizeServiceInterface $authorizeService
     */
    public function __construct(AuthorizeServiceInterface $authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }

    /**
     * @param string $resource
     * @param null|\Closure|\SpiffyAuthorize\Assertion\AssertionInterface $assertion
     * @return bool
     */
    public function __invoke($resource, $assertion = null)
    {
        return $this->authorizeService->isAuthorized($resource, $assertion);
    }
}
