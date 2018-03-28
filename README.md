##### 简介
##### 交互参数验证微服务
##### 配置说明
```
    [
         "paramsName" => ~参数验证配置 
         [
            "name1"=>["condition" => ["type"=>"int|float|double|numberic","empty"=>true,"range"=>["<:10",">=:20"],"in"=>[1,2,3,4],"!in"=>[1,2,3,4]],"tail_handle"=>function()],
            "name2"=>["condition" => ["type"=>"string","len"=>["=:10",">:100"],"trim"=>" ","phone"=>true,"email"=>true,"http"=>"post"],"tail_handle"=>function()],
            "name3"=>["condition" => ["type"=>"bool|boolean","http"=>"get"],"tail_handle"=>function()]],
            "name4"=>["condition" => ["type"=>"json","decode"=>"array|obj"],"tail_handle"=>function()]],
         ],
         "data" => [] //参数值
    ]
    
    PS : 1. type  => int 整型
                  => float 浮点型
                  => double 双浮点型
                  => numberic 数值
                  => string 字符串
                  => bool|boolean 布尔型
                  => json json字符串
            指定参数的类型,可选
         2 . empty => true | false
            是否可以为空
         3 . in => [数值1，数值2，数值3]
            是否相等于数组中的某个数值
         4 . !in => [数值1，数值2，数值3]
            是否不相等于数组所有的数组
         5 . trim => "\b\t\n\c"
            去掉字符串中指定的字符,PHP中的trim()函数的匹配字符参数
         6 . email => true|false
            是否验证为电子邮件格式
         7 . phone => [
                "RPC" => 中国大陆电话号码,
                "TW" => 台湾电话号码,
                "HK" => 香港电话号码,
                "AM" => 澳门电话号码,
                "CHINA" => 中国电话号码,
                "ALL" => 所有电话号码,
                "CMCC" => 中国移动电话号码,
                "CUCC" => 中国联通电话号码,
                "CTCC" => 中国电信电话号码
            ]
            检查字符串是否为特定地区的电话号码,进行或验证,地区验证可自由组合到数组中
         8 . range => [
                "=" => 等于,
                "===" => 全等于,
                "<" => 小于,
                "<=" => 小于等于,
                ">" => 大于,
                ">=" => 大于等于,
                "!=" => 不等于,
                "<>" => 不等于,      
            ],
            检查数组的大小范围是否服务要求
         9 . len => [
                "=" => 等于,
                "===" => 全等于,
                "<" => 小于,
                "<=" => 小于等于,
                ">" => 大于,
                ">=" => 大于等于,
                "!=" => 不等于,
                "<>" => 不等于,      
            ],
            检查字符串的长度范围是否服务要求 
         10 . http => get | post 当前参数的提交方式
         11 . tail_handle => function($paramsName,$value){
            检验完参数后，如设置此参数，则调用当前匿名函数，传入参数名作为$paramsName,参数值为$value,
            return的数据会自动保存，并通过当前server程序返回。返回结果
            [
                ......，
                "newData" => ["参数名"=>处理后的数据],
                ......
            ]
         }                   
```


