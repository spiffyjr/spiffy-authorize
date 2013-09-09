<?php

namespace SpiffyAuthorize\Service;

interface AuthorizeServiceAwareInterface
{
    /**
     * @param AuthorizeServiceInterface $authorizeService
     * @return $this
     */
    public function setAuthorizeService(AuthorizeServiceInterface $authorizeService);

    /**
     * @return AuthorizeServiceInterface
     */
    public function getAuthorizeService();
}