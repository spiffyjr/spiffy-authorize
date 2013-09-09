<?php

namespace SpiffyAuthorize\View;

use SpiffyAuthorize\Exception\UnauthorizedException;
use SpiffyAuthorize\Guard\RouteGuard;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ViewModel;

class UnauthorizedStrategy implements ListenerAggregateInterface
{
    const ERROR_UNAUTHORIZED = 'error-unauthorized';

    /**
     * @var string
     */
    protected $template = 'error/403';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @param string $template
     * @return UnauthorizedStrategy
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'));
    }

    /**
     * @param MvcEvent $e
     */
    public function onDispatchError(MvcEvent $e)
    {
        $result   = $e->getResult();
        $response = $e->getResponse();

        if ($result instanceof ResponseInterface || ($response && ! $response instanceof HttpResponse)) {
            return;
        }

        $vars = array(
            'error' => $e->getError()
        );

        switch ($e->getError()) {
            case RouteGuard::ERROR_UNAUTHORIZED_ROUTE:
                $vars['route'] = $e->getParam('route');
                break;
            case Application::ERROR_EXCEPTION:
                $exception = $e->getParam('exception');

                if (!$exception instanceof UnauthorizedException) {
                    return;
                }

                $vars['error']   = self::ERROR_UNAUTHORIZED;
                $vars['message'] = $exception->getMessage();
                break;
            default:
                return;
        }

        $response = $response ? : new HttpResponse();
        $model    = new ViewModel($vars);
        $model->setTemplate($this->getTemplate());

        $response->setStatusCode(403);
        $e->setResponse($response);
        $e->setResult($model);
    }
}