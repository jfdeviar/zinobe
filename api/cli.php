<?php
require 'vendor/autoload.php';
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use \Modules\Notification\Model\Notification;
use \Modules\User\Model\User;

class Minimal extends CLI
{
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->registerOption('test:send-sms', 'Send SMS to register config sms');
    }

    // implement your code
    protected function main(Options $options)
    {
        $loadConfig = new \Modules\Api\Boot\LoadConfig();
        $loadConfig->fire();
        if ($options->getOpt('test:send-sms')) {
            $user = new User([
                'identification' => 1110534050,
                'phone' => 3185241383
            ]);
            Notification::sendNotification($user,'sms de prueba código 123');
        } else {
            echo $options->help();
        }
    }
}
// execute it
$cli = new Minimal();
$cli->run();
