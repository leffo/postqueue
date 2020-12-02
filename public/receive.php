<?php

use AYakovlev\core\Receiver\Listen;
use AYakovlev\core\Receiver\Process;
use AYakovlev\core\WorkerReceiver;

require_once('../vendor/autoload.php');


$listen = new Listen(new WorkerReceiver());
$listen->listen();