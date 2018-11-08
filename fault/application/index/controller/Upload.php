<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use PHPExcel;
use PHPExcel_IOFactory;

class Upload extends Controller
{


    public function image()
    {
        $Request = Request::instance();
        // 获取表单上传文件 例如上传了001.jpg
        $files = $Request->file();
        
        // 移动到框架应用根目录/uploads/ 目录下
        foreach($files as $file){
            if($file){
                $root = ROOT_PATH . 'public/';
                $uploadPath = 'uploads';
                $info = $file->validate(['size'=>1048576,'ext'=>'jpg,jpeg,png'])->rule('md5')->move($root . $uploadPath);
                if($info){
                    $filePath = $root . $uploadPath . DS . $info->getSaveName();

                    $this->imageCompress($filePath);    //图片压缩处理
                    // 成功上传后 获取上传信息
                    return json(['default' => DS . $uploadPath . DS . $info->getSaveName()]);
                }else{
                    // 上传失败获取错误信息
                    return json(['code' => 0,'msg' => $file->getError()],403);
                }
            }

        }
    }

    /*
     * 导入数据(xlsx,xls,csv)
     * @param string $filename 文件名
     * @param array $filedList 字段
     * @param bool $isCutting 是否切割数组
     * @param int $size 切割数
     * @return array $data
     */
    public static function import($filename, $filedList = [], $isCutting = false, $size = 1000)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        vendor("PHPExcel.PHPExcel");

        switch ($ext) {

            case 'xlsx':
                $objReader   = PHPExcel_IOFactory::createReader('Excel2007');//new \PHPExcel_Reader_Excel2007();
                $objPHPExcel = $objReader->load($filename);//加载文件
                break;
            case 'xls':
                $objReader   = PHPExcel_IOFactory::createReader('Excel5');//new \PHPExcel_Reader_Excel5();
                $objPHPExcel = $objReader->load($filename);//加载文件
                break;
            case 'csv':
                $objReader   = PHPExcel_IOFactory::createReader('CSV'); //new \PHPExcel_Reader_CSV();
                $objPHPExcel = $objReader->setInputEncoding('GBK')->load($filename);//加载文件
                break;
            default:
                return [];
                break;
        }

        $highestRow     = $objPHPExcel->getSheet()->getHighestRow();    //取得总行数
        $highestColumn  = $objPHPExcel->getSheet()->getHighestColumn(); //取得总列数

        $cellList = [];

        foreach ($filedList as $k => $v) $cellList[] = self::IntToChr($k);

        if ($cellList[count($filedList) - 1] != $highestColumn) return [];

        $data = [];

        for ($i = 0; $i < $highestRow - 1; $i++) {

            for ($j = 0; $j < count($cellList); $j++) {

                $data[$i][$filedList[$j]] = $objPHPExcel->getActiveSheet()->getCell($cellList[$j] . ($i + 2))->getFormattedValue();

                if (is_object($data[$i][$filedList[$j]])) $data[$i][$filedList[$j]] = $data[$i][$filedList[$j]]->__toString();

                $data[$i][$filedList[$j]] = preg_replace("/(\s|\ \;|　|\xc2\xa0)/", "", $data[$i][$filedList[$j]]);

            }

        }

        if ($isCutting)
            return array_chunk($data, $size);

        return $data;
    }

    //图片压缩
    protected function imageCompress($filePath)
    {
        list($width,$height,$suffix) = getimagesize($filePath);

        $image_p = imagecreatetruecolor($width, $height);
        
        switch($suffix){
            case 1: $image = imagecreatefromgif($filePath); break;
            case 2: $image = imagecreatefromjpeg($filePath);break;
            case 3: $image = imagecreatefrompng($filePath); break;
        }

        imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

        switch($suffix){
            case 1: imagegif($image_p,$filePath); break;
            case 2: imagejpeg($image_p,$filePath,50);break;
            case 3: imagepng($image_p,$filePath); break;
        }

    }

    /**
     * 生成Excel列标
     * @param int $index 索引值
     * @param int $start 字母起始值
     * @return string 返回字母
     */
    protected static function IntToChr($index, $start = 65)
    {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= self::IntToChr(floor($index / 26) - 1);
        }
        return $str . chr($index % 26 + $start);
    }

}