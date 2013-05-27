<?php

namespace SpiffyAuthorize\Guard;

trait GuardTrait
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param array $rules
     * @return RouteGuard
     */
    public function setRules(array $rules)
    {
        $cleaned = [];

        foreach ($rules as $route => $permissions) {
            if (is_numeric($permissions)) {
                $route       = $permissions;
                $permissions = [];
            }

            if (!is_array($permissions)) {
                $permissions = [ $permissions ];
            }

            $cleaned[$route] = $permissions;
        }
        $this->rules = $cleaned;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}