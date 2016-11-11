<?php
include "includes/php/config.php";
$view=isset($_GET["view"])?strtolower($_GET['view']):'trang-chu';
define('limit_record',6);
define('page_size',10);
function pageHeader($view){
    switch($view){
        case 'gioi-thieu':
            if(isset($_GET['id'])){
                $tab=mysql_query("select title,meta_keyword,meta_description from about where id=".$_GET['id']);
                $row=mysql_fetch_object($tab);
                $pageTitle = $row->title;
                $pageDescription = $row->meta_description;
                $pageKeyword = $row->meta_keyword;
                break;
            } else {
            }
        case 'san-pham':
            if(isset($_GET['id'])){
                $tab=mysql_query("select title,meta_keyword,meta_description from product where id=".$_GET['id']);
                $row=mysql_fetch_object($tab);
                $pageTitle = $row->title;
                $pageDescription = $row->meta_description;
                $pageKeyword = $row->meta_keyword;
                break;
            } else if(isset($_GET['pId'])){
                $tab=mysql_query("select title,meta_keyword,meta_description from cata_sub where id=".$_GET['pId']);
                $row=mysql_fetch_object($tab);
                $pageTitle = $row->title;
                $pageDescription = $row->meta_description;
                $pageKeyword = $row->meta_keyword;
                break;
            } else {
            }
        case 'tin-tuc':
            if(isset($_GET['id'])){
                $tab=mysql_query("select title,meta_keyword,meta_description from news where id=".$_GET['id']);
                $row=mysql_fetch_object($tab);
                $pageTitle = $row->title;
                $pageDescription = $row->meta_description;
                $pageKeyword = $row->meta_keyword;
                break;
            } else {
            }
        case 'bang-gia':
            if(isset($_GET['id'])){
                $tab=mysql_query("select title,meta_keyword,meta_description from price where id=".$_GET['id']);
                $row=mysql_fetch_object($tab);
                $pageTitle = $row->title;
                $pageDescription = $row->meta_description;
                $pageKeyword = $row->meta_keyword;
                break;
            } else {
            }
        default:
        {
            $sql="select title,meta_keyword,meta_description from menu where view like '%$view%'";
            $tab=mysql_query($sql);
            $row=mysql_fetch_object($tab);
            $pageTitle = '.:CÔNG TY TNHH THƯƠNG MẠI DỊCH VỤ VÀ TƯ VẤN V.T.L | '.$row->title.':.';
            $pageDescription = $row->meta_description;
            $pageKeyword = $row->meta_keyword;
            break;
        }
    }
    $str='
    <title>'.$pageTitle.'</title>
    <meta name="description" content="'.$pageDescription.'">
    <meta name="keyword" content="'.$pageDescription.'">
    ';
    return $str;
}
function menu()
{
    $view=isset($_GET["view"])?$_GET['view']:'trang-chu';
    $sql="select * from menu where active=1 order by ind asc,id desc";
    $str='<table class="menu"><tr>';
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        if($row->view==$view)
        {
            $cls='active';
        }
        else $cls='';
        $str.='
        <td>
            <div class="'.$cls.'">
                <a href="'.myWeb.$row->view.'.html">'.$row->title.'</a>                
            </div>
        </td>';
    }
    $str.='</tr></table>';
    return $str;
}
function foot_menu(){
    $view=isset($_GET["view"])?$_GET['view']:'trang-chu';
    $sql="select * from menu where active=1 order by ind asc,id desc";
    $str='<ul class="foot_menu">';
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        if($row->view==$view)
        {
            $cls='active';
        }
        else $cls='';
        $str.='
        <li class="'.$cls.'">
            <a href="'.myWeb.$row->view.'.html">'.$row->title.'</a>                
        </li>';
    }
    $str.='</ul>';
    return $str;
}
function slide()
{
    $tab=mysql_query("select * from slide where active=1 order by ind asc,id desc");
    $tmp='';
    $sld='';
    $k=1;
    while($row=mysql_fetch_object($tab))
    {
        $sld.='<li>';
        if(trim($row->lnk)!='')
            $sld.='<a href="'.$row->lnk.'">';
        $sld.='<img src="'.webPath.$row->img.'" alt="'.$row->title.'" title="'.$row->title.'" id=""/>';
        if(trim($row->lnk)!='')
            $sld.='</a>';
        $sld.='</li>';
        $tmp.='<a href="#" title="'.$row->title.'"><span>'.$k.'</span></a>';
        $k++;
    }
    $str.='
    <!-- Start WOWSlider BODY section -->
    <div id="wowslider-container1">
    <div class="ws_images"><ul>
    		'.$sld.'
    	</ul></div>
    	<div class="ws_bullets"><div>
    		'.$tmp.'
    	</div></div><div class="ws_script" style="position:absolute;left:-99%"></div>
    <div class="ws_shadow"></div>
    </div>	
    <script type="text/javascript" src="'.myWeb.'engine/wowslider.js"></script>
    <script type="text/javascript" src="'.myWeb.'engine/script.js"></script>
    <!-- End WOWSlider BODY section -->';
    return $str;
}

function home()
{
    $str=slide().'';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Sản Phẩm
        </span>
    </div>
    ';
    $str.=product_index();
    
    //$str.=project_index();
    return $str;
}



function qtext($id)
{
    $tab=mysql_query("select content from qtext where id=$id");
    $row=mysql_fetch_object($tab);
    return $row->content;
}

function serv()
{
    if(isset($_GET['id'])) return serv_one();
    else if(isset($_GET['pId'])) return serv_cate();
    else return serv_all();
}
function serv_all()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Dịch vụ</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Dịch vụ
        </span>
    </div>
    ';
    $cate=mysql_query("select id,title from serv_cate where active=1");
    while($cate_item=mysql_fetch_object($cate)){
        $sql="select id,title,dates,sum,img from serv where active=1 and pId=$cate_item->id order by id desc limit 2";
        $tab=mysql_query($sql);
        $count=mysql_num_rows($tab);
        if($count>0){
            $str.='
            <h2 class="cate-title"><span>'.$cate_item->title.'</span>
            <a href="'.myWeb.'dich-vu-sua-chua/'.slug($cate_item->title).'-'.$cate_item->id.'.html">
                <i class="fa fa-bell-o"> Xem tất cả</a></i></h2>';
            $str.='
            <ul class="news-list">
            ';
            
            while($row=mysql_fetch_object($tab))
            {
                $str.='
                <li class="clearfix">
                    <a href="'.myWeb.'dich-vu-sua-chua/'.slug($row->title).'-i'.$row->id.'.html">
                    <img src="'.webPath.$row->img.'"/>
                    <div>
                        <h2>'.$row->title.'</h2>
                        <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                        <span>'.strcut($row->sum,120).'</span>
                    </div>
                    </a>
                </li>
                ';
            }
            $str.='
            </ul>
            ';       
        }
    }    
    return $str;
}
function serv_cate(){
    $pId=intval($_GET['pId']);
    $tb=mysql_query("select id,title from serv_cate where id=$pId");
    $r=mysql_fetch_object($tb);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'dich-vu-sua-chua.html">Dịch vụ</a>
     <a>'.$r->title.'</a>   
    </div>
    ';
    $str.='
    <div class="head-title clearfix">
        <span>
            <b class="fa fa-calendar"></b> Dịch vụ
        </span>
    </div>
    ';
    $str.='
    <ul class="news-list">
    ';
    $sql="select id,title,dates,sum,img from serv where active=1 and pId=$pId order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $start=($page-1)*limit_record;
    $sql.=" limit $start,".limit_record;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'dich-vu-sua-chua/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = limit_record;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=dich-vu-sua-chua";
    $pg->paginationUrl = myWeb."index.php?view=dich-vu-sua-chua&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function serv_one()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select title,content,id,pId from serv where id=$id");
    $row=mysql_fetch_object($tab);
    $tb=mysql_query("select id,title from serv_cate where id=$row->pId");
    $r=mysql_fetch_object($tb);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'dich-vu-sua-chua.html">Dịch vụ</a>
     <a href="'.myWeb.'dich-vu-sua-chua/'.slug($r->title).'-'.$r->id.'.html">'.$r->title.'</a>     
     <a>'.$row->title.'</a>
    </div>
    ';
    
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Dịch vụ
        </span>
    </div>
    ';
    $str.='
    <article class="article">
        <h1 class="article-heading">'.$row->title.'</h1>
        <p>'.$row->content.'</p>
    </article>
    ';
    $str.='
    <h3 class="other-article">Bài viết khác</h3>
    <ul class="news-list">
    ';
    $tab=mysql_query("select id,title,dates,sum,img from serv where active=1 and id<>$id order by id desc limit 3");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'dich-vu-sua-chua/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    return $str;
}

function techs()
{
    if(isset($_GET['id'])) return one_techs();
    else if(isset($_GET['pId'])) return techs_kind();
    else return all_techs();
}
function all_techs()
{
    $str='
    <div class="row">
        <div class="col-lg-4" style="">
            '.techs_menu().'
            '.facebook_plugin().'
        </div>
        <div class="col-lg-8" style="">
            <div class="page-header">
                <h3>CÔNG NGHỆ</h3>
            </div>';
    $tab=mysql_query("select  * from techs where active=1 order by id desc limit 20");
    while($row=mysql_fetch_object($tab))
    {
        $str.=one_list_techs($row->id);
    }
    $str.='
        </div>
    </div>
    ';
    return $str;
}
function techs_kind()
{
    $pId=intval($_GET['pId']);
    $tb=mysql_query("select title from techs_kind where id=$pId");
    $r=mysql_fetch_object($tb);
   $str='
    <div class="row">
        <div class="col-lg-4" style="">
            '.techs_menu().'
        </div>
        <div class="col-lg-8" style="">
            <div class="page-header">
                <h3>'.$r->title.'</h3>
            </div>';
    $tab=mysql_query("select  id from techs where active=1 and pId=$pId order by id desc limit 20");
    while($row=mysql_fetch_object($tab))
    {
        $str.=one_list_techs($row->id);
    }
    $str.='
        </div>
    </div>
    ';
    return $str; 
}
function one_techs()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select * from techs where id=$id");
    $row=mysql_fetch_object($tab);
   $str='
    <div class="row">
        <div class="col-lg-4" style="">
            '.techs_menu().'
        </div>
        <div class="col-lg-8" style="">
            <div class="page-header">
                <h3>'.$row->title.'</h3>
            </div>
            <article class="article">
            '.$row->content.'
            </article>
    ';
    
    $str.='
        </div>
    </div>
    ';
    return $str;
}
function techs_menu()
{
    $tab=mysql_query("select * from techs_kind where active=1 order by id desc");
    $str='
            <nav class="left_menu">
                <ul class="nav">';
    if(isset($_GET['pId'])) $pId=intval($_GET['pId']);
    if(isset($_GET['id']))
    {
        $tb=mysql_query("select pId from techs where id=".intval($_GET['id']));
        $r=mysql_fetch_object($tb);
        $pId=$r->pId;
    }
    while($row=mysql_fetch_object($tab))
    {
        if($row->id==$pId) $cls='active';
        else $cls='';
        $str.='<li><a class="'.$cls.'" href="index.php?view=cong-nghe&pId='.$row->id.'">'.$row->title.'</a></li>';
    }
    $str.='
            	</ul>
            </nav>       
    ';
    
    return $str;
}
function one_list_techs($id)
{
    $tab=mysql_query("select * from techs where id=$id");
    $row=mysql_fetch_object($tab);
    $str.='
        <div class="row serv_items">
        <a href="index.php?view=cong-nghe&id='.$id.'">
            <div class="col-xs-4">
                <img src="'.webPath.$row->img.'" class="img-responsive"/>
            </div>
            <div class="col-xs-8">
                <h2>'.$row->title.'</h2>
                <span>'.nl2br($row->sum).'</span>
            </div>
        </a>
        </div>
        ';
    return $str;
}
function contact()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Liên hệ</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Liên Hệ
        </span>
    </div>
    ';
    $str.='
    <div class="contact">
    <div class="col-xs-12" style="margin:10px 0px">
        <em style="font-weight:bold">
        Cảm ơn Quý khách đã truy cập vào website. Mọi thông tin chi tiết xin vui lòng liên hệ:
        </em>
    </div>
	<div class="col-xs-12">
		'.qtext(3).'
	</div>
	<div class="col-xs-12">
		'.cont().'
	</div>';
    /*$str.='
    <div class="col-xs-12 gmap">'.gmaps().'</div>
    ';*/
    $str.='
    </div>';
    
    return $str;
}
function cont()
{
    if(isset($_POST["btn_submit"]))
	  {
			$name=str_replace("'","&rsquo;",$_POST["name"]);
			$comp=str_replace("'","&rsquo;",$_POST["comp"]);
			$adds=str_replace("'","&rsquo;",$_POST["adds"]);
			$phone=str_replace("'","&rsquo;",$_POST["phone"]);
			$fax=str_replace("'","&rsquo;",$_POST["fax"]);
			$email=str_replace("'","&rsquo;",$_POST["email"]);
			$content=str_replace("'","&rsquo;",$_POST["content"]);
			$dates=date("Y-m-d H:i:s");
			$sInsert="insert into contact(name,company,adds,phone,fax,email,content,dates) values(";
			$sInsert.="'$name','$comp','$adds','$phone','$fax','$email','$content','$dates')";
			$test=mysql_query($sInsert);
			if($test)
			{
				echo "<script>
							var msg='Cám ơn thông tin của bạn đã gởi đến chúng tôi! BQT sẽ phản hồi sớm nhất có thể!';
							var wrn='Thông Báo';
                            alert(msg);
							location.href='".$_SERVER['REQUEST_URI']."';
					</script>";
			}
			else echo $sInsert;
	  }
	$name="Liên hệ với chúng tôi";
	$iname="Họ tên";
	$icomp="Công ty";
	$iadds="Địa chỉ";
	$iphone="Điện thoại";
	$ifax="Fax";
	$iemail="Email";
	$icontent="Nội dung";
	$isend="Gởi";
	$idel="Xóa";
	$iem="Liên hệ với chúng tôi qua Form mẫu dưới đây. Chúng tôi sẽ phản hồi nhanh nhất có thể.";
	$ispan='(<span style="font-weight:bold;color:#f00">Chú ý</span> :Thông tin với <span style="color:#f00">*</span> là bắt buộc)';
					
	$str.='
    <div class="row" style="margin-top:10px">
        <div class="col-lg-12">
			<em>'.$iem.'</em><br/>				
			'.$ispan.'
        </div>
    </div>					
			';
	$str.='
	<form action="javascript:void(0)" method="post" name="frmContact">
				
		<table cellpadding="5" cellspacing="5" border="0" class="registerFrm">
			<tr>
				<td colspan="2">
				<em><strong>'.$or.'</strong></em>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				'.$utf.'
				</td>
			</tr>
			
			<tr>
				<td class="frmHead">'.$iname.' <em>*</em> :</td>
				<td>
					<input type="text" name="name" autocomplete="off"/>
				</td>
			</tr>
            <tr>
				<td class="frmHead">'.$icomp.' :</td>
				<td>
					<input type="text" name="comp" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td class="frmHead">'.$iadds.' <em>*</em> :</td>
				<td>
					<input type="text" name="adds" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td class="frmHead">'.$iphone.' <em>*</em> :</td>
				<td>
					<input type="text" name="phone" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td class="frmHead">'.$ifax.' :</td>
				<td>
					<input type="text" name="fax" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td class="frmHead">'.$iemail.' <em>*</em> :</td>
				<td>
					<input type="text" name="email" autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td class="frmHead">'.$icontent.' <em>*</em> :</td>
				<td>
					<textarea name="content"></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" value="'.$isend.'" name="btn_submit" id="btn_submit" onclick="return chkContact()"/>
					&ensp;
					<input type="reset" value="'.$idel.'"/>
				</td>
			</tr>
		</table>
	</form>
	';	
$str.='
    <!--div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Văn Phòng Đại Diện Tại Hồ Chí Minh
        </span>
    </div>
    ';
    $str.='
    <div class="maps">
        '.gmaps().'
    </div-->
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
            <span>
                <b class="fa fa-calendar"></b> TRỤ SỞ CÔNG TY
            </span>
        </div>
        ';
    $str.='<div class="maps">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.1442213109362!2d105.79431651496111!3d20.986855194583306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135acc7abd8443d%3A0x6e5bf6f4dec13dd3!2zxJDhu6ljIMSQ4bqhaSBPZmZpY2UgVG93ZXI!5e0!3m2!1sen!2s!4v1453687068536" width="800" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
    ';
    
    
	return $str; 
}

function gmaps()
{
    $str='
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1174760528056!2d106.64126311487045!3d10.80231366166905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175294f90fb880d%3A0x4e86b1f8f3297eca!2zMzIyIEPhu5luZyBIw7JhLCBwaMaw4budbmcgMTMsIFTDom4gQsOsbmgsIEjhu5MgQ2jDrSBNaW5oLCBWaWV0bmFt!5e0!3m2!1sen!2s!4v1453686996991" width="800" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
    ';
    return $str;
}


function gallery()
{
    $str='
    <div class="row">';
    $tab=mysql_query("select * from gallery where active=1 order by id desc");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <div class="col-md-3 gallery">
        <a href="'.webPath.$row->img.'" class="image-popup-vertical-fit">
            <div class="image">
                <img src="'.webPath.$row->img.'" class="img-responsive"/>
            </div>
            <h2>'.$row->title.'</h2>
        </a>
        </div>
        ';
    }
    $str.='    
    </div>
    ';
    $str.="
    <script type='text/javascript'>
      $(document).ready(function() {

        $('.image-popup-vertical-fit').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          mainClass: 'mfp-img-mobile',
          image: {
            verticalFit: true
          }
          
        });

        $('.image-popup-fit-width').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          image: {
            verticalFit: false
          }
        });

        $('.image-popup-no-margins').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          closeBtnInside: false,
          fixedContentPos: true,
          mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
          image: {
            verticalFit: true
          },
          zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
          }
        });

      });
    </script>
    ";
    return $str;
}
function facebook_plugin()
{
    $str='
    <div class="facebook_plugin hidden-xs hidden-sm">
    <div class="fb-page" data-href="https://www.facebook.com/dangvietspa/?fref=ts" data-show-posts="false" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
    </div>
    ';
    return $str;
}
function facebook_plugin_xs_sm()
{
    $str='
    <div class="facebook_plugin hidden-md hidden-lg">
    <div class="fb-page" data-href="https://www.facebook.com/dangvietspa/?fref=ts" data-show-posts="false" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
    </div>
    ';
    return $str;
}

function news()
{
    if(isset($_GET['id'])) return news_one();
    else if(isset($_GET['pId'])) return news_cate();
    else return news_all();
}
function news_all()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Tin tức</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Tin tức
        </span>
    </div>
    ';
    $cate=mysql_query("select id,title from news_cate where active=1");
    while($cate_item=mysql_fetch_object($cate)){
        $sql="select id,title,dates,sum,img from news where active=1 and pId=$cate_item->id order by id desc limit 2";
        $tab=mysql_query($sql);
        $count=mysql_num_rows($tab);
        if($count>0){
            $str.='
            <h2 class="cate-title"><span>'.$cate_item->title.'</span>
            <a href="'.myWeb.'tin-tuc/'.slug($cate_item->title).'-'.$cate_item->id.'.html">
                <i class="fa fa-bell-o"> Xem tất cả</a></i></h2>';
            $str.='
            <ul class="news-list">
            ';
            
            while($row=mysql_fetch_object($tab))
            {
                $str.='
                <li class="clearfix">
                    <a href="'.myWeb.'tin-tuc/'.slug($row->title).'-i'.$row->id.'.html">
                    <img src="'.webPath.$row->img.'"/>
                    <div>
                        <h2>'.$row->title.'</h2>
                        <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                        <span>'.strcut($row->sum,120).'</span>
                    </div>
                    </a>
                </li>
                ';
            }
            $str.='
            </ul>
            ';       
        }
    }    
    return $str;
}
function news_cate(){
    $pId=intval($_GET['pId']);
    $tb=mysql_query("select id,title from news_cate where id=$pId");
    $r=mysql_fetch_object($tb);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'tin-tuc.html">Tin tức</a>
     <a>'.$r->title.'</a>   
    </div>
    ';
    $str.='
    <div class="head-title clearfix">
        <span>
            <b class="fa fa-calendar"></b> Tin Tức
        </span>
    </div>
    ';
    $str.='
    <ul class="news-list">
    ';
    $sql="select id,title,dates,sum,img from news where active=1 order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $start=($page-1)*limit_record;
    $sql.=" limit $start,".limit_record;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'tin-tuc/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = limit_record;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=tin-tuc";
    $pg->paginationUrl = myWeb."index.php?view=tin-tuc&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function news_one()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select title,content,id,pId from news where id=$id");
    $row=mysql_fetch_object($tab);
    $tb=mysql_query("select id,title from news_cate where id=$row->pId");
    $r=mysql_fetch_object($tb);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'tin-tuc.html">Tin tức</a>
     <a href="'.myWeb.'tin-tuc/'.slug($r->title).'-'.$r->id.'.html">'.$r->title.'</a>     
     <a>'.$row->title.'</a>
    </div>
    ';
    
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Tin tức
        </span>
    </div>
    ';
    $str.='
    <article class="article">
        <h1 class="article-heading">'.$row->title.'</h1>
        <p>'.$row->content.'</p>
    </article>
    ';
    $str.='
    <h3 class="other-article">Bài viết khác</h3>
    <ul class="news-list">
    ';
    $tab=mysql_query("select id,title,dates,sum,img from news where active=1 and id<>$id order by id desc limit 3");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'tin-tuc/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    return $str;
}
function station()
{
    $tab=mysql_query("select * from station order by id asc");
    $str='<ul class="station clearfix">';
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li>'.$row->content.'</li>
        ';
    }
    $str.='</ul>';
    return $str;
}
function left_module()
{
//    $str='
//    <div class="box">
//        <span class="box-title">Tìm Kiếm Sản Phẩm</span>
//        <div class="search-box clearfix">
//        <form action="'.myWeb.'index.php">
//        <table cellpadding="0" cellspacing="0" border="0">
//            <tr>
//                <td>
//                    <input type="hidden" value="tim-kiem" name="view"/>
//                    <input type="text" name="hint" required placeholder="Tên sản phẩm..." required class="search">
//                </td>
//                <td>
//                    <input type="submit" value="GO" class="button"/>
//                </td>
//            </tr>
//        </table>
//        </form>
//        </div>
//    </div>
//    ';
    $str.=catalogue();    
    $str.=support_online();
    //$str.=temp_about();
    //$str.=multi_cate(1);
    $str.=multi_cate(0);
//    $str.='
//<div class="box">
//        <span class="box-title">Video</span>
//<div>
//<iframe width="198" height="111" src="https://www.youtube.com/embed/'.qtext(4).'" frameborder="0" allowfullscreen></iframe>
//</div>
//</div>
//';
    $str.='
    <div class="fb-page" data-href="https://www.facebook.com/ongnuocmiennam" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/ongnuocmiennam/"><a href="https://www.facebook.com/ongnuocmiennam/">Công ty TNHH V.T.L</a></blockquote></div></div>
    ';
    return $str;
}
function catalogue()
{
    $str='
    <div class="box">
        <span class="box-title">Danh Mục Sản Phẩm</span>
    ';
    $str.='
    <div>
    <ul class="catalogue">
    ';
    $tab=mysql_query("select id,title,img from catalogue where active=1 order by id asc");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li>
            
                <span style="background-image:url('.webPath.($row->img===''?def_icon:$row->img).')"> '.$row->title;
        $tb=mysql_query("select id,title from cata_sub where pId=$row->id");
        if(mysql_num_rows($tb)!=0)
        {
            $str.='
            <ul>
            ';
            while($r=mysql_fetch_object($tb))
            {
                $str.='
                <li>
                <a href="'.myWeb.'san-pham/'.slug($r->title).'-'.$r->id.'.html">
                '.$r->title.'
                </a>
                </li>
                ';
            }
            $str.='
            </ul>
            ';
        }
        $str.='
                </span>
            
        </li>
        ';
    }
    $str.='
    </ul>
    </div>
    </div>';
    return $str;
}
function multi_cate($type)
{
    if($type==1){
        $view='tu-bang-dien';
        $table='project_cate';
        $title='Tủ Bảng Điện';
    }else{
        $view='dich-vu-sua-chua';
        $table='serv_cate';
        $title='Dịch Vụ V.T.L';
    }
    $str='
    <div class="box">
        <span class="box-title">'.$title.'</span>
    ';
    $str.='
    <div>
    <ul class="catalogue">
    ';
    $tab=mysql_query("select id,title,img from $table where active=1 and lev=1 order by id asc");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li>
            <span style="background-image:url('.webPath.($row->img===''?def_icon:$row->img).')">
                <a href="'.myWeb.$view.'/'.slug($row->title).'-'.$row->id.'.html">'.$row->title.'</a>';
        if($type!=0){
            $tb=mysql_query("select id,title from $table where pId=$row->id and lev=2");
            if(mysql_num_rows($tb)!=0)
            {
                $str.='
                <ul>
                ';
                while($r=mysql_fetch_object($tb))
                {
                    $str.='
                    <li>
                    <a href="'.myWeb.$view.'/'.slug($r->title).'-'.$r->id.'.html">
                    '.$r->title.'
                    </a>
                    </li>
                    ';
                }
                $str.='
                </ul>
                ';
            }   
        }        
        $str.='
            </span>
        </li>
        ';
    }
    $str.='
    </ul>
    </div>
    </div>';
    return $str;
}
function temp_about()
{
    $str='
    <div class="box">
        <span class="box-title">Giới Thiệu</span>
    ';
    $str.='
    <div>
    <ul class="temp_news">
    ';
    $tab=mysql_query("select id,title,sum,img from about where active=1 order by id desc limit 3");
    $k=1;
    while($row=mysql_fetch_object($tab))
    {
        if($k==1)
        {
            $str.='
            <li class="big-item clearfix">
                <a href="'.myWeb.'gioi-thieu/'.slug($row->title).'-i'.$row->id.'.html">
                <img src="'.webPath.$row->img.'"/>
                <h2>'.$row->title.'</h2>
                <span>'.strcut($row->sum,100).'</span>
                </a>
            </li>
            ';
        }
        else
        {
           $str.='
            <li class="small-item clearfix">
                <a href="'.myWeb.'gioi-thieu/'.slug($row->title).'-i'.$row->id.'.html">
                <img src="'.webPath.$row->img.'"/>
                <h2>'.$row->title.'</h2>
                </a>
            </li>
            ' ;
        }
        $k++;
    }
    $str.='
    </ul>
    </div>
    </div>';
    return $str;
}
function right_module()
{
    $str='';
    $str.=temp_news();
    return $str;
}
function temp_news()
{
    $str='
    <div class="box">
        <span class="box-title">Tin Tức</span>
    ';
    $str.='
    <div>
    <ul class="temp_news">
    ';
    $tab=mysql_query("select id,title,sum,img from news where active=1 order by id desc limit 3");
    $k=1;
    while($row=mysql_fetch_object($tab))
    {
        if($k==1)
        {
            $str.='
            <li class="big-item clearfix">
                <a href="'.myWeb.'tin-tuc/'.slug($row->title).'-i'.$row->id.'.html">
                <img src="'.webPath.$row->img.'"/>
                <h2>'.$row->title.'</h2>
                <span>'.strcut($row->sum,100).'</span>
                </a>
            </li>
            ';
        }
        else
        {
           $str.='
            <li class="small-item clearfix">
                <a href="'.myWeb.'tin-tuc/'.slug($row->title).'-i'.$row->id.'.html">
                <img src="'.webPath.$row->img.'"/>
                <h2>'.$row->title.'</h2>
                </a>
            </li>
            ' ;
        }
        $k++;
    }
    $str.='
    </ul>
    </div>
    </div>';
    return $str;
}
function support_online()
{
    $str='
    <div class="box">
        <span class="box-title">Hỗ Trợ Trực Tuyến</span>
    ';
    $str.='
    <div class="online_support">';
    $tab=mysql_query("select id,hotline,hotline_title from station order by id asc");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <div class="support_box">
            <span>'.$row->hotline_title.'</span>
            <ul>
                <li class="hotline_support">
                <i class="fa fa-phone"></i>
                '.$row->hotline.'</li>';
        $tb=mysql_query("select * from online_support where active=1 and station_id=$row->id order by id asc");
        while($r=mysql_fetch_object($tb))
        {
            $str.='
            <li>
                <span>'.$r->title.'</span>
                <div class="clearfix">
                    <div class="left_sp">'.$r->phone.'</div>
                    <div class="right_sp">
                        <a href="skype:'.$r->skype.'?chat">
                            <img src="'.myWeb.'images/content/skype.png"/>
                        </a>
                    </div>
                </div>
            </li>
            ';
        }
        $str.='
            </ul>
        </div>
        ';
    }
    $str.='
    <!--img src="'.myWeb.'images/content/support_online.png"/-->
    </div>
    </div>';
    return $str;
}
function about()
{
    /*if(isset($_GET['id'])) return about_one();
    else return about_all();*/
    return about_only();
}
function about_only(){
    $tab=mysql_query("select title,content from about where active=1 order by id desc limit 1");
    $row=mysql_fetch_object($tab);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'gioi-thieu.html">Giới thiệu</a>   
    </div>
    ';    
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Giới thiệu
        </span>
    </div>
    ';
    $str.='
    <article class="article">
        <h1 class="article-heading">'.$row->title.'</h1>
        <p>'.$row->content.'</p>
    </article>
    ';
    return $str;
}
function about_all()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Giới thiệu</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Giới thiệu
        </span>
    </div>
    ';
    $str.='
    <ul class="news-list">
    ';
    $sql="select id,title,dates,sum,img from about where active=1 order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $start=($page-1)*limit_record;
    $sql.=" limit $start,".limit_record;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'gioi-thieu/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = limit_record;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."gioi-thieu.html";
    $pg->paginationUrl = myWeb."index.php?view=gioi-thieu&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function about_one()
{
    $id=intval($_GET['id']);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'gioi-thieu.html">Giới thiệu</a>   
    </div>
    ';
    $tab=mysql_query("select title,content from about where id=$id");
    $row=mysql_fetch_object($tab);
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Giới thiệu
        </span>
    </div>
    ';
    $str.='
    <article class="article">
        <h1 class="article-heading">'.$row->title.'</h1>
        <p>'.$row->content.'</p>
    </article>
    ';
    $str.='
    <h3 class="other-article">Bài viết khác</h3>
    <ul class="news-list">
    ';
    $tab=mysql_query("select id,title,dates,sum,img from about where active=1 and id<>$id order by id desc limit 3");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'gioi-thieu/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    return $str;
}

function price()
{
    if(isset($_GET['id'])) return price_one();
    else return price_all();
}
function price_all()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Bảng giá</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Bảng giá
        </span>
    </div>
    ';
    $str.='
    <ul class="news-list">
    ';
    $sql="select id,title,dates,sum,img from price where active=1 order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $start=($page-1)*limit_record;
    $sql.=" limit $start,".limit_record;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'bang-gia/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = limit_record;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=bang-gia";
    $pg->paginationUrl = myWeb."index.php?view=bang-gia&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function price_one()
{
    $id=intval($_GET['id']);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'bang-gia.html">Bảng giá</a>   
    </div>
    ';
    $tab=mysql_query("select title,content from price where id=$id");
    $row=mysql_fetch_object($tab);
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Bảng giá
        </span>
    </div>
    ';
    $str.='
    <article class="article">
        <h1 class="article-heading">'.$row->title.'</h1>
        <p>'.$row->content.'</p>
    </article>
    ';
    $str.='
    <h3 class="other-article">Bài viết khác</h3>
    <ul class="news-list">
    ';
    $tab=mysql_query("select id,title,dates,sum,img from price where active=1 and id<>$id order by id desc limit 3");
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.'bang-gia/'.slug($row->title).'-i'.$row->id.'.html">
            <img src="'.webPath.$row->img.'"/>
            <div>
                <h2>'.$row->title.'</h2>
                <em>'.date("d.m.Y",strtotime($row->dates)).'</em>
                <span>'.strcut($row->sum,120).'</span>
            </div>
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    return $str;
}
function product_index(){
    $str='
    <ul class="product_list clearfix">';
    $view=isset($_GET['view'])?$_GET['view']:'trang-chu';
    if($view=='trang-chu')
        $sql="select id from product where active=1 and hot=1 order by id desc";
    else $sql="select id from product where active=1 order by id desc limit 20";
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.product_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}
function product_all()
{
    $cate=mysql_query("select id,title from cata_sub where active=1 order by id desc");
    while($cate_item=mysql_fetch_object($cate)){
        $tab=mysql_query("select id from product where pId=$cate_item->id and active=1 order by id desc limit 3");
        $count=mysql_num_rows($tab);
        if($count>0){
            $str.='
            <h2 class="cate-title"><span>'.$cate_item->title.'</span>
            <a href="'.myWeb.'san-pham/'.slug($cate_item->title).'-'.$cate_item->id.'.html">
                <i class="fa fa-bell-o"> Xem tất cả</a></i></h2>';
            $str.='
            <ul class="product_list clearfix">';
            while($row=mysql_fetch_object($tab))
            {
                $str.='<li>'.product_list_item($row->id).'</li>';
            }
            $str.='
            </ul>';
        }
    }
    return $str;
}
function product_list_item($id)
{
    $tab=mysql_query("select id,title,img,price from product where id=$id");
    $row=mysql_fetch_object($tab);
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str='
    <a href="'.myWeb.'san-pham/'.slug($row->title).'-i'.$row->id.'.html">
    <img src="'.webPath.$row->img.'"/>
    <em>'.$price.'</em>
    <span>'.$row->title.'</span>
    ';
    $str.='
    </a>';
    return $str;
}
function product()
{
    if(isset($_GET['id'])) return one_product();
    else if(isset($_GET['pId'])) return list_product();
    else return all_product();
}
function all_product()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Sản phẩm</a>   
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Sản Phẩm
        </span>
    </div>
    ';
    $str.=product_all();
    return $str;
}
function list_product()
{
    $pId=intval($_GET['pId']);
    $tmp=mysql_query("select a.id as pId,a.title as pTitle,b.id,b.title from catalogue a,cata_sub b where b.id=$pId");
    $tmp=mysql_fetch_object($tmp);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'san-pham.html">Sản phẩm</a> 
     <a >'.$tmp->pTitle.'</a>  
     <a >'.$tmp->title.'</a> 
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Sản Phẩm
        </span>
    </div>
    ';
    
    $str.='
    <ul class="product_list clearfix">';
    $sql="select id from product where active=1 and pId=$pId order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $lim_pd=15;
    $start=($page-1)*$lim_pd;
    $sql.=" limit $start,".$lim_pd;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.product_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = $lim_pd;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=san-pham&pId=".$pId;
    $pg->paginationUrl = myWeb."index.php?view=san-pham&pId=".$pId."&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function one_product()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select * from product where id=$id");
    $row=mysql_fetch_object($tab);
    $tmp=mysql_query("select a.id as pId,a.title as pTitle,b.id,b.title from catalogue a,cata_sub b where b.id=$row->pId");
    $tmp=mysql_fetch_object($tmp);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'san-pham.html">Sản phẩm</a>
     <a >'.$tmp->pTitle.'</a>   
     <a href="'.myWeb.'san-pham/'.slug($tmp->title).'-'.$tmp->id.'.html">'.$tmp->title.'</a>  
    </div>
    ';
    
    
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Sản Phẩm
        </span>
    </div>
    ';
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str.='
    <div class="clearfix product">
<a href="'.webPath.$row->img.'" class="image-popup">
        <img src="'.webPath.$row->img.'"/>
</a>
<script>
$(".image-popup").magnificPopup({
  //delegate:"a", // child items selector, by clicking on it popup will open
  type: "image"
  // other options
});
</script>
        <div>
            <h1>'.$row->title.'</h1>
            <em>Giá : '.$price.'</em>
            <span>'.nl2br($row->feature).'</span>
        </div>
    </div>
    ';
    $str.='
    <link rel="stylesheet" href="'.myWeb.'jquery-ui.css">  
    <script src="'.myWeb.'js/jquery-ui.js"></script>
    
    <script>
    $(function() {
    $( "#tabs" ).tabs();
    });
    </script>
    <div id="tabs" style="margin-top:10px">
      <ul>
        <li><a href="#tabs-1"><strong>Tính Năng</strong></a></li>
        <li><a href="#tabs-2"><strong>Thông Số Kỹ Thuật</strong></a></li>
      </ul>
      <div id="tabs-1">
        <p>'.$row->content.'</p>
      </div>
      <div id="tabs-2">
        <p>'.$row->detail.'</p>
      </div>
    </div>
    ';
    $tb=mysql_query("select * from product where id<>$id and pId=$row->pId limit 6");
    if(mysql_num_rows($tb)>0){
        $str.='<h3 class="other-article">Sản phẩm khác</h3>';    
        $str.='
        <ul class="product_list clearfix">';
        //$sql="select id from product where active=1 and pId=$pId order by id desc limit 20";
        //$tab=mysql_query($sql);
        while($r=mysql_fetch_object($tb))
        {
            $str.='<li>'.product_list_item($r->id).'</li>';
        }
        $str.='
        </ul>';
    }
    return $str;
}
//Project

function project()
{
    if(isset($_GET['id'])) return one_project();
    else if(isset($_GET['pId'])) return list_project();
    else return all_project();
}
function project_index(){
    $str='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Tủ Bảng Điện
        </span>
    </div>
    ';
    $str.='
    <ul class="product_list clearfix">';
    $view=isset($_GET['view'])?$_GET['view']:'trang-chu';
    if($view=='trang-chu')
        $sql="select id from project where active=1 and hot=1 order by id desc";
    else $sql="select id from project where active=1 order by id desc limit 20";
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.project_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    if(mysql_num_rows($tab)==0) $str='';
    return $str;
}
function project_all()
{
    $str='
    <ul class="product_list clearfix">';
    $view=isset($_GET['view'])?$_GET['view']:'trang-chu';
    if($view=='trang-chu')
        $sql="select id from project where active=1 and hot=1 order by id desc";
    else $sql="select id from project where active=1 order by id desc limit 20";
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.project_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}
function project_list_item($id)
{
    $tab=mysql_query("select id,title,img,price from project where id=$id");
    $row=mysql_fetch_object($tab);
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str='
    <a href="'.myWeb.'tu-bang-dien/'.slug($row->title).'-i'.$row->id.'.html">
    <img src="'.webPath.$row->img.'"/>
    <em>'.$price.'</em>
    <span>'.$row->title.'</span>
    ';
    $str.='
    </a>';
    return $str;
}
function all_project()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Tủ bảng điện</a>   
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Tủ Bảng Điện
        </span>
    </div>
    ';
    $str.=project_all();
    return $str;
}
function list_project()
{
    $pId=intval($_GET['pId']);
    $sub_cate=mysql_query("select id,title,pId from project_cate where id=$pId and lev=2");
    $sub_cate=mysql_fetch_object($sub_cate);
    $cate=mysql_query("select id,title,pId from project_cate where id=$sub_cate->pId and lev=1");
    $cate=mysql_fetch_object($cate);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'tu-bang-dien.html">Tủ bảng điện</a> 
     <a >'.$cate->title.'</a>  
     <a >'.$sub_cate->title.'</a> 
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Tủ Bảng Điện
        </span>
    </div>
    ';
    
    $str.='
    <ul class="product_list clearfix">';
    $sql="select id from project where active=1 and pId=$pId order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $lim_pd=15;
    $start=($page-1)*$lim_pd;
    $sql.=" limit $start,".$lim_pd;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.project_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = $lim_pd;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=tu-bang-dien&pId=".$pId;
    $pg->paginationUrl = myWeb."index.php?view=tu-bang-dien&pId=".$pId."&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function one_project()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select * from project where id=$id");
    $row=mysql_fetch_object($tab);
     $sub_cate=mysql_query("select id,title,pId from project_cate where id=$row->pId and lev=2");
    $sub_cate=mysql_fetch_object($sub_cate);
    $cate=mysql_query("select id,title,pId from project_cate where id=$sub_cate->pId and lev=1");
    $cate=mysql_fetch_object($cate);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'tu-bang-dien.html">Tủ bảng điện</a>
     <a >'.$cate->title.'</a>   
     <a href="'.myWeb.'tu-bang-dien/'.slug($sub_cate->title).'-'.$sub_cate->id.'.html">'.$sub_cate->title.'</a>  
    </div>
    ';
    
    
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Tủ Bảng Điện
        </span>
    </div>
    ';
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str.='
    <div class="clearfix product">
<a href="'.webPath.$row->img.'" class="image-popup">
        <img src="'.webPath.$row->img.'"/>
</a>
<script>
$(".image-popup").magnificPopup({
  //delegate:"a", // child items selector, by clicking on it popup will open
  type: "image"
  // other options
});
</script>
        <div>
            <h1>'.$row->title.'</h1>
            <em>Giá : '.$price.'</em>
            <span>'.nl2br($row->feature).'</span>
        </div>
    </div>
    ';
    $str.='
    <link rel="stylesheet" href="'.myWeb.'jquery-ui.css">  
    <script src="'.myWeb.'js/jquery-ui.js"></script>
    
    <script>
    $(function() {
    $( "#tabs" ).tabs();
    });
    </script>
    <div id="tabs" style="margin-top:10px">
      <ul>
        <li><a href="#tabs-1"><strong>Tính Năng</strong></a></li>
        <li><a href="#tabs-2"><strong>Thông Số Kỹ Thuật</strong></a></li>
      </ul>
      <div id="tabs-1">
        <p>'.$row->content.'</p>
      </div>
      <div id="tabs-2">
        <p>'.$row->detail.'</p>
      </div>
    </div>
    ';
    $str.='<h3 class="other-article">Bài viết liên quan</h3>';
    $tb=mysql_query("select * from project where id<>$id and pId=$row->pId limit 6");
    $str.='
    <ul class="product_list clearfix">';
    $sql="select id from project where active=1 and pId=$pId order by id desc limit 20";
    $tab=mysql_query($sql);
    while($r=mysql_fetch_object($tb))
    {
        $str.='<li>'.project_list_item($r->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}

//Project

//Serv

/*function serv()
{
    if(isset($_GET['id'])) return one_serv();
    else if(isset($_GET['pId'])) return list_serv();
    else return all_serv();
}
function serv_all()
{
    $str='
    <ul class="product_list clearfix">';
    $view=isset($_GET['view'])?$_GET['view']:'trang-chu';
    if($view=='trang-chu')
        $sql="select id from serv where active=1 and hot=1 order by id desc";
    else $sql="select id from serv where active=1 order by id desc limit 20";
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.serv_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}
function serv_list_item($id)
{
    $tab=mysql_query("select id,title,img,price from serv where id=$id");
    $row=mysql_fetch_object($tab);
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str='
    <a href="'.myWeb.'dich-vu-sua-chua/'.slug($row->title).'-i'.$row->id.'.html">
    <img src="'.webPath.$row->img.'"/>
    <em>'.$price.'</em>
    <span>'.$row->title.'</span>
    ';
    $str.='
    </a>';
    return $str;
}
function all_serv()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Dịch vụ sửa chữa</a>   
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Dịch Vụ Sửa Chữa
        </span>
    </div>
    ';
    $str.=serv_all();
    return $str;
}
function list_serv()
{
    $pId=intval($_GET['pId']);
    $sub_cate=mysql_query("select id,title,pId from serv_cate where id=$pId and lev=2");
    $sub_cate=mysql_fetch_object($sub_cate);
    $cate=mysql_query("select id,title,pId from serv_cate where id=$sub_cate->pId and lev=1");
    $cate=mysql_fetch_object($cate);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'dich-vu-sua-chua.html">Dịch vụ sửa chữa</a> 
     <a >'.$cate->title.'</a>  
     <a >'.$sub_cate->title.'</a> 
    </div>
    ';
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Dịch Vụ Sửa Chữa
        </span>
    </div>
    ';
    
    $str.='
    <ul class="product_list clearfix">';
    $sql="select id from serv where active=1 and pId=$pId order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $lim_pd=15;
    $start=($page-1)*$lim_pd;
    $sql.=" limit $start,".$lim_pd;
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.serv_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = $lim_pd;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=dich-vu-sua-chua&pId=".$pId;
    $pg->paginationUrl = myWeb."index.php?view=dich-vu-sua-chua&pId=".$pId."&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function one_serv()
{
    $id=intval($_GET['id']);
    $tab=mysql_query("select * from serv where id=$id");
    $row=mysql_fetch_object($tab);
     $sub_cate=mysql_query("select id,title,pId from serv_cate where id=$row->pId and lev=2");
    $sub_cate=mysql_fetch_object($sub_cate);
    $cate=mysql_query("select id,title,pId from serv_cate where id=$sub_cate->pId and lev=1");
    $cate=mysql_fetch_object($cate);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'dich-vu-sua-chua.html">Dịch vụ sửa chữa</a>
     <a >'.$cate->title.'</a>   
     <a href="'.myWeb.'dich-vu-sua-chua/'.slug($sub_cate->title).'-'.$sub_cate->id.'.html">'.$sub_cate->title.'</a>  
    </div>
    ';
    
    
    $str.='
    <div class="head-title" style="margin-top:20px">
        <span>
            <b class="fa fa-calendar"></b> Dịch Vụ Sửa Chữa
        </span>
    </div>
    ';
    $price=$row->price!=0?number_format($row->price,0,',','.').'VNĐ':'Liên Hệ';
    $str.='
    <div class="clearfix product">
<a href="'.webPath.$row->img.'" class="image-popup">
        <img src="'.webPath.$row->img.'"/>
</a>
<script>
$(".image-popup").magnificPopup({
  //delegate:"a", // child items selector, by clicking on it popup will open
  type: "image"
  // other options
});
</script>
        <div>
            <h1>'.$row->title.'</h1>
            <em>Giá : '.$price.'</em>
            <span>'.nl2br($row->feature).'</span>
        </div>
    </div>
    ';
    $str.='
    <link rel="stylesheet" href="'.myWeb.'jquery-ui.css">  
    <script src="'.myWeb.'js/jquery-ui.js"></script>
    
    <script>
    $(function() {
    $( "#tabs" ).tabs();
    });
    </script>
    <div id="tabs" style="margin-top:10px">
      <ul>
        <li><a href="#tabs-1"><strong>Tính Năng</strong></a></li>
        <li><a href="#tabs-2"><strong>Thông Số Kỹ Thuật</strong></a></li>
      </ul>
      <div id="tabs-1">
        <p>'.$row->content.'</p>
      </div>
      <div id="tabs-2">
        <p>'.$row->detail.'</p>
      </div>
    </div>
    ';
    $str.='<h3 class="other-article">Bài viết liên quan</h3>';
    $tb=mysql_query("select * from serv where id<>$id and pId=$row->pId limit 6");
    $str.='
    <ul class="product_list clearfix">';
    $sql="select id from serv where active=1 and pId=$pId order by id desc limit 20";
    $tab=mysql_query($sql);
    while($r=mysql_fetch_object($tb))
    {
        $str.='<li>'.serv_list_item($r->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}*/

//Serv

function download()
{
    if(isset($_GET['pId'])) return download_cate();
    else return download_all();
}
function download_all(){
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Download</a>   
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Download
        </span>
    </div>
    ';
    $cate=mysql_query("select id,title from download_cate where active=1");
    while($cate_item=mysql_fetch_object($cate)){
        $sql="select id,title,lnk from download where active=1 and pId=$cate_item->id order by id desc limit 10";
        $tab=mysql_query($sql);
        $count=mysql_num_rows($tab);
        if($count>0){
            $str.='
            <h2 class="cate-title"><span>'.$cate_item->title.'</span>
            <a href="'.myWeb.'download/'.slug($cate_item->title).'-'.$cate_item->id.'.html">
                <i class="fa fa-bell-o"> Xem tất cả</a></i></h2>';
            $str.='
            <ul class="download-list">
            ';
            

            while($row=mysql_fetch_object($tab))
            {
                $str.='
                <li class="clearfix">
                    <a href="'.webPath.$row->lnk.'" target="_blank">
                        '.$row->title.'
                    </a>
                </li>
                ';
            }
            $str.='
            </ul>
            ';   
        }
    }    
    return $str;
}
function download_cate(){
    $pId=intval($_GET['pId']);
    $tb=mysql_query("select id,title from download_cate where id=$pId");
    $r=mysql_fetch_object($tb);
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a href="'.myWeb.'download.html">Download</a>   
     <a>'.$r->title.'</a>
    </div>
    ';
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Download
        </span>
    </div>
    ';
    $str.='
    <ul class="download-list">
    ';
    $sql="select id,title,lnk from download where active=1 and pId=$pId order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $page=isset($_GET['page'])?intval($_GET['page']):1;
    $start=($page-1)*30;
    $sql.=" limit $start,30";
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='
        <li class="clearfix">
            <a href="'.webPath.$row->lnk.'" target="_blank">
                '.$row->title.'
            </a>
        </li>
        ';
    }
    $str.='
    </ul>
    ';
    $pg = new bootPagination();
    $pg->pagenumber = $page;
    $pg->pagesize = 30;
    $pg->totalrecords = $count;
    $pg->showfirst = true;
    $pg->showlast = true;
    $pg->paginationcss = "my-pagination";
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = myWeb."index.php?view=download";
    $pg->paginationUrl = myWeb."index.php?view=download&page=[p]";
    $str.='<div class="text-center">'.$pg->process().'</div>';
    return $str;
}
function search()
{
    $str='
    <div class="breadcum">
     <a href="'.myWeb.'"><b class="fa fa-home"></b> Trang chủ</a>
     <a>Tìm kiếm</a>   
    </div>
    ';
    $sql="select id from product where active=1 and title like '%".$_GET['hint']."%' order by id desc";
    $tab=mysql_query($sql);
    $count=mysql_num_rows($tab);
    $str.='
    <div class="head-title">
        <span>
            <b class="fa fa-calendar"></b> Có '.$count.' được tìm thấy với từ khoá "'.$_GET['hint'].'"
        </span>
    </div>
    ';
    $str.='
    <ul class="product_list clearfix">';
    $tab=mysql_query($sql);
    while($row=mysql_fetch_object($tab))
    {
        $str.='<li>'.product_list_item($row->id).'</li>';
    }
    $str.='
    </ul>';
    return $str;
}
?>