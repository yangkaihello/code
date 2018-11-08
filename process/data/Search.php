<?php

namespace process\data{

    class Search{

        public function like($array,$lineNumber,$keyword)
        {
            $data = [];
            
            foreach($array as $key=>$value)
            {
                $this->stdClass($value);    //格式化数据

                if(strstr($value->$lineNumber,$keyword) !== false)
                {
                    array_push($data,$value); 
                }
            }
            
            return $data;
        }

        public function stdClass(&$arr)
        {
            $arr = json_decode(json_encode($arr));
        }

    }

}

