## 安装 SVN 环境

1. ###### 首先通过 镜像库下载SVN

  ```
  //可以对SVN 进行安装 (不同的操作系统源都不同)  详细请百度
  yum install subversion

  //安装完成后 查看版本
  svnserve --version    
  ```

2. ###### 安装完成后 SVN命令确认可以执行 我们需要创建一个容纳SVN 版本的空间

  ```
  //对SVN的版本库目录进行创建  （可以是任何目录）
  mkdir /var/svn/svnserve

  //对创建的目录 进行svnadmin 命令来创建SVN的版本库
  svnadmin create /var/svn/svnserve    
  ```
3. SVN 的基本安装就完成了下一步是对SVN版本库的进程进行守护才可以对SVN进行一个(局域网|互联网)访问 [进程创建](./pstree.md)
