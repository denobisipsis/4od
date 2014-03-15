	

    <?
    /* DECIPHERING YOUTUBE SIGNATURES
       $_loc2-> deciphered signature */
     
    $swf =str_replace("\\","",extrae(file_get_contents($youtubeurl),"ytplayer.config =",'"url": "','"'));
     
    $resp=bin2hex(gzuncompress(substr(file_get_contents($swf),8)));
     
    $fdecipher=explode("800dd6d22c0146",explode("d030d12c01",$resp)[1])[0];
                     
                      // analyze subfunctions
                     
                      $cod =explode("46",$fdecipher);
                      $cod1=$cod[0];
                      $cod =array_slice($cod,1);
                     
                      $funcs=$datas=$nfuncs=array();                 
                                     
                      foreach ($cod as $co)
                            {
                            if (substr($co,2,2)=="28")
                                    {
                                    $call=substr($co,0,2);
                                   
                                    $funcs[]=$call;
                                   
                                    $data=substr($cod1,-4);
                                     
                                    // control que los data no sean también 46
                                   
                                    if ($data=="") $data="2446";
                                   
                                    $push=$datas[]=hexdec(substr($data,2,2));
     
                                    if (substr($data,0,2)!="24")
                                            $nfuncs2[$call]="reverse";
                                    else
                                            {
                                            if ($push>5) // swap always > 5?
                                                    $nfuncs2[$g]="swap";
                                            else    $nfuncs2[$g]="slice";
                                            }                              
                                    }
                            $cod1=$co;
                            }
                   
                    // ejecuta las acciones
                                                   
                    $n=0;
                                           
                    foreach ($funcs as $gofunc)
                            {
                            switch ($nfuncs2[$gofunc])
                                    {
                                    case "slice":
                                            $_loc2_ = array_slice($_loc2_,$datas[$n]);
                                            if (!$nosave) $ciphering[]=" slice $datas[$n] ";
                                            break;
                                    case "reverse":
                                            $_loc2_ = array_reverse($_loc2_);
                                            if (!$nosave) $ciphering[]=" reverse ";
                                            break;
                                    case "swap":
                                            $_loc2_ = swap($_loc2_,$datas[$n]);
                                            if (!$nosave) $ciphering[]=" swap $datas[$n] ";                        
                                    }
                            ++$n;
                            }
                           
    $ciphering="ciphering ".implode($ciphering);
    ?>

