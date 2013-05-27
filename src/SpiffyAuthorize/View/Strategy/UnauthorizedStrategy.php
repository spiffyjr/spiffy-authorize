<?php

namespace SpiffyAuthorize\View\Strategy;

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
    use ListenerAggregateTrait;

    const ERROR_UNAUTHORIZED = 'error-unauthorized';

    /**
     * @var string
     */
    protected $template = 'error/403';

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
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError']);
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

        $vars = [
            'error' => $e->getError()
        ];

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