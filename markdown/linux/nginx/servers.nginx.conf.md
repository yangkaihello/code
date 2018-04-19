## PHP 的pathinfo 开启

* 创建pathinfo.conf 对文件内写入以下配置
  ```
  fastcgi_split_path_info ^(.+?\.php)(/.*)$;
  set $path_info $fastcgi_path_info;
  fastcgi_param PATH_INFO       $path_info;
  try_files $fastcgi_script_name =404;
  ```

* 创建fastcgi.conf 对文件内写入以下配置
    ```
    fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    fastcgi_param  QUERY_STRING       $query_string;
    fastcgi_param  REQUEST_METHOD     $request_method;
    fastcgi_param  CONTENT_TYPE       $content_type;
    fastcgi_param  CONTENT_LENGTH     $content_length;

    fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
    fastcgi_param  REQUEST_URI        $request_uri;
    fastcgi_param  DOCUMENT_URI       $document_uri;
    fastcgi_param  DOCUMENT_ROOT      $document_root;
    fastcgi_param  SERVER_PROTOCOL    $server_protocol;
    fastcgi_param  REQUEST_SCHEME     $scheme;
    fastcgi_param  HTTPS              $https if_not_empty;

    fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
    fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;

    fastcgi_param  REMOTE_ADDR        $remote_addr;
    fastcgi_param  REMOTE_PORT        $remote_port;
    fastcgi_param  SERVER_ADDR        $server_addr;
    fastcgi_param  SERVER_PORT        $server_port;
    fastcgi_param  SERVER_NAME        $server_name;

    # PHP only, required if PHP was built with --enable-force-cgi-redirect
    fastcgi_param  REDIRECT_STATUS    200;
    # fastcgi_param PHP_ADMIN_VALUE "open_basedir=$document_root/:/tmp/:/proc/";
    ```
* 创建enable-php-pathinfo.conf 对文件内写入以下配置 通过include来使用
  ```
  #引入pathinfo.conf 配置使用
  location ~ [^/]\.php(/|$)
  {
      fastcgi_pass  unix:/tmp/php-cgi.sock;
      fastcgi_index index.php;
      include fastcgi.conf;
      include pathinfo.conf;
  }
  ```

## laravel 项目配置

  * 在服务器配置中添加以下代码
    ```
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    ```

## 对服务器的静态文件进行缓存配置

  * 创建cache.conf 对文件内写入以下配置 通过include来使用
    ```
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
          {
                  expires      30d;
                  access_log off;
          }

    location ~ .*\.(js|css)?$
          {
                  expires      12d;
                  access_log off;
          }

    ```
