<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class ConnectionHandler extends Base
{
    protected $loggerType = Logger::NOTICE;
    protected $fileName = '/var/log/spingo/connection.log';
}
