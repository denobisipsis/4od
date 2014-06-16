/*
extrae & curl_proxy are macros
*/

// $contenth is the html from original url

  $downurl=extrae($contenth,'"downloadUrl":','"','"');
	
	$wid	    =extrae($downurl,'/p','/','/');
	$uiconf_id="11673211";
	$entry_id =extrae($downurl,'/entry_id','/','/');

// build the swf url

	$swf    ="http://cdnapi.kaltura.com/index.php/kwidget/wid/_$wid/uiconf_id/$uiconf_id/entry_id/$entry_id";	

// get the swf and extract relevant info

	$swf	  =gzuncompress(substr(file_get_contents($swf),8));
	
	$info   =urldecode("widgetId=".extrae($swf,'widgetId','=','?'));
	
	$partnerId	=extrae($info,'partnerId','=','&');
	$subpId		  =extrae($info,'subpId','=','&');
	$ts		      =extrae($info,'ts','=','&');
	$ks		      =extrae($info,'ks','=','&');
	$uid		    =extrae($info,'uid','=','&');
	$cdnHost	  =extrae($info,'cdnHost','=','&');
	$referrer   =base64_encode($urlorig);

	$kalsig		  =explode("|",base64_decode($ks))[0];

// with this info we make the post

	$post ="3:filter:objectType=KalturaCuePointFilter&2:contextDataParams:referrer=$urlorig";
	$post.="&3:action=list&1:version=-1&1:service=baseentry&3:filter:entryIdEqual=$entry_id";
	$post.="&2:contextDataParams:streamerType=http&1:entryId=$entry_id&2:entryId=$entry_id";
	$post.="&2:action=getContextData";
	$post.="&ks=$ks";
	$post.="&1:action=get&3:service=cuepoint_cuepoint";
	$post.="&ignoreNull=1&2:contextDataParams:objectType=KalturaEntryContextDataParams";
	$post.="&2:service=baseentry&clientTag=kdp:v3.8.2.sdk46";
	
	$call ="http://cdnapi.kaltura.com/api_v3/index.php?service=multirequest&action=null&kalsig=$kalsig";
	
	$medialast=simplexml_load_string(curl_proxy($call,"","",$headers,0,$post));

// we obtain a xml from which to extract more info
	
	$downloadurl  =$medialast->result->item[0]->downloadUrl;
	$format       =$medialast->result->item[1]->streamerType;
	$mediaProtocol=$medialast->result->item[1]->mediaProtocol;		

// we look for the media

	foreach ($medialast->result->item[1]->flavorAssets->item as $link)
		{
		$flavorId=$link->id;
		
		$call ="http://cdnapi.kaltura.com/p/$partnerId/sp/$subpId/playManifest/";
		$call.="entryId/$entry_id/flavorId/$flavorId/format/$format/protocol/$mediaProtocol/";
		$call.="cdnHost/$cdnHost/ks/$ks/uiConfId/$uiconf_id/a/a.f4m?referrer=$referrer";
		
		$result =simplexml_load_string(curl_proxy($call,"","",$headers));

//  $mp4 is the final link

		$mp4 		  =$result->media->attributes()->url;
		$bitrate 	=$result->media->attributes()->bitrate;
		$width 		=$result->media->attributes()->width;
		$height 	=$result->media->attributes()->height;
		}
