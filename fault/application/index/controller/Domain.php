<?php
/**
 * Created by PhpStorm.
 * User: yangkai
 * Date: 2018/10/28
 * Time: 下午2:50
 */

namespace app\index\controller;

use think\Db;
use think\Loader;
use app\index\model\DomainIndex;
use PHPExcel_IOFactory;
use PHPExcel;

class Domain extends Common
{

    public function list()
    {
        $request    = request();
        $model      = (new DomainIndex);

        $model->search($request);

        return $this->fetch('list',[
            'title' => '故障库列表',
            'model' => $model->order('id desc')->paginate(35,false, ['query' => $request->param()]),
        ]);
    }

    public function show()
    {
        $request = request();
        if($request->has('id'))
        {
            $this->assign( 'data' ,DomainIndex::get($request->param('id')) );
        }
        return $this->fetch('show',[
            'title' => '故障登记',
        ]);
    }

    public function handle()
    {
        $request    = request();
        $model      = new DomainIndex();
        $validate   = Loader::validate('Domain');

        $scene  = $request->has('id') ? 'edit' : 'add';
        if( !$validate->batch()->scene($scene)->check($request->post()) )
        {
            $data = $validate->getError();
            $code = 404;
        }else{
            if( $request->has('id') )
            {
                $model = $model->find($request->post('id'));
            }
            $model = $model->createOrUpdate($request->post());
            $model->save();

        }

        return json($data,$code ?? 200);
    }

    public function delete()
    {
        return DomainIndex::destroy(request()->post('ids'));
    }

    public function export()
    {

        $PHPExcel = new PHPExcel(); //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
        $request    = request();
        $model      = (new DomainIndex);

        $model->search($request);
        
        $PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
        $PHPSheet->setTitle('域名列表'); //给当前活动sheet设置名称
        $PHPSheet->setCellValue('A1','序号')
                ->setCellValue('B1','域名')
                ->setCellValue('C1','启用日期')
                ->setCellValue('D1','备注')
                ->setCellValue('E1','所属产品')
                ->setCellValue('F1','域名类型')
                ->setCellValue('G1','域名状态');

        $model = $model->select();

        foreach($model as $key=>$val)
        {
            $line = $key+2;
            $PHPSheet->setCellValue('A'.$line,$val->id)
                    ->setCellValue('B'.$line,$val->entire)
                    ->setCellValue('C'.$line,$val->started_date)
                    ->setCellValue('D'.$line,$val->remark)
                    ->setCellValue(
                        'E'.$line,
                        config("domain.product")[$val->product]
                    )
                    ->setCellValue(
                        'F'.$line,
                        config("domain.type")[$val->type]
                    )
                    ->setCellValue(
                        'G'.$line,
                        config("domain.status")[$val->status]
                    );
        }

        $PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，‘Excel5’表示生成2003版本Excel文件

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header('Content-Disposition: attachment;filename="DomainList.xlsx"');//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $PHPWriter->save("php://output");

    }

}