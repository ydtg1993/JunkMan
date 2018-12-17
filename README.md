# JunkMan

### php运行环境代码段监控,gc收集分析

    环境要求:
    xdebug >= 2.6

### 安装方式
    1.直接clone到项目目录 require_once YOUR_PATH/JunkMan/autoload.php;
    2.使用composer引入 composer require stream/junkman

#### 代码段监测

    JunkMan::stream()->start('监测点名称');
    #监测代码段
    JunkMan::stream()->end();
    
    
#### 长时间循环代码段监测 用flush的refurbish冲刷消息

    JunkMan::flush()->start('监测点名称');
    
    while(true){
      #监测代码段
      JunkMan::flush()->refurbish();
    }
    
    JunkMan::flush()->end();   
    
#### 单个数据消息

    JunkMan::spot()->dot('监测点名称',$data); 
