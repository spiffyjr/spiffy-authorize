<?php

namespace SpiffyAuthorize\Guard;

interface ControllerGuardInterface
{
    /**
     * Returns an array of resources allowed to access the controller.
     *
     * @return array
     */
    public function getResources();
}