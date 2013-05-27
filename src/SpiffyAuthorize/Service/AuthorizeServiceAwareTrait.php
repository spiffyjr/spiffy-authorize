<?php

namespace SpiffyAuthorize\Service;

trait AuthorizeServiceAwareTrait
{
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