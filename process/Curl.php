<?php
namespace process;

class Curl
{
    /**
     * 创建一个curl请求
     * @param string $method
     * @param string $host
     * @param mix $params
     * @param array $options
     * @return object
     */
    public static function request($host, $options = array())
    {
        $defaults = array (
            CURLOPT_HEADER => 1, 
            CURLOPT_RETURNTRANSFER => 1, 
            CURLOPT_TIMEOUT => 10, 
            CURLOPT_CONNECTTIMEOUT => 7, 
            CURLOPT_SSL_VERIFYPEER => 0, 
            CURLOPT_SSL_VERIFYHOST => 0,
        );
        $config = ( array ) $options + $defaults;
        $ch = curl_init ( $host );
        foreach ($config as $key => $val)
        {
            curl_setopt($ch, $key, $val);
        }
        $object = new \stdClass ();

        $object->response = curl_exec ( $ch );
        $object->info = curl_getinfo ( $ch );
        $object->error_code = curl_errno ( $ch );
        $object->error = curl_error ( $ch );
        curl_close ( $ch );
        return $object;
    }
    
    /**
     * post请求
     * @param string $host 
     * @param array $data 
     * @param array $options 
     * @return Ambigous <object, stdClass>
     */
    public static function post($host, $params = null, $options = array())
    {
        $defaults[CURLOPT_CUSTOMREQUEST] = 'POST';
        if(!empty($params))
        {
            $defaults[CURLOPT_POSTFIELDS] = $params;
        }
        $options = $options + $defaults;
        return self::request($host, $options);
    }
    
    /**
     * @param $host string
     * @param $data array
     * @param $options array()
     * get请求
     */
    public static function get($host, $options = array())
    {
        $defaults [CURLOPT_CUSTOMREQUEST] = 'GET';
        $options = $options + $defaults;
        return self::request($host, $options );
    }
    
    /**
     * put请求
     * @param string $host
     * @param mix $params
     * @param array $options
     */
    public static function put($host, $params = null, $options = array())
    {
        !is_null($params) && $defaults [CURLOPT_POSTFIELDS] = $params;
        $defaults [CURLOPT_CUSTOMREQUEST] = 'PUT';
        $options = $options + $defaults;
        return self::request($host, $options);
    }
    
    /**
     * 删除请求
     * @param string $host
     * @param mix $params
     * @param array $options
     */
    public static function delete($host, $params = null, $options = array())
    {
        !is_null($params) && $defaults [CURLOPT_POSTFIELDS] = $params;
        $defaults [CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $options = $options + $defaults;
        return self::request($host, $options);
    }

    /**
     * 删除请求
     * @param string $host
     * @param mix $params
     * @param array $options
     */
    public static function patch($host, $params = null, $options = array())
    {
        !is_null($params) && $defaults [CURLOPT_POSTFIELDS] = $params;
        $defaults [CURLOPT_CUSTOMREQUEST] = 'PATCH';
        $options = $options + $defaults;
        return self::request($host, $options);
    }
    
}