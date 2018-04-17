## SVN linux 操作命令

* 项目导出
  ```
  #使用 yangkai 账号 -- 导入当前所有SVN项目到 /home/testtools 目录中 (带有 .SVN 版本控制)
  svn checkout svn://（IP地址）/ /home/testtools --username="yangkai" --password="yangkai"

  #不带 .SVN 版本控制的导入
  svn export svn://（IP地址）/ /home/testtools  --username="yangkai" --password="yangkai"
  ```
SVN 服务器的项目控制到此完结
