<?php

namespace App\Controller;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\Request;

class MakeLog 
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('jarvis-api');
        $this->logger->pushHandler(new StreamHandler(\dirname(__FILE__).'/../../var/log/jarvis-legal-log.txt', Logger::WARNING));
    }

    public function createLog($content)
    {

        if( in_array( $content['type'], ['PUT', 'POST', 'DELETE'])){   
            $this->logger->info($content['type'],[
                "cause" => $content['context']
            ]);
        } else

        if ($content['type'] == 'Exception') {
            $this->logger->critical($content['type'],[
                "cause" => $content['context']
            ]);
        }
        
    }
}
