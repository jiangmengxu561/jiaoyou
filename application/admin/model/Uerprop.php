<?php

namespace app\admin\model;

use think\Model;


class Uerprop extends Model
{

    

    

    // 表名
    protected $name = 'uerprop';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'starttime_text',
        'endtime_text'
    ];
    

    



    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setEndtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function prpop()
    {
        return $this->belongsTo('Prpop', 'c_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
