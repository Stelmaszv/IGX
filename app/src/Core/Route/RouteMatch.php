<?php

namespace App\Core\Route;

class RouteMatch
{
    static function match(string $urlMain){
        $urls = explode('/',$urlMain);
        $serverUrl = explode('/',$_SERVER['REQUEST_URI']);

        foreach ($urls as $url){
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);
            self::validateUrl($matches);
        }
        if(self::isMatched($urls,$serverUrl)){
            var_dump('load');
        }

    }

    static private function isMatched(array $urls,array $serverUrls){
        $urlMatchArray = [];

        if(count($serverUrls) === count($urls)){
            foreach ($serverUrls as $serverKey => $serverUrl) {
                if(!empty($serverUrls[$serverKey]) && $serverUrls[$serverKey] === $urls[$serverKey] && !empty($urls[$serverKey])){
                    $urlMatchArray[] = $serverKey;
                }

                $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
                preg_match($pattern, $urls[$serverKey], $matches);

                if(count($matches)===3) {
                    if ($matches[1] === 'int' && intval($serverUrls[$serverKey]) != 0) {
                        $urlMatchArray[] = 'int';
                    }

                    if ($matches[1] === 'string' && $serverUrls[$serverKey] != 0) {
                        $urlMatchArray[] = 'string';
                    }
                }
            }
        }

        return count($urlMatchArray) === count($serverUrls)-1;

    }

    static private function validateUrl(array $matches){
        if(count($matches)===3){
            if($matches[1] !== 'string' && $matches[1] !== 'int'){
                echo 'error';
            }
        }
    }
}
