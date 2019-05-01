<p align="center"><img src="https://github.com/ydtg1993/JunkMan/blob/master/src/image/iconfinder_Eye.png" width="400px"></p>   
  
## About JunkMan

JunkMan is a toolkit for what it can trace the PHP GC logs by xdebug. And distribute GC logs to the JunkManMonitor.
so that you could monitor the applicaton in time.

<table><thead><tr><th style="text-align:center;">JunkMan</th>
<th style="text-align:left;">PHP</th>
<th style="text-align:left;">xdebug</th>
</tr></thead><tbody><tr><td style="text-align:left;">v1.0</td>
<td style="text-align:left;">&gt;=7.0.x</td>
<td style="text-align:left;">&gt;=2.6.0</td>
</tr></tbody></table>


### 1. build

`composer require stream/junkman "v1.0"`    

### 2. startup JunkManTransfer service

windows    `start /B (YOUR_PATH)/JunkMan/src/JunkManTransfer.exe`

linux    `(YOUR_PATH)/JunkMan/src/JunkManTransfer &`

    tips： chmod -R 0777 (YOUR_PATH)/JunkMan/src/  

### 3. set your outpost

##### stream monitor (monitor code blocks)

    JunkMan::stream()->start('stream watching');
    #code
    JunkMan::stream()->end();
    
    
##### flood monitor (for long time application process)

    JunkMan::flood()->start('flood watching');
    
    while(true){
      #code
      #if ... break
      #flush the message to monitor
      JunkMan::flood()->flush();
    }
    
    JunkMan::flood()->end();   
    
##### spot (monitor a variable)
    $data = 'some message';
    JunkMan::spot()->dot('spot watching',$data); 
    
    
## JunkMonitor for watch the GC logs
[JunkMonitor](https://github.com/ydtg1993/JunkMonitor.git)

    tips： the remote server should open firewall port for 9303
    command:
    iptables -A INPUT -p tcp --dport 9303 -j ACCEPT
    iptables -A OUTPUT -p tcp --sport 9303 -j ACCEPT
    service iptables save
