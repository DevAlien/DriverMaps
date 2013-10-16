<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

define('ROOT', __DIR__);
require 'vendor/autoload.php';

use Aliegon\Kernel;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
$run = new Run;
$handler = new PrettyPageHandler;
// Add a custom table to the layout:
$handler->addDataTable('Ice-cream I like', array(
    'Chocolate' => 'yes',
    'Coffee & chocolate' => 'a lot',
    'Strawberry & chocolate' => 'it\'s alright',
    'Vanilla' => 'ew'
));

$run->pushHandler($handler);

// Example: tag all frames inside a function with their function name
$run->pushHandler(function($exception, $inspector, $run) {

    $inspector->getFrames()->map(function($frame) {

        if($function = $frame->getFunction()) {
            $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
        }

        return $frame;
    });

});
$run->register();

$kernel = new Kernel();
$kernel->run();

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
//echo 'Page generated in '.$total_time.' seconds.';