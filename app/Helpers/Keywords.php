<?php

namespace App\Helpers;

class Keywords
{
    var $origin_arr;
    var $modif_arr;
    var $min_word_length = 4;

    public function get($text)
    {
        $this->explode_str_on_words($text);
        $this->count_words();
//        $arr = array_slice($this->modif_arr, 0, 30);
        $arr = $this->modif_arr;
        $str = "";
        foreach ($arr as $key => $val) {
            $str .= $key . ", ";
        }

        return trim(substr($str, 0, strlen($str) - 2));
    }

    private function explode_str_on_words($text)
    {
        $search = ["'ё'",
            "'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
            "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
            "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
            "'&(quot|#34);'i",                 // Замещаются html-элементы
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&#(\d+);'"];
        $replace = ["е",
            " ",
            " ",
            "\\1 ",
            "\" ",
            " ",
            " ",
            " ",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            "chr(\\1)"];
        $text = preg_replace($search, $replace, $text);
        $del_symbols = [",", ".", ";", ":", "\"", "#", "\$", "%", "^",
            "!", "@", "`", "~", "*", "-", "=", "+", "\\",
            "|", "/", ">", "<", "(", ")", "&", "?", "¹", "\t",
            "\r", "\n", "{", "}", "[", "]", "'", "“", "”", "•",
            "как", "для", "что", "или", "это", "этих",
            "всех", "вас", "они", "оно", "еще", "когда",
            "где", "эта", "лишь", "уже", "вам", "нет",
            "если", "надо", "все", "так", "его", "чем",
            "при", "даже", "мне", "есть", "раз", "два",
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        ];
        $text = str_replace($del_symbols, [" "], $text);
        $text = preg_replace("( +)", " ", $text);
        $this->origin_arr = explode(" ", trim($text));

        return $this->origin_arr;
    }

    private function count_words()
    {
        $tmp_arr = [];
        foreach ($this->origin_arr as $val) {
            if (strlen($val) >= $this->min_word_length) {
                $val = strtolower($val);
                if (array_key_exists($val, $tmp_arr)) {
                    $tmp_arr[$val]++;
                } else {
                    $tmp_arr[$val] = 1;
                }
            }
        }
        arsort($tmp_arr);
        $this->modif_arr = $tmp_arr;
    }
}