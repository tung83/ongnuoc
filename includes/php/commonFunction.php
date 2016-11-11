<?php
//25-05-2013
function imageRex($width,$height,$tmpName,$filePath)
{
	$result = move_uploaded_file($tmpName, $filePath);
	$tail=getFileExtend($filePath);
	if($tail=="jpg"||$tail=="jpeg")
		$orig_image = imagecreatefromjpeg($filePath);  
	else if($tail=="png")
		$orig_image= imagecreatefrompng($filePath);
	else if($orig_image=="gif")
		$orig_image=imagecreatefromgif($filePath);
	
	$image_info = getimagesize($filePath); 
	$width_orig  = $image_info[0]; // current width as found in image file
	$height_orig = $image_info[1]; // current height as found in image file
	//$width = 1024; // new image width
	//$height = 768; // new image height
	//image set width=0
	if($width==0)
	{
		$perHeight=($height/$height_orig);
		$width=$width_orig*$perHeight;	
	}
	//image set width=0
	//image set height=0
	if($height==0)
	{
		$perWidth=($width/$width_orig);
		$height=$height_orig*$perWidth;	
	}
	//image set height=0
	
	$destination_image = imagecreatetruecolor($width, $height);
	if($tail=="png")
	{
		// integer representation of the color black (rgb: 0,0,0)
        $background = imagecolorallocate($destination_image, 0, 0, 0);
        // removing the black from the placeholder
        imagecolortransparent($destination_image, $background);
        // turning off alpha blending (to ensure alpha channel information 
        // is preserved, rather than removed (blending with the rest of the 
        // image in the form of black))
        imagealphablending($destination_image, false);
        // turning on alpha channel information saving (to ensure the full range 
        // of transparency is preserved)
        imagesavealpha($destination_image, true);
	}
	else if($orig_image=="gif")
	{
		$background = imagecolorallocate($destination_image, 0, 0, 0);
        // removing the black from the placeholder
        imagecolortransparent($destination_image, $background);
	}
	
	imagecopyresampled($destination_image, $orig_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	// This will just copy the new image over the original at the same filePath.
	//imagejpeg($destination_image, $filePath, 100);
	
	/*if($tail=="jpg"||$tail=="jpeg")
		imagejpeg($destination_image, $filePath, 100);
	else imagepng($destination_image, $filePath, 0);*/
	if($tail=="jpg"||$tail=="jpeg")
		imagejpeg($destination_image, $filePath, 100);
	else if($tail=="png")
	{
		imagepng($destination_image, $filePath, 0);
	}
	else if($orig_image=="gif")
	{
		imagegif($destination_image, $filePath, 100);
	}
}
function getFile()
{
	$file=$_SERVER['PHP_SELF'];
	return basename($file);
}
function strcut($str, $len, $charset='UTF-8')
{
	$str = html_entity_decode($str, ENT_QUOTES, $charset);
	if(mb_strlen($str, $charset)> $len){
		$arr = explode(' ', $str);
		$str = mb_substr($str, 0, $len, $charset);
		$arrRes = explode(' ', $str);
		$last = $arr[count($arrRes)-1];
		unset($arr);
		if(strcasecmp($arrRes[count($arrRes)-1], $last))
			{
				unset($arrRes[count($arrRes)-1]);
			}
		return implode(' ', $arrRes)."...";
		}
		return $str;
}
function cutOut($str,$len)
{
	$curLen=strlen($str);
	$str=substr($str,0,$len);
	if($curLen>$len) $str.="...";
	return $str;
}
//kiem tra online
function OnlineTrongNgay()
{
//session_start();
		
	 $sql="select * from songuoi";
	 $Result=mysql_query($sql)or die(mysql_error());
	 $aRow=mysql_fetch_array($Result);
	 $day=date('d');
	 
	 if( $day != $aRow['DATE'])
	 	{
			$so=1;
			$sql="update songuoi set NUMBER=$so,DATE=$day";
			mysql_query($sql) or die(mysql_error());
			$_SESSION["SoNguoiOnline"]="11";
		}
	else
		{
			if (!isset($_SESSION["SoNguoiOnline"]))
				{
					$so1=$aRow['NUMBER'] + 1;
					$sql="update songuoi set NUMBER=$so1,DATE=$day";
					mysql_query($sql) or die(mysql_error());
					$_SESSION["SoNguoiOnline"]="11";
				}
		}
	$tab=mysql_query("select NUMBER from songuoi");
	$row=mysql_fetch_object($tab);
	return  $row->NUMBER ;
}		
function DangOnline()
{
	//session_start();        // Khoi dong session     
$s_id = session_id();    // Bien s_id         
$time = time();            // Lay thoi gian hien tai 
$time_secs = 600;        // Thoi gian tinh bang seconds de delete & insert cai $s_id    moi, test tren localhost thi cho no bang 3 seconds de nhanh thay ket qua, chạy trên host thì để 900 = 15 phút là vừa 
$time_out = $time - $time_secs;    // Lay thoi gian hien tai     

@mysql_query("DELETE FROM stats WHERE s_time < '$time_out'");                // Delete tat ca nhung rows trong khoang thoi gian qui dinh san 
@mysql_query("DELETE FROM stats WHERE s_id = '$s_id'");                        // Delete cai $s_id cua chinh thang nay 
@mysql_query("INSERT INTO stats (s_id, s_time) VALUES ('$s_id', '$time')");    // Delete no xong lai insert chinh no 
$user_online = @mysql_num_rows(@mysql_query("SELECT id FROM stats"));        // Dem so dong trong table stats, chinh la so nguoi dang online 
// Them 1 cai, xem page nay da duoc mo bao nhieu lan: 
//list($page_visited) = @mysql_fetch_array(@mysql_query("SELECT MAX(id) FROM stats")); 
// Xong rui, cho no ra thui 
return "".$user_online.""; 
//echo "Trang nay duoc mo: <b>".$page_visited."</b> lan"; 
}

function counter($readorwrite="")
{
//session_start();
//session_is_registered("hit");
if($readorwrite="write")
{
$hits_session = $_SESSION["hit"];
if($hits_session == null) {
	$_SESSION["hit"] = "deiafienvcoewkcdo22";
	$bool = mysql_query("UPDATE tbl_counter SET hits=hits+1");
	$bool2 = mysql_query("UPDATE tbl_counter SET realhits=realhits+1");
}
$r2=mysql_query("SELECT hits FROM tbl_counter");
$hits=0;
if($hit=mysql_fetch_row($r2)) {
	$hits=$hit[0];
}
}
return $hits;
}
function ultimatePaging($lim,$count,$url)
{
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$pages=($count%$lim==0)?$count/$lim:floor($count/$lim)+1;
	$str="<ul class=\"pagerClass\">";
	if($count!=0)
	{	
		if($page>1)
		{
			$str.="<li><a class=\"offPage\" href=\"$url"."page=".($page-1)."\"> << </a></li>";
		}
		else $str.='<li><a> << </a></li>';
	for($i=1;$i<=$pages;$i++)
	{
		
		if($pages<12)
		{
			if($i==$page) $str.="<li><a class=\"onPage\">".$i."</a></li>";
			else $str.="<li><a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a></li>";
		}
		else
		{
			if($i==$page) $str.="<li><a class=\"onPage\">".$i."</a></li>";
			else 
			{
				if($i==1&&$page!=1) $str.="<li><a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a></li>";
				if($i>($page-3) && $i<($page+3) && $i>1 && $i<($pages))
					$str.="<li><a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a></li>";					
				if($i==$pages) $str.="<li><a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a></li>";
			}
		}
	}
		if($page<$pages)
			$str.="<li><a class=\"offPage\" href=\"$url"."page=".($page+1)."\"> >> </a></li>";
		else $str.="<li><a> >> </a></li>";
	}	
	$str.="</ul>";	
	if($count<=$lim) $str="";
	return $str;
}
function playFlash($url,$width,$height,$loop)
{
	$str='<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="max-width:'.$width.';max-height:'.$height.'" 
		CODEBASE="http://active.macromedia.com/flash5/cabs/swflash.cab#version=5,0,0,0">		
		<PARAM NAME="MOVIE" VALUE="'.$url.'">
		<PARAM NAME="PLAY" VALUE="true">
		<PARAM NAME="QUALITY" VALUE="best">
		<PARAM NAME="LOOP" VALUE="true">
		<EMBED SRC="'.$url.'" style="max-width:'.$width.';max-height:'.$height.'"  LOOP="'.$loop.'" QUALITY="best" 
		PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"> 
		</EMBED>
		</OBJECT>';	
	return $str;
}
function mypager($lim,$count,$url)
{
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$pages=($count%$lim==0)?$count/$lim:floor($count/$lim)+1;
	$str='<div id="pager">';
	if($count!=0)
	{	
		if($page>1)
		{
			$str.="<a href=\"/$url"."page=".($page-1)."\"> &laquo; </a>";
		}
	
		
		if($pages<12)
		{
			for($i=1;$i<=$pages;$i++)
			{
				if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
				else $str.="<a href=\"/$url"."page=".$i."\">$i</a>";
			}
		}
		else
		{
			for($i=1;$i<=$pages;$i++)
			{
				if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
				else 
				{
					if($i==1&&$page!=1) $str.="<a href=\"$url"."page=".$i."\">$i</a>";
					if($i>($page-3) && $i<($page+3) && $i>1 && $i<($pages))
						$str.="<a href=\"/$url"."page=".$i."\">$i</a>";					
					if($i==$pages) $str.="<a href=\"/$url"."page=".$i."\">$i</a>";
				}
			}
		}	
		if($page<$pages)
			$str.="<a href=\"/$url"."page=".($page+1)."\"> &raquo; </a>";
	}	
	$str.="</div>";	
	if($count<=$lim) $str="";
	return $str;
}

function ad_paging($lim,$count,$url,$page)
{
	$pages=($count%$lim==0)?$count/$lim:floor($count/$lim)+1;
	$str='<nav>
  			<ul class="pagination" style="margin:0">';
	if($count!=0)
	{	
		if($page>1)
		{
			$str.='<li><a href="'.$url.'page='.($page-1).'" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span></a></li>';
		}
	for($i=1;$i<=$pages;$i++)
	{
		
		if($pages<12)
		{
			if($i==$page) $str.='<li class="active"><a href="#">'.$i.'</a></li>';
			else $str.="<li><a href=\"$url"."page=".$i."\">$i</a></li>";
		}
		else
		{
			if($i==$page) $str.='<li class="active"><a href="#">'.$i.'</a></li>';
			else 
			{
				if($i==1&&$page!=1) $str.="<li><a href=\"$url"."page=".$i."\">$i</a></li>";
				if($i>($page-3) && $i<($page+3) && $i>1 && $i<($pages))
					$str.="<li><a href=\"$url"."page=".$i."\">$i</a></li>";					
				if($i==$pages) $str.="<li><a href=\"$url"."page=".$i."\">$i</a></li>";
			}
		}
	}
		if($page<$pages)
			$str.="<li><a href=\"$url"."page=".($page+1)."\" aria-label=\"Next\">
						<span aria-hidden=\"true\">&raquo;</span></a></li>";
	}	
	$str.="</nav>";	
	if($count<=$lim) $str="";
	return $str;	
}
function newPaging($lim,$count,$url)
{
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$pages=($count%$lim==0)?$count/$lim:floor($count/$lim)+1;
	$str='<div id="pager">';
	if($count!=0)
	{	
		if($page>1)
		{
			$str.="<a href=\"/$url"."page=".($page-1)."\"> &laquo; </a>";
		}
	for($i=1;$i<=$pages;$i++)
	{
		
		if($pages<12)
		{
			if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
			else $str.="<a href=\"/$url"."page=".$i."\">$i</a>";
		}
		else
		{
			if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
			else 
			{
				if($i==1&&$page!=1) $str.="<a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a>";
				if($i>($page-3) && $i<($page+3) && $i>1 && $i<($pages))
					$str.="<a href=\"/$url"."page=".$i."\">$i</a>";					
				if($i==$pages) $str.="<a href=\"/$url"."page=".$i."\">$i</a>";
			}
		}
	}
		if($page<$pages)
			$str.="<a href=\"/$url"."page=".($page+1)."\"> &raquo; </a>";
	}	
	$str.="</div>";	
	if($count<=$lim) $str="";
	return $str;	
}
function adminPaging($lim,$count,$url)
{
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$pages=($count%$lim==0)?$count/$lim:floor($count/$lim)+1;
	$str="<div id=\"pager\">";
	if($count!=0)
	{	
		if($page>1)
		{
			$str.="<a href=\"$url"."page=".($page-1)."\"> << </a>";
		}
	for($i=1;$i<=$pages;$i++)
	{
		
		if($pages<12)
		{
			if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
			else $str.="<a href=\"$url"."page=".$i."\">$i</a>";
		}
		else
		{
			if($i==$page) $str.="<a class=\"onPage\">".$i."</a>";
			else 
			{
				if($i==1&&$page!=1) $str.="<a class=\"offPage\" href=\"$url"."page=".$i."\">$i</a>";
				if($i>($page-3) && $i<($page+3) && $i>1 && $i<($pages))
					$str.="<a href=\"$url"."page=".$i."\">$i</a>";					
				if($i==$pages) $str.="<a href=\"$url"."page=".$i."\">$i</a>";
			}
		}
	}
		if($page<$pages)
			$str.="<a href=\"$url"."page=".($page+1)."\"> >> </a>";
	}	
	$str.="</div>";	
	if($count<=$lim) $str="";
	return $str;		
}
function checkImg($fileName)
{
	if($fileName=="")
	{
		return false;
	}
	else
	{
		$reg="png,jpeg,jpg,gif";
		$tail=getFileExtend($fileName);
		if(strstr($reg,$tail)!=NULL) return true;
		else return false;
	}
}
function getFileExtend($file)
{
	$arr=explode('.',$file);
	return strtolower($arr[(sizeof($arr)-1)]);
}
function getDomain()
{
	$url = $_SERVER['PHP_SELF'];
	$parse = parse_url($url);
	return $parse['host'];
}
function vn_str_filter($str){

   $unicode = array(

	   'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

	   'd'=>'đ',

	   'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

	   'i'=>'í|ì|ỉ|ĩ|ị',

	   'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

	   'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

	   'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

	   'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

	   'D'=>'Đ',

	   'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

	   'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

	   'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

	   'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

	   'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

   );

  foreach($unicode as $nonUnicode=>$uni){

	   $str = preg_replace("/($uni)/i", $nonUnicode, $str);

  }

   return $str;

}
function slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	
	$str=vn_str_filter($str);
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = array(
	'delimiter' => '-',
	'limit' => null,
	'lowercase' => true,
	'replacements' => array(),
	'transliterate' => false,
	);
	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
	// Latin
	'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
	'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
	'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
	'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
	'ß' => 'ss',
	'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
	'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
	'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
	'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
	'ÿ' => 'y',
	 
	// Latin symbols
	'©' => '(c)',
	 
	// Greek
	'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
	'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
	'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
	'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
	'Ϋ' => 'Y',
	'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
	'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
	'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
	'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
	'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
	 
	// Turkish
	'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
	'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
	 
	// Russian
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
	'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
	'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
	'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
	'Я' => 'Ya',
	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
	'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
	'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
	'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
	'я' => 'ya',
	 
	// Ukrainian
	'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
	'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
	 
	// Czech
	'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
	'Ž' => 'Z',
	'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
	'ž' => 'z',
	 
	// Polish
	'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
	'Ż' => 'Z',
	'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
	'ż' => 'z',
	 
	// Latvian
	'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
	'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
	'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
	'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
	$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function checkmobile()
{

	if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) return true;

	if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) return true;

	if(isset($_SERVER["HTTP_USER_AGENT"]))
	{
		// Quick Array to kill out matches in the user agent
		// that might cause false positives	
		$badmatches = array("OfficeLiveConnector","MSIE\ 8\.0","OptimizedIE8","MSN\ Optimized","Creative\ AutoUpdate","Swapper");	
		foreach($badmatches as $badstring)
		{
			if(preg_match("/".$badstring."/i",$_SERVER["HTTP_USER_AGENT"])) return false;
		}

		// Now we'll go for positive matches
		$uamatches = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto","webos");

		foreach($uamatches as $uastring)
		{
			if(preg_match("/".$uastring."/i",$_SERVER["HTTP_USER_AGENT"])) return true;
		}

	}
	return false;
}
function del($str)
{
	return preg_replace('/\s+/', '', $str);	
}
function tb_count($tab)
{
	/*if(mysql_num_rows($tab)>0) return true;
	else return false;*/
	return mysql_num_rows($tab);
}
?>