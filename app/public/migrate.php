<?php
require('../vendor/autoload.php');

use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBException;

$connect = Connect::getInstance();
$engin = $connect->getEngin();

foreach (scandir('./migrate') as $query) {
    $extension = pathinfo($query, PATHINFO_EXTENSION);
    if($extension === 'sql'){
        $file_path = './migrate/'.$query;

        $fileHandle = fopen($file_path, 'r');

        if ($fileHandle) {

            try{
                while (($lineQuery = fgets($fileHandle)) !== false) {
                    $engin->runQuery($lineQuery);
                }
            }catch (DBException $e){

            }

            fclose($fileHandle);
        }

        echo 'Generated migration from file - '.$query;
        echo '<br>';
    }
}