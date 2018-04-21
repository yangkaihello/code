## GIT  添加版本标签

> 注意事项
> > git 打标签的版本是根据commit提交的版本内容

*  添加最新版本标签

  ```
  #本地添加
  git tag -a v1.0[版本] -m "提交信息"

  #添加推送
  git push origin v1.0[版本]
  ```

* 版本标签删除

  ```
  #本地删除
  git tag -d v1.0[版本]

  #删除推送
  git tag origin :v1.0[版本]
  ```

* 查看标签列表

  ```
  git tag -l
  ```
