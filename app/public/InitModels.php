<?php
require('../vendor/autoload.php');

use App\Core\Model\AbstractModel;
$files = glob('../src/Main/Model/*');

function loopExceute(array $loop): void
{
    foreach ($loop as $file){
        Exceute($file);
    }
}

function Exceute(string $file) : void
{
    if(!is_dir($file)){
        $urls = explode('/',$file);
        $className = $urls[count($urls)-1];
        $ext = explode('.',$className);
        $modelName = basename($file, '.php');
        $nameSpace = str_replace("../src/", "App/", $file);
        $nameSpace = str_replace(".php", "", $nameSpace);
        $nameSpace = str_replace($className, "", $nameSpace);
        $nameSpace = str_replace("/", "\\", $nameSpace);
        $nameSpace = str_replace('/'.$ext[0], "", $nameSpace);
        try {
            $model = new $nameSpace();
            if ($model Instanceof AbstractModel){
                echo "Init Model - $modelName <br>";
                $model->initModel();
            }
        }catch (ArgumentCountError){
            echo '';
        }
    }else{
        $files = glob($file.'/*');
        loopExceute($files);
    }
}

loopExceute($files);
?>