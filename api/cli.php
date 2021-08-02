<?php
require 'vendor/autoload.php';
use splitbrain\phpcli\CLI as SCLI;
use splitbrain\phpcli\Options;
use \Modules\Notification\Model\Notification;
use \Modules\User\Model\User;
use \Core\Util;
use \Modules\Api\Boot\LoadConfig;

class CLI extends SCLI
{
    protected function setup(Options $options)
    {
        $options->registerCommand('test:sms', 'Send SMS to register config sms');
        $options->registerArgument('phone', 'Numero al que será enviado el mensaje', true, 'test:sms');

        $options->registerCommand('setup', 'Setup configuration');
        $options->registerCommand('migrate', 'Run database sql');
        $options->registerOption('debug', 'Aplicación en debug (false)', null, 'debug','setup');
        $options->registerOption('database_host', 'Host de la base de datos (localhost)', null, 'database_host','setup');
        $options->registerOption('database_name', 'Nombre de la base de datos (zinobe)', null, 'database_name','setup');
        $options->registerOption('database_user', 'Usuario de la base de datos (root)', null, 'database_user','setup');
        $options->registerOption('database_password', 'Contraseña de la base de datos ([vacio])', null, 'database_password','setup');
        $options->registerOption('admin_email', 'Email del administrador (jfdeviar@gmail.com)', null, 'admin_email','setup');
        $options->registerOption('admin_phone', 'Teléfono del administrador (3185241383)', null, 'admin_phone','setup');
    }

    protected function main(Options $options)
    {
        $loadConfig = new LoadConfig();
        $loadConfig->fire();
        switch ($options->getCmd()) {
            case 'test:sms':
                $user = new User([
                    'phone' => $options->getArgs()[0]
                ]);
                Notification::sendNotification($user, 'sms de prueba código 123');
                $this->success('SMS Sent, check SMS logs');
                break;
            case 'setup':
                $content = file_get_contents('generator/config.base');
                $content = str_replace('IS_DEBUG',$options->getOpt('debug',"false"),$content);
                $content = str_replace('DATABASE_HOST',$options->getOpt('database_host','localhost'),$content);
                $content = str_replace('DATABASE_NAME',$options->getOpt('database_name','zinobe'),$content);
                $content = str_replace('DATABASE_USERNAME',$options->getOpt('database_user','root'),$content);
                $content = str_replace('DATABASE_PASSWORD',$options->getOpt('database_password',''),$content);
                $content = str_replace('ADMIN_EMAIL',$options->getOpt('admin_email','jfdeviar@gmail.com'),$content);
                $content = str_replace('ADMIN_PHONE',$options->getOpt('admin_phone','3185241383'),$content);

                $config_file = fopen("config.php", "w");
                fwrite($config_file, $content);
                fclose($config_file);

                $this->success('Setup command done');
                break;
            case 'migrate':
                $content = file_get_contents('generator/database.base');
                Util::$database->query($content);
                $this->success('Migration runned');
                break;
            default:
                echo $options->help();
                exit;
        }
    }
}
// execute it
$cli = new CLI();
$cli->run();
