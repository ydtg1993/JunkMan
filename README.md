# JunkMan

### php生产环境代码段监控,gc日志收集分析
<table><thead><tr><th style="text-align:center;">JunkMan</th>
<th style="text-align:left;">PHP</th>
<th style="text-align:left;">xdebug</th>
</tr></thead><tbody><tr><td style="text-align:left;">v1.0</td>
<td style="text-align:left;">&gt;=7.0.x</td>
<td style="text-align:left;">&gt;=2.6.0</td>
</tr></tbody></table>

### 安装方式
    方式一. 直接clone到项目目录 require_once YOUR_PATH/JunkMan/autoload.php;
    
    方式二. 使用composer直接引入 composer require stream/junkman

#### 代码段监测 stream

    JunkMan::stream()->start('监测点名称');
    #监测代码段
    JunkMan::stream()->end();
    
    
#### 运行时长的任务检测 flood

    JunkMan::flood()->start('监测点名称');
    
    while(true){
      #监测代码段
      #if ... break
      #flush将日志消息冲刷出去
      JunkMan::flood()->flush();
    }
    
    JunkMan::flood()->end();   
    
#### 单条数据收集 spot

    JunkMan::spot()->dot('监测点名称',$data); 
