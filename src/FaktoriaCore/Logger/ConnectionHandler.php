<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class ConnectionHandler extends Base
{
    protected $loggerType = Logger::ERROR;
    protected $fileName = '/var/log/faktoria/connection.log';
}
