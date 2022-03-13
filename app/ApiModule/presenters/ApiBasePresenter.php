<?php

namespace App\ApiModule;

use App\Model\Orm\Orm;
use App\Services\AppParameters;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Tracy\Debugger;

abstract class ApiBasePresenter extends Presenter
{
//    #[Inject] public Orm $orm;
    #[Inject] public AppParameters $parameters;

    public function startup()
    {
        if(str_contains($this->getHttpRequest()->getRawBody(), 'IntrospectionQuery ')) {
            Debugger::$productionMode = true;
        }

        parent::startup();
        $responseHeaders = [
            'SecondAttempt',
            'show-debug-bar',
            'x-tracy-ajax',
            'X-Tracy-Ajax',
            'Authorization',
            'Origin',
            'Sec-Fetch-Site',
            'Sec-Fetch-Mode',
            'Cookie',
            'Sec-Fetch-Dest',
            'Accept',
            'Overwrite',
            'Destination',
            'Content-Type',
            'Content-Length',
            'Connection',
            'X-Real-Ip',
            'Host',
            'Accept-Language',
            'Accept-Encoding',
            'Referer',
            'Depth',
            'User-Agent',
            'Translate',
            'Range',
            'Content-Range',
            'Timeout',
            'X-Requested-With',
            'If-Modified-Since',
            'Cache-Control',
            'Location',
            'Device-Type'
        ];
//        foreach (getallheaders() as $header => $value) {
//            if (!in_array($header, $responseHeaders)) {
//                Debugger::log('necekany header: ' . $header, 'NECEKANY-HEADER');
//            }
//        }
        $this->getHttpResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->getHttpResponse()->setHeader('Access-Control-Allow-Credentials', 'true');
        $this->getHttpResponse()->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS, GET');
        $this->getHttpResponse()->setHeader('Access-Control-Allow-Headers', implode(',', $responseHeaders));
        $this->getHttpResponse()->setHeader('Access-Control-Max-Age', 1728000);

        if ($this->getHttpRequest()->getMethod() == 'OPTIONS') {
            $this->terminate();
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();
    }

    public function addDebugBar($res)
    {
        if( $this->parameters->getParameter('debugMode') &&
            $this->getHttpRequest()->getHeader('show-debug-bar') === 'true') {
            ob_start();
            Debugger::getBar()->render();
            $s = ob_get_clean();
            $res['debugBar'] = $s;
            if($this->getHttpRequest()->getHeader('x-tracy-ajax')) {
                if (isset($_SESSION['_tracy']['bar'][$this->getHttpRequest()->getHeader('x-tracy-ajax')])) {
                    $res['debugBar'] = $_SESSION['_tracy']['bar'][$this->getHttpRequest()->getHeader('x-tracy-ajax')];
                }
            }
        }
        return $res;
    }
}
