<?php

namespace SpiffyAuthorize\Service;

trait AuthorizeServiceAwareTrait
{
    /**
     * @var AuthorizeInterface
     */
    protected $authorizeService;

    /**
     * @param AuthorizeInterface $authorizeService
     * @return mixed
     */
    public function setAuthorizeService(AuthorizeInterface $authorizeService)
    {
        $this->authorizeService = $authorizeService;
        return $this;
    }

    /**
     * @return \SpiffyAuthorize\Service\AuthorizeInterface
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }
}