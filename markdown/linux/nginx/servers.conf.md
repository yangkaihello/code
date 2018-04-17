## PHP 的pathinfo 开启

* 创建pathinfo.conf 对文件内写入以下配置
  ```
  fastcgi_split_path_info ^(.+?\.php)(/.*)$;
  set $path_info $fastcgi_path_info;
  fastcgi_param PATH_INFO       $path_info;
  try_files $fastcgi_script_name =404;
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
