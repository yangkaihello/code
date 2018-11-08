<?php

namespace process{

    class Search{

        public static function integralResourceLog($model,$request,$method = 'get')
        {

            if($request->$method('source_role','all') != 'all')
            {
                $model->where('source_role','=',$request->get('source_role'));
            }

            if($request->$method('source_id','') != '')
            {
                $model->where('source_id','=',$request->get('source_id'));
            }

            if($request->$method('date','') != '')
            {
                $data = $request->$method('date');
                $model->where( 'create_time','between time',[$data,date('Y-m-d',strtotime('+1 month',strtotime($data)))] );
            }

            return $model;
        }

        public static function integralRole($model,$request,$method = 'get')
        {

            if($request->$method('source_role','all') != 'all')
            {
                $model->where('source_role','=',$request->get('source_role'));
            }

            if($request->$method('source_id','') != '')
            {
                $model->where('source_id','=',$request->get('source_id'));
            }

            return $model;
        }

    }

}

