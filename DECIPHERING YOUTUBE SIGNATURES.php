	

    <?
    /* 
    
    OBSOLETO
    
    DECIPHERING YOUTUBE SIGNATURES
       $_loc2-> deciphered signature */
     
    $swf =str_replace("\\","",extrae(file_get_contents($youtubeurl),"ytplayer.config =",'"url": "','"'));
     
    $resp=bin2hex(gzuncompress(substr(file_get_contents($swf),8)));
     
    $fdecipher=explode("800dd6d22c0146",explode("d030d12c01",$resp)[1])[0];
                     
                      // analyze subfunctions
                     
                      $cod =explode("46",$fdecipher);
                      $cod1=$cod[0];
                      $cod =array_slice($cod,1);
                     
		  $ncall=array_slice(explode("d65d",$fdecipher),1);
		  			  
		  $ncall=substr($ncall[0],2,2);
					    
		  $funcs=$datas=$nfuncs=$nfuncs2=array();		  
		  		  
		  foreach ($cod as $co)
		  	{
		  	if (substr($co,2,2)==$ncall) 
		  		{
				$call=substr($co,0,2);
				
				$funcs[]=$call;
				
		  		$data=substr($cod1,-4);
		  				  		
				if ($data=="") $data="2446";
				
				if (substr($data,0,2)!="24") 
					$nfuncs2[$call]="reverse"; 
				else    
					{
					if (($push=hexdec(substr($data,2,2)))>5) 
					       $nfuncs2[$call]="swap"; 
					else   $nfuncs2[$call]="slice";
					}
			
				$datas[]=$push;
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

