<?php

require('../vendor/autoload.php');

use App\Core\Model\AbstractModel;

$files = glob('../src/Main/Model/*');

function loopExecute(array $loop): void
{
    foreach ($loop as $file) {
        execute($file);
    }
}

function execute(string $file): void
{
    if (!is_dir($file)) {
        $urls = explode('/', $file);
        $className = end($urls);
        $modelName = basename($file, '.php');

        $nameSpace = str_replace("../src/", "App/", $file);
        $nameSpace = str_replace(".php", "", $nameSpace);
        $nameSpace = str_replace($className, "", $nameSpace);
        $nameSpace = str_replace("/", "\\", $nameSpace);

        $model = new $nameSpace();
        if ($model instanceof AbstractModel) {
            echo "Init Model - $modelName <br>";
            $model->initModel();
        }

    } else {
        $files = glob("$file/*");
        loopExecute($files);
    }
}

loopExecute($files);
?>