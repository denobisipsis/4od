class myvideode // para PHP4
	{			
    function hexToChars($param1)
    		{
    		$_loc2_ = array();
    		$_loc3_ = 0;
    		while($_loc3_ < strlen($param1))
    			{
    			$_loc2_[]= chr(hexdec(substr($param1,$_loc3_,2)));
    			$_loc3_+= 2;
    			}
    		return $_loc2_;
    		}

    function strToChars($param1)
	         {
	         $_loc2_ = array();
	         $_loc3_ = 0;
	         while($_loc3_ < strlen($param1))
	            {
	            $_loc2_[]=ord($param1[$_loc3_]);
	            $_loc3_++;
	            }
	         return $_loc2_;
	         }
	
	function compat_ord($c)
		    {
	    	if (is_int($c)) return $c;
	    	else 		return ord($c);
	    	}	
		    		    
	function myvideodecrypt($xml, $video_id)
		{		  
		$MASTER_KEY 	= "c8407a08b3c71ea418ec9dc662f2a56e40cbd6d5a114aa50fb1e1079e17f2b83";
		  
		$encxml		    = $this->hexToChars(_explode("=",$xml,1));
						
		$key        	= $this->strToChars(md5($MASTER_KEY.md5($video_id)));
		
		//Desarrollo del método arcfour
		
		$x = 0;
		$box = range(0,255);
		foreach ($box as $i)
			{
			  $x = ($x + $box[$i] + $this->compat_ord($key[$i % sizeof($key)])) % 256;
			  $boxswap = $box[$i];
			  $box[$i] = $box[$x]; 
			  $box[$x] = $boxswap;
		  	}
		$x = 0;
		$y = 0;
		$out = '';
		foreach ($encxml as $char)
			{
			  $x = ($x + 1) % 256;
			  $y = ($y + $box[$x]) % 256;
			  $boxswap = $box[$y];
			  $box[$y] = $box[$x]; 
			  $box[$x] = $boxswap;
			  $out.= chr($this->compat_ord($char) ^ $box[($box[$x] + $box[$y]) % 256]);
		  	}
		return $out;
		}  
		
	function generateUrlToken($path, $token, $duration)
		{
		 $start_time	=time();		
		 $end_time	=$start_time+$duration;
		 
		 $start		=getdate($start_time); 
		
		 $start_time	=$start["year"].sprintf("%02d",$start["mon"]).sprintf("%02d",$start["mday"]).
		 		 sprintf("%02d",$start["hours"]).sprintf("%02d",$start["minutes"]).sprintf("%02d",$start["seconds"]);
				  
		 $end		=getdate($end_time);
		 
		 $end_time	=$end["year"].sprintf("%02d",$end["mon"]).sprintf("%02d",$end["mday"]).
		 		 sprintf("%02d",$end["hours"]).sprintf("%02d",$end["minutes"]).sprintf("%02d",$end["seconds"]);
				  
		 $_loc8_ 	=$path."?start_time=".$start_time."&end_time=".$end_time."#".$token;
		 
		 $_loc10_= md5($_loc8_);
		 
		 $_loc11_= base64_encode("start_time=".$start_time."&end_time=".$end_time."&digest=".$_loc10_);
		 
		 return $_loc11_;
		}
			      
	function get($url,$window)
		{		      
		wb_set_text(wb_get_control($window, 803),"myvideode");
			      		
		$headers = array(		 	          
		          "User-Agent: 		Mozilla/5.0 (Windows NT 6.2; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0",
		          "Accept: 		text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		          "Accept-Encoding: 	gzip, deflate",
			        "Connection: 		keep-alive"
		          );
				  
		$totallinks=array();
		
		$proxy=getproxy("de");
		
		$contenth=curl_proxy($url);
		  	
		$referer="http://is1.myvideo.de/de/player/mingR13q/cartridge/video/video.swf";		
			      	
		$video_id =_explode("/",_explode("/watch/",$url,1),0);
		
		$flashvars=explode(",",extrae($contenth,'var flashvars=','{','};'));
		
		$flash_playertype=str_replace("'","",_explode(":",$flashvars[2],1));
		$ds		         =str_replace("'","",_explode(":",$flashvars[4],1));
		$_countlimit	 =str_replace("'","",_explode(":",$flashvars[5],1));
		
		$xml ="http://www.myvideo.de/dynamic/get_player_video_xml.php";
		$xml.="?autorun=yes&ds=$ds&ID=$video_id&_countlimit=$_countlimit";
		$xml.="&flash_playertype=$flash_playertype&domain=www.myvideo.de";
		
		$xmlc="";while (!$xmlc) $xmlc=curl_proxy($xml,"",$proxy);
		
		$dec_data   	= simplexml_load_string($this->myvideodecrypt($xmlc, $video_id));
		
		$attr=$dec_data->playlist->videos->video->{"@attributes"};
		
		$n=0;				      
  					
		$rtmp  			=urldecode($attr->connectionurl);
		$rtmp  			=substr(_explode("?",$rtmp,0),0,-1);
		$subpath		=_explode("/",$rtmp,3);
			
		$source			=urldecode($attr->source);
					
		$token 			=$attr->token;//Berlinale
		$token_duration		=18000;
		
		if (!strpos($rtmp,"myvideo2flash"))
		     // se genera un nuevo token  
			  
			   $finaltoken="?token=".$this->generateUrlToken("/$subpath/".$source, $token, $token_duration);	
			
		else $rtmp=str_replace("tmpe://","tmpt://",$rtmp);
				
		// método rtmp: -r -y
		
		$finallink		=$rtmp.$finaltoken."mp4:".$source;
		
		$title=noacentos(normaliza(urldecode($attr->title)));
	
		$totallinks[0]["img"]  =urldecode($attr->preview);
		
		epiinfo($window,$title);
		
		$totallinks[$n]["tipo"]="_";		
		$totallinks[$n]["link"]=$finallink;	
		$totallinks[$n]["mode"]="rt";			
				
		return $totallinks;
		}
	}	
?>
