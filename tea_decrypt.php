<?
function charsToLongs($_arg1)
	          {
            $_local2 = array();
            $_local3 = 0;
            while ($_local3 < ceil((sizeof($_arg1) / 4))) 
	              {
                $_local2[$_local3] = ((($_arg1[($_local3 * 4)] + ($_arg1[(($_local3 * 4) + 1)] << 8)) + ($_arg1[(($_local3 * 4) + 2)] << 16)) + ($_arg1[(($_local3 * 4) + 3)] << 24));

                $_local3++;
                }
            return ($_local2);
            }

function longsToChars($_arg1)
	          {     
            $_local3 = 0;
            while ($_local3 < sizeof($_arg1)) 
	              {
                $_local2.=chr($_arg1[$_local3] & 0xFF).chr(($_arg1[$_local3] >> 8) & 0xFF).chr(($_arg1[$_local3] >> 16) & 0xFF).chr(($_arg1[$_local3] >> 24) & 0xFF);
                $_local3++;
                }
            return ($_local2);
        }
		
function _rshift($integer, $n){
        // convert to 32 bits
        if (0xffffffff < $integer || -0xffffffff > $integer){
            $integer = fmod($integer, 0xffffffff + 1);
        }

        // convert to unsigned integer
        if (0x7fffffff < $integer){
            $integer -= 0xffffffff + 1.0;
        }elseif (-0x80000000 > $integer){
            $integer += 0xffffffff + 1.0;
        }

        // do right shift
            if (0 > $integer){
                $integer &= 0x7fffffff;         // remove sign bit before shift
                $integer >>= $n;                    // right shift
                $integer |= 1 << (31 - $n); // set shifted sign bit
            }else{
                $integer >>= $n;                    // use normal right shift
            }
        return $integer;
    }
	    
function tea_decrypt($str,$key)
	{
	$_local5 = sizeof($str);
	$_local6 = $str[($_local5 - 1)];
	$_local7 = $str[0];
	$_local8 = 2654435769;
	$_local11 = (int)((6 + (52 / $_local5)));
	$_local12 = ($_local11 * $_local8);
	
	while ($_local12 != 0) 
		{
		$_local10 = (($_local12 >> 2) & 3);
		$_local14 = ($_local5 - 1);
		while ($_local14 >= 0) 
			{
			    $_local6 = $str[((($_local14 > 0)) ? ($_local14 - 1) : ($_local5 - 1))];
			    
			    $_local91 =   (_rshift($_local6,5)^($_local7 << 2))  +  (_rshift($_local7,3)^($_local6 << 4));
			    $_local92 =   ($_local12^$_local7) + ($key[($_local14 & 3^$_local10)]^$_local6);
			    $_local9  =   $_local91 ^ $_local92;
			    		    
			    $_local7 = ($str[$_local14] = ($str[$_local14] - $_local9));
			    		    
			    $_local14--;
			}
		$_local12 = ($_local12 - $_local8);
		}
		
	return longsToChars($str);
	}

function transform_r2($r2)
	{
	$_local2 = "";
	$_local3 = 0;
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMabcdefghijklmnopqrstuvwxyzabcdefghijklm";
	
	while ($_local3 < strlen($r2)) 
	    {
	    $_local5 = $r2[$_local3];
	    if (strpos($chars,$_local5))
		    {
		     $_local5 = $chars[strpos($chars,$_local5) + 13];
		    }
	    $_local2.= $_local5;
	    $_local3++;
	   }
	return $_local2;	
	}

/* 
sample for 3dbuff

this._connection.call("rr", null, $decrypt);
*/

$r2    = "5n251623p7q688po5s9o89p8249so8oqs399r7os8982srs70852662qpq14r2409r6q58r8"; 
                  
$r2 =charsToLongs(array_values(unpack("C*",pack("H*",transform_r2($r2)))));
$key=charsToLongs(array_values(unpack("C*",substr('92J3ax!077M6%.EZckS776^J$i8=}I',0,16))));

// 55a7b6f3-ba33-4596-8965-b46d2247ea51
$decrypt=tea_decrypt($r2,$key);
?>
