# JunkMan

### php运行环境代码段监控,gc收集分析

    环境要求:
    xdebug >= 2.6
    pcntl 可选择安装
    

#### 代码段监测

    require_once YOUR_PATH/JunkMan/autoload.php;
    JunkMan::stream()->start('this is a test');
    #监测代码段
    JunkMan::stream()->end();
    
    
#### 长时间循环代码段监测 用flush的refurbish冲刷消息

    require_once YOUR_PATH/JunkMan/autoload.php;
    JunkMan::flush()->start('this is a test');
    
    while(true){
      #监测代码段
      JunkMan::flush()->refurbish();
    }
    
    JunkMan::flush()->end();   
    
#### 单个数据消息

    require_once YOUR_PATH/JunkMan/autoload.php;
    #监测代码段
    JunkMan::spot()->dot('test',$data); 
