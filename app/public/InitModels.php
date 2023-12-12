<?php

require('../vendor/autoload.php');

use App\Core\Model\AbstractModel;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

$files = glob('../src/Main/Model/*');
$connect = Connect::getInstance();
$engin = $connect->getEngine();

function loopExecute(array $loop,DBInterface $engin): void
{
    foreach ($loop as $file) {
        execute($file,$engin);
    }
}

function execute(string $file,DBInterface $engin): void
{
    if (!is_dir($file)) {
        $urls = explode('/', $file);
        $className = end($urls);
        $modelName = basename($file, '.php');

        $nameSpace = str_replace("../src/", "App/", $file);
        $nameSpace = str_replace(".php", "", $nameSpace);
        $nameSpace = str_replace($className, "", $nameSpace);
        $nameSpace = str_replace("/", "\\", $nameSpace);

        $model = new $nameSpace($engin);
        if ($model instanceof AbstractModel) {
            echo "Init Model - $modelName <br>";
            $model->initModel();
        }

    } else {
        $files = glob("$file/*");
        loopExecute($files);
    }
}

loopExecute($files,$engin);
?>