<?php


    /*
     * url处理拼接
     */
    if (!function_exists('paramStringUrl')) {

        function paramStringUrl($url,array $param,$linkParam = []){
            $param = $linkParam+$param;
            $param = http_build_query($param);
            $param = $param ? $url . '?' . $param : $url;
            return $param; 
        }

    }

    /*
     * 字符串图片批量处理
     */
    if (!function_exists('htmlImgAddHref')) {

        function htmlImgAddHref($string){
            preg_match_all('/<img.*\/>/iUs', $string, $arr);
            foreach($arr[0] as $value){
                preg_match("/src=[\"|']?([^\"'>]+)[\"|']?/i",$value,$src);
                $string = str_replace($value,"<a href='{$src[1]}' target='_blank'>{$value}</a>",$string);
            }
            return $string;
        }

    }

    /*
     * 密码加密字符
     */
    if (!function_exists('encrypt_character')) {

        function encrypt_character($length = 6){

            // 加密字符集，可任意添加你需要的字符
            $chars  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
            $max    = strlen($chars)-1;
            $str    = '';

            for($i=0; $i < $length; $i++){
                $str .= $chars[rand(0,$max)];
            }

            return $str;
        }

    }

    /*
     * 上传文件公用方法
     */
    if (!function_exists('file_upload')) {
        function file_upload( $file, $sub_dir, $ext='jpg,png,gif,jpeg', $check = true )
        {
            $absolute_path = ROOT_PATH . 'public' . DS . 'uploads/' . $sub_dir . DS . date( 'Ymd', time() );
            $relative_path = '/uploads/' . $sub_dir. '/' . date( 'Ymd', time() );

            if ( !file_exists( $absolute_path ) ) {
                if ( !mkdir( $absolute_path, 0755, true ) ) {
                    $msg[ 'code' ]    = 112;
                    $msg[ 'message' ] = "目录 {$absolute_path} 创建失败！";
                    return json( $msg );
                }
            }

            if($check){
                $info = $file->validate( [ 'ext' => $ext ] )->rule( 'uniqid' )->move( $absolute_path );
            }else{
                $info = $file->rule( 'uniqid' )->move( $absolute_path );
            }

            if ( $info ) {
                $msg[ 'code' ]      = 100;
                $msg[ 'file_url' ]  = $relative_path . '/' . $info->getSaveName();
                $msg[ 'file_name' ] = $info->getSaveName();
            } else {
                $msg[ 'code' ]    = 106;
                $msg[ 'message' ] = $file->getError();
            }

            return $msg;
        }
    }

    /*
     * 建立post请求
     */
    if (!function_exists('request_post')) {
        function request_post( $url, $data, $timeout = 30 )
        {
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
            curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout ); // 设置超时限制防止死循环

            curl_setopt( $ch, CURLOPT_POST, 1 ); // 发送一个常规的Post请
            curl_setopt( $ch, CURLOPT_POSTFIELDS, is_string( $data ) ? $data : http_build_query( $data ) ); // Post提交的数据包求

            $result = curl_exec( $ch );
            curl_close( $ch );

            return $result;
        }
    }

    /*
     * 建立get请求
     */
    if(!function_exists('request_get')){
        function request_get( $url, $timeout = 30 ){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 设置超时限制防止死循环

            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }
    }

    /*
     * sha1--解密加密
     * @param string    $string 被加密字符串
     * @param bool      $isEncrypt true-加密 false-解密
     * @param string    $key 加密密钥
     */
    if (!function_exists('sha1_dencrypt')) {
        function sha1_dencrypt( $string, $isEncrypt = true, $key = 'cxsha1' )
        {
            if ( !isset( $string{0} ) || !isset( $key{0} ) ) {
                return false;
            }

            $dynKey   = $isEncrypt ? hash( 'sha1', microtime( true ) ) : substr( $string, 0, 40 );
            $fixedKey = hash( 'sha1', $key );

            $dynKeyPart1   = substr( $dynKey, 0, 20 );
            $dynKeyPart2   = substr( $dynKey, 20 );
            $fixedKeyPart1 = substr( $fixedKey, 0, 20 );
            $fixedKeyPart2 = substr( $fixedKey, 20 );

            $key = hash( 'sha1', $dynKeyPart1 . $fixedKeyPart1 . $dynKeyPart2 . $fixedKeyPart2 );

            $string = $isEncrypt ? $fixedKeyPart1 . $string . $dynKeyPart2 : ( isset( $string{339} ) ? gzuncompress( base64_decode( substr( $string, 40 ) ) ) : base64_decode( substr( $string, 40 ) ) );

            $n      = 0;
            $result = '';
            $len    = strlen( $string );

            for ( $n = 0; $n < $len; $n++ ) {
                $result .= chr( ord( $string{$n} ) ^ ord( $key{$n % 40} ) );
            }

            return $isEncrypt ? $dynKey . str_replace( '=', '', base64_encode( $n > 299 ? gzcompress( $result ) : $result ) ) : substr( $result, 20, -20 );
        }
    }

    /*
     * url 参数拼接
     */
    if (!function_exists('request_param_handle')) {
        function request_param_handle($arr)
        {
            if ($arr) {
                foreach ($arr as $k => $v) {
                    if (strpos($k, '/') !== false) {
                        unset($arr[$k]);
                    }
                }
            }

            return $arr;
        }
    }

    /*生成URL参数字符串*/
    if (!function_exists('build_query')) {
        function build_query($arr)
        {
            $paramstring = "?";
            foreach ($arr as $key => $value) {
                if (strlen($paramstring) == 0)
                    $paramstring .= $key . "=" . $value;
                else
                    $paramstring .= "&" . $key . "=" . $value;
            }

            return $paramstring;
        }
    }

    /*
     * 移动文件到新位置
     */
    if (!function_exists('move_file')) {
        function move_file($file_path, $sub_dir)
        {
            $absolute_path = ROOT_PATH . 'public' . DS . 'uploads/' . $sub_dir . DS . date('Ymd', time());
            $relative_path = '/uploads/' . $sub_dir . '/' . date('Ymd', time());

            if (!file_exists($absolute_path)) {
                if (!mkdir($absolute_path, 0755, true)) {
                    $msg['code'] = 112;
                    $msg['message'] = "目录 {$absolute_path} 创建失败！";
                    return json($msg);
                }
            }

            $file_name      = substr($file_path,strripos($file_path,'/'),strlen($file_path));
            $need_file      = ROOT_PATH . 'public' .$file_path;
            $replace_file   = $absolute_path.$file_name;

            $res = copy($need_file,$replace_file);

            if( $res ){
                $msg['code']        = 100;
                $msg['file_url']    = $relative_path.$file_name;
            }else{
                $msg['code']    = 106;
                $msg['message'] = '文件上传失败';
            }

            return $msg;
        }
    }

    /*
     * 下载微信公众号二维码
     */
    if (!function_exists('download_qrcode')) {
        function download_qrcode($account_id, $sub_dir)
        {
            $path          = "https://open.weixin.qq.com/qr/code?username=".$account_id;
            $absolute_path = ROOT_PATH . 'public' . DS . 'uploads/' . $sub_dir . DS . date('Ymd', time());
            $relative_path = '/uploads/' . $sub_dir . '/' . date('Ymd', time());

            $file_name = '/'.$account_id.'.jpg';

            if (!file_exists($absolute_path)) {
                if (!mkdir($absolute_path, 0755, true)) {
                    $msg['code'] = 112;
                    $msg['message'] = "目录 {$absolute_path} 创建失败！";
                    return json($msg);
                }
            }

            $replace_file = $absolute_path.$file_name;

            $res = copy($path,$replace_file);

            if( $res ){
                $msg['code']        = 100;
                $msg['file_url']    = $relative_path.$file_name;
            }else{
                $msg['code']    = 106;
                $msg['message'] = '文件上传失败';
            }

            return $msg;

        }
    }

    /*
     * 获取城市区域信息
     */
if (!function_exists('getArea')) {
    function getArea($keywords)
    {

        if($keywords)
            $param['keywords'] = $keywords;

        $param['key'] = 'cc6eb16307593be36df18ac239cf7294';
        $param['subdistrict'] = 1;

        $query = http_build_query($param);

        $url = 'https://restapi.amap.com/v3/config/district?' . $query;

        $result_json    = request_get($url);
        $result         = json_decode($result_json,true);

        $msg['code'] = $result['status'] == 1 ? 100 : 101;
        $msg['list'] = $result['status'] == 1 ? $result['districts'][0]['districts']: array();

        return $msg;
    }
}