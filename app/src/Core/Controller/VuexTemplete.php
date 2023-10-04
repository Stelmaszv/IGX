<?php

namespace App\Core\Controller;

class VuexTemplete
{
    var $code;
    var $keywords;
    var $start = '{{';
    var $end = '}}';

    public function __construct($url){
        $this->code=file_get_contents($url);
        $this->code = str_replace("\r\n",'',$this->code);
        $this->code = str_replace("\r",'',$this->code);
        $this->code = str_replace("\n",'',$this->code);
        $this->code = str_replace("\t",'',$this->code);
        $this->keywords=array();
    }
    private function CAdd($keyword,$text){
        $this->keywords[$keyword]=$text;
    }

    private function CLoop(string $array_id,$array_name){
        $count_table = count($array_name);

        if ($count_table > 0) {
            $start_pos = strpos(strtolower($this->code), '<loop name="'.$array_id.'">') + strlen('<loop name="'.$array_id.'">');
            $end_pos = strpos(strtolower($this->code), '</loop name="'.$array_id.'">');

            $loop_code = substr($this->code, $start_pos, $end_pos - $start_pos);
            $start_tag = substr($this->code, strpos(strtolower($this->code), '<loop name="'.$array_id.'">'), strlen('<loop name="'.$array_id.'">'));
            $end_tag = substr($this->code, strpos(strtolower($this->code), '</loop name="'.$array_id.'">'), strlen('</loop name="'.$array_id.'">'));

            if ($loop_code != '') {
                $new_code = '';
                foreach ($array_name as $array_item) {
                    $temp_code = $loop_code;
                    foreach ($array_item as $key => $value) {
                        $temp_code = str_replace($this->start . $key . $this->end, $value, $temp_code);
                    }
                    $new_code .= $temp_code;
                }
                $this->code = str_replace($start_tag . $loop_code . $end_tag, $new_code, $this->code);
            }
        } else {
            $this->code = preg_replace('/<loop name="'.$array_id.'">(.*)<\/loop name="'.$array_id.'">/i', '', $this->code);
        }
    }

    private function CIf($keyword, $text){
        if ($text==1) {
            $this->code = str_replace('<if name="'.$keyword.'">','', $this->code);
            $this->code = str_replace('</if name="'.$keyword.'">','', $this->code);
        }
        else {
            $this->code = preg_replace('/<if name="'.$keyword.'">(.*)<\/if name="'.$keyword.'">/i', '', $this->code); //czyszczenie nie przypisanych
        }
    }


    public function CGet() {
        reset($this->keywords);
        foreach ($this->keywords as $key => $val) {
            $this->code = str_replace($key, $val, $this->code);
        }

        $this->code = preg_replace('/\{{(.*?)}\}/i', '', $this->code); //czyszczenie nie przypisanych
        $this->code = preg_replace('/<if name="(.*?)">/i', '', $this->code); //czyszczenie nie przypisanych
        $this->code = preg_replace('/<\/if name="(.*?)">/i', '', $this->code); //czyszczenie nie przypisanych
        $this->code = preg_replace('/<loop name="(.*?)">/i', '', $this->code); //czyszczenie nie przypisanych
        $this->code = preg_replace('/<\/loop name="(.*?)">/i', '', $this->code); //czyszczenie nie przypisanych

        return $this->code;
    }

    public function getVarables(array $attributes){
        foreach ($attributes as $key => $attribute){
            if(is_string($attribute)){
                $this->CAdd($this->start.''.$key.''.$this->end,$attribute);
            }elseif (is_array($attribute)){
                $this->CLoop($key,$attribute);
            }elseif (is_bool($attribute)){
                $this->CIf('zero',$attribute);
            }
        }
    }
}