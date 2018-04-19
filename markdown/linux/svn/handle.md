## SVN linux 操作命令

* 项目导出
  ```
  #使用 yangkai 账号 -- 导入当前所有SVN项目到 /home/testtools 目录中 (带有 .SVN 版本控制)
  svn checkout svn://（IP地址）/ /home/testtools --username="yangkai" --password="yangkai"

  #不带 .SVN 版本控制的导入
  svn export svn://（IP地址）/ /home/testtools  --username="yangkai" --password="yangkai"
  ```

* 项目中无需更新的文件删除
  ```
  #版本库删除操作
  svn update --set-depth=exclude [文件路径]

  #版本库恢复操作
  svn update --set-depth=infinity [文件路径]
  ```
