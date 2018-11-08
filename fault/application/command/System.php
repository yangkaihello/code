<?php
namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
class System extends Command
{
	// 配置定时器的信息
	protected function configure()
	{
		$this->setName('System')
	    	->setDescription('System');
	}
	protected function execute(Input $input, Output $output)
	{
		// 输出到日志文件
		$output->writeln("TestCommand:");
		// 定时器需要执行的内容
		// .....
		$output->writeln("end....");
	}
}