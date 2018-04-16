## 创建SVN的进程树

* 进程守护

  ```
  #我们需要先查看下 svnserve 是否已经启动
  ps -ef | grep svn

  #如果已经启动需要先杀死进程对最新的版本库配置进行更新
  kill -9 （对应的端口号）

  #就可以正常的创建SVN的进程
  svnserve -d -r /var/svn/svnserve
  ```

* 配置修改

  ```
  #（/var/svn/svnserve）  创建的版本库中会存在很多的项目文件
  #其中 （/var/svn/svnserve/conf/）中的文件是我们正常需要修改的配置文件
  ```

  * svnserve 服务配置文件

  ```
  #我们只需要对 general 配置项进行对应的修改就可以了
  [general]
  anon-access = none                     #控制非鉴权用户访问版本库的权限
  auth-access = write                    #控制鉴权用户访问版本库的权限
  password-db = passwd                   #指定用户名口令文件名
  authz-db = authz                       #指定权限配置文件名
  realm = /var/svn/svnserve/something    #指定版本库的认证域，即在登录时提示的认证域名称
  ```

  * passwd 用户配置文件

  ```
  #配置一个 用户名:yangkai,密码:yangkai ;的SVN用户
  [users]
  yangkai = yangkai
  ```

  * authz 权限配置文件

  ```
  #用户组定义
  [groups]
  all=yangkai,root,linux    //定义一个组(all)，yangkai,root,linux 属于完全开放用户
  project=guest,general     //定义一个组(project)，guest,general 属于只对project项目开放
  #SVN 全部项目都可以访问
  [/]
  yangkai=rw
  @all=rw
  #来宾用户只能访问 project 项目
  [/project]  
  guest=rw
  @project=rw
  ```
