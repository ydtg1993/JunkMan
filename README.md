# JunkMan
    php生产环境代码段监控,gc日志收集分析
    
<table><thead><tr><th style="text-align:center;">JunkMan</th>
<th style="text-align:left;">PHP</th>
<th style="text-align:left;">xdebug</th>
</tr></thead><tbody><tr><td style="text-align:left;">v1.0</td>
<td style="text-align:left;">&gt;=7.0.x</td>
<td style="text-align:left;">&gt;=2.6.0</td>
</tr></tbody></table>

### 1. 安装方式
方式一 `composer安装 composer require stream/junkman "1.0.0"`  
    
方式二 `直接clone工具包 require_once (YOUR_PATH)/JunkMan/autoload.php`

### 2. 命令行启动transfer服务

windows `start /B (YOUR_PATH)/JunkMan/src/JunkManTransfer.exe`

linux `(YOUR_PATH)/JunkMan/src/JunkManTransfer &`

### 3. code添加监测点

##### 代码段监测 stream

    JunkMan::stream()->start('监测点名称');
    #监测代码段
    JunkMan::stream()->end();
    
    
##### 运行长时任务监测 flood

    JunkMan::flood()->start('监测点名称');
    
    while(true){
      #监测代码段
      #if ... break
      #flush将日志消息冲刷出去
      JunkMan::flood()->flush();
    }
    
    JunkMan::flood()->end();   
    
##### 单条数据监测 spot

    JunkMan::spot()->dot('监测点名称',$data); 
    
    
## 客户端下载安装
[JunkMonitor](https://github.com/ydtg1993/JunkMonitor.git)
