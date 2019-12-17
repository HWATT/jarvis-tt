<?php

namespace App\EventSubscriber;

use App\Entity\Users;
use App\Controller\MakeLog;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostSubscriber implements EventSubscriberInterface
{
    private $logResult;
    private $request;
    public function __construct()
    {
        $this->logResult = [];
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request'   => 'saveRequest',
            'kernel.terminate' => 'onKernelTerminateEventAction',
            'kernel.exception' => 'onKernelExceptionEventAction',
        ];
    }

    public function saveRequest(RequestEvent $event)
    {
        $this->request = $event->getRequest();
    }
    

    public function onKernelTerminateEventAction(TerminateEvent $event)
    {
        if( in_array( $this->request->getMethod(), ['PUT', 'POST', 'DELETE'])){        
            $makeLog = new MakeLog();
            $content = [ "type" => $this->request->getMethod(), "context" =>  "[".$this->request->getMethod()."] : Opération effectuée ..."];
            $makeLog->createLog($content);
        }
    }

    public function onKernelExceptionEventAction(ExceptionEvent $event)
    {
        $makeLog = new MakeLog();
        $content = [ "type" => "Exception", "context" =>  "Une exception a été levée !!!"];
        $makeLog->createLog($content);
    }

}