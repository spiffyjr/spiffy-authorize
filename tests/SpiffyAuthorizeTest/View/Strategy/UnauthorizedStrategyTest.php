<?php

namespace SpiffyAuthorizeTest\View\Strategy;

use SpiffyAuthorize\Exception\UnauthorizedException;
use SpiffyAuthorize\Guard\RouteGuard;
use SpiffyAuthorize\View\UnauthorizedStrategy;
use Zend\Console\Response as ConsoleResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

class UnauthorizedStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidResponseReturns()
    {
        $event = new MvcEvent();
        $event->setResponse(new ConsoleResponse());

        $strategy = new UnauthorizedStrategy();
        $strategy->onDispatchError($event);

        $this->assertEquals('', $event->getError());
        $this->assertEquals(null, $event->getResult());
    }

    public function testExceptionErrorReturns()
    {
        $event = new MvcEvent();
        $event->setError('foo-bar');

        $strategy = new UnauthorizedStrategy();
        $strategy->onDispatchError($event);

        $this->assertEquals('foo-bar', $event->getError());
    }

    public function testUnauthorizedRouteSetsParam()
    {
        $event = new MvcEvent();
        $event->setError(RouteGuard::ERROR_UNAUTHORIZED_ROUTE);
        $event->setParam('route', 'foo');

        $strategy = new UnauthorizedStrategy();
        $strategy->onDispatchError($event);

        $this->assertEquals('foo', $event->getResult()->route);
    }

    public function testApplicationExceptionSetsErrorAndMessage()
    {
        $event = new MvcEvent();
        $event->setError(Application::ERROR_EXCEPTION);
        $event->setParam('exception', new UnauthorizedException('foo'));

        $strategy = new UnauthorizedStrategy();
        $strategy->onDispatchError($event);

        $this->assertEquals(UnauthorizedStrategy::ERROR_UNAUTHORIZED, $event->getResult()->error);
        $this->assertEquals('foo', $event->getResult()->message);
    }

    public function testTemplateIsSetInViewModel()
    {
        $event = new MvcEvent();
        $event->setError(Application::ERROR_EXCEPTION);
        $event->setParam('exception', new UnauthorizedException('foo'));

        $strategy = new UnauthorizedStrategy();
        $strategy->onDispatchError($event);

        $this->assertEquals($strategy->getTemplate(), $event->getResult()->getTemplate());
    }
}