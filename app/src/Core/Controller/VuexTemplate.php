<?php
namespace App\Core\Controller;

use Exception;

class VuexTemplate
{
    private string $code;
    private array $keywords;
    private string $start = '{{';
    private string $end = '}}';

    public function __construct(string $url)
    {

        if (!file_exists($url)) {
            throw new Exception('File "'.$url.'" Template not Exist !!');
        }

        $content = file_get_contents($url);
        $this->code = $this->cleanCode($content);
        $this->keywords = [];
    
    }

    private function cleanCode(string $code) : string
    {
        return preg_replace("/[\r\n\t]/", '', $code);
    }

    private function addKeyword(string $keyword, string $text) : void
    {
        $this->keywords[$keyword] = $text;
    }

    private function loopContent(string $arrayId, array $arrayName) : void
    {
        $count_table = count($arrayName);

        if ($count_table > 0) {
            $start_pos = strpos(strtolower($this->code), '<loop name="'.$arrayId.'">') + strlen('<loop name="'.$arrayId.'">');
            $end_pos = strpos(strtolower($this->code), '</loop name="'.$arrayId.'">');

            $loop_code = substr($this->code, $start_pos, $end_pos - $start_pos);
            $start_tag = substr($this->code, strpos(strtolower($this->code), '<loop name="'.$arrayId.'">'), strlen('<loop name="'.$arrayId.'">'));
            $end_tag = substr($this->code, strpos(strtolower($this->code), '</loop name="'.$arrayId.'">'), strlen('</loop name="'.$arrayId.'">'));

            if ($loop_code != '') {
                $new_code = '';
                foreach ($arrayName as $array_item) {
                    $temp_code = $loop_code;
                    foreach ($array_item as $key => $value) {
                        $temp_code = str_replace($this->start . $key . $this->end, $value, $temp_code);
                    }
                    $new_code .= $temp_code;
                }
                $this->code = str_replace($start_tag . $loop_code . $end_tag, $new_code, $this->code);
            }
        } else {
            $this->code = preg_replace('/<loop name="'.$arrayId.'">(.*)<\/loop name="'.$arrayId.'">/i', '', $this->code);
        }
    }

    private function conditionallyInclude(string $keyword, bool $include) : void
    {
        if ($include == 1) {
            $this->code = str_replace('<if name="'.$keyword.'">','', $this->code);
            $this->code = str_replace('</if name="'.$keyword.'">','', $this->code);
        }
        else {
            $this->code = preg_replace('/<if name="'.$keyword.'">(.*)<\/if name="'.$keyword.'">/i', '', $this->code);
        }
    }

    public function render() : string
    {
        foreach ($this->keywords as $key => $val) {
            $this->code = str_replace($key, $val, $this->code);
        }

        $this->code = preg_replace('/\{{(.*?)}\}|<if name="(.*?)">|<\/if name="(.*?)">|<loop name="(.*?)">|<\/loop name="(.*?)">/i', '', $this->code);

        return $this->code;
    }

    public function setVariables(array $attributes) : void
    {
        foreach ($attributes as $key => $attribute) {
            if (is_string($attribute)) {
                $this->addKeyword($this->start . $key . $this->end, $attribute);
            } elseif (is_array($attribute)) {
                $this->loopContent($key, $attribute);
            } elseif (is_bool($attribute)) {
                $this->conditionallyInclude($key, $attribute);
            }
        }
    }
}