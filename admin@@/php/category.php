<?php
function mainProcess()
{
	if(isset($_GET["id"])) return product();
    else return category();	
}
function category()
{
	$msg='';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'","&rsquo;",$_POST["title"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into category(title";
		$sInsert.=") values('$title')";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;		
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update category set title='$title'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from category where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from category where id=".$_POST["idLoad"];
		$test=mysql_query($sDelete);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Danh mục sản phẩm
				</li>
			</ol>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tiêu đề</th>							
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from category order by id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$active=$row->active==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>
	           '.$row->title.'
			</td>
			<td align="center">
            <a href="main.php?act=cart&cata_id='.$row->id.'" class="glyphicon glyphicon-euro" aria-hidden="true"></a>
            <a href="main.php?act=category&id='.$row->id.'" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>';
	if(isset($_POST["Edit"])==1)
	{
		if($_POST["idLoad"]==$row->id)
		{
			$str.='
			<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
			';	
		}
		else
		{
			$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';	
		}	
	}
	else
	{
		$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';		
	}
	
	$str.='
	<a href="javascript:operationFrm('.$row->id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=category&',$page);
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			Cập nhật - Thêm mới thông tin
		</div>
		
		<div class="panel-body">
		<div class="row">
		
		<div class="col-lg-12">
		
			<div class="form-group">
				<label>Tiêu đề :</label>
				<input class="form-control" required name="title" value="'.$rowEdit->title.'">
			</div>				
           
			
		</div>                
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$str.='		
				<button type="submit" name="update" class="btn btn-default">Update</button>';
	}
	else
	{
		$str.='		
				<button type="submit" name="addNew" class="btn btn-default">Submit</button>';	
	}
	$str.='
			<button type="reset" class="btn btn-default" id="reset">Reset</button>
		</div>
		
	<div>
	<!--div row-->
	</div>
	<!--panel body-->
	</div>
	</div>
	</div>
	</form>
	';	
	return $str;
}

function product()
{
    $msg='';
    $pId=intval($_GET['id']);
    $s="select title from category where id=$pId";
    $temp=mysql_query($s);
    $tmp=mysql_fetch_object($temp);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
        $pId=intval($_POST['pId']);
		$meta_descript=str_replace("'","&rsquo;",$_POST["meta_descript"]);
		$active=$_POST["active"]=="on"?1:0;	
        $hot=$_POST["hot"]=="on"?1:0;	
        $sale=$_POST["sale"]=="on"?1:0;	
        $promotion=$_POST["promotion"]=="on"?1:0;	
		$title=str_replace("'","&rsquo;",$_POST["title"]);
        $sum=str_replace("'","&rsquo;",$_POST["sum"]);
        $price=intval($_POST['price']);
        $reduce=intval($_POST['reduce']);
        $content=str_replace("'","",$_POST['content']);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into product(title,pId,price,dates";
		$sInsert.=") values('$title',$pId,'$price',now())";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;		
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update product set title='$title',pId=$pId";
        $sUpdate.=",price=$price";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from product where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from product where id=".$_POST["idLoad"];
		$test=mysql_query($sDelete);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> <a href="main.php?act=category">Ngành hàng</a>
				</li>
                <li class="active">
                   <i class="fa fa-wrench"></i> '.$tmp->title.' 
                </li>
			</ol>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tiêu đề</th>							
							<th>Giá bán</th>
						    	
						
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from product where pId=$pId order by id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>
	           '.$row->title.'
			</td>
			<td>'.number_format($row->price,0,",",".").'</td>
			<td align="center">
        ';
	if(isset($_POST["Edit"])==1)
	{
		if($_POST["idLoad"]==$row->id)
		{
			$str.='
			<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
			';	
		}
		else
		{
			$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';	
		}	
	}
	else
	{
		$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';		
	}
	
	$str.='
	<a href="javascript:operationFrm('.$row->id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=category&id='.$_GET['id'].'&',$page);
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			Cập nhật - Thêm mới thông tin
		</div>
		
		<div class="panel-body">
		<div class="row">
		
		<div class="col-lg-6">
		
			<div class="form-group">
				<label>Tiêu đề :</label>
				<input class="form-control" required name="title" value="'.$rowEdit->title.'">
			</div>	
            <div class="form-group">
                <label>Giá bán :</label>
                <input class="form-control" required name="price" type="number" value="'.$rowEdit->price.'"/>
            </div>				
		</div>
		<div class="col-lg-6">
			';
        
        $str.='
            <div class="form-group">
                <label>Thuộc danh mục</label>
                <select class="form-control" name="pId">';
        $tb=mysql_query("select id,title from category order by title asc,id desc");
        while($r=mysql_fetch_object($tb))
        {
            if($r->id==$pId) $selected=' selected="selected"';
            else $selected='';
            $str.='
            <option value="'.$r->id.'"'.$selected.'>'.$r->title.'</option>
            ';
        }
        $str.='
                </select>
            </div>
            			
		</div>
                
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$str.='		
				<button type="submit" name="update" class="btn btn-default">Update</button>';
	}
	else
	{
		$str.='		
				<button type="submit" name="addNew" class="btn btn-default">Submit</button>';	
	}
	$str.='
			<button type="reset" class="btn btn-default" id="reset">Reset</button>
		</div>
		
	<div>
	<!--div row-->
	</div>
	<!--panel body-->
	</div>
	</div>
	</div>
	</form>
	';	
	return $str;
}
function pd_image()
{
    $msg='';
    $id=intval($_GET['pd_id']);
    $s="select a.title,a.id,b.id as pId,b.title as pTitle,c.id as pd_id,c.title as pd_title from category a,sub_category b,product c where c.id=$id and c.pId=b.id and b.pId=a.id";
    $temp=mysql_query($s);
    $tmp=mysql_fetch_object($temp);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$file=time().$_FILES["file"]["name"];
		$active=$_POST["active"]=="on"?1:0;	
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into product_image(active,pId";
		$sInsert.=") values($active,$id)";
		$test=mysql_query($sInsert);
        $recent=mysql_insert_id();
        if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
            $small=new resize(myPath.$file);
            $small->resizeImage(184,170,'exact');
            $small->saveImage(myPath.'small_'.$file,100);
            
            $thumb=new resize(myPath.$file);
            $thumb->resizeImage(256,237,'exact');
            $thumb->saveImage(myPath.'thumb_'.$file,100);
            
            $orgin=new resize(myPath.$file);
            $orgin->resizeImage(327,303,'exact');
            $orgin->saveImage(myPath.'orgin_'.$file,100);
            
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(640, 539, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
			mysql_query("update product_image set img='$file' where id=$recent");	
		}
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;		
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update product_image set active=$active";
        if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
            $small=new resize(myPath.$file);
            $small->resizeImage(184,170,'exact');
            $small->saveImage(myPath.'small_'.$file,100);
            
			$thumb=new resize(myPath.$file);
            $thumb->resizeImage(256,237,'exact');
            $thumb->saveImage(myPath.'thumb_'.$file,100);
            
            $orgin=new resize(myPath.$file);
            $orgin->resizeImage(327,303,'exact');
            $orgin->saveImage(myPath.'orgin_'.$file,100);
            
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(640, 539, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
			$sUpdate.=",img='$file'";
            
            //Delete old file
            $tb=mysql_query("select img from product_image where id=".$_POST['idLoad']);
            $r=mysql_fetch_object($tb);
            if(trim($r->img)!='') 
            {
                try
                {
                    unlink(webPath.$r->img);
                    unlink(webPath.'thumb_'.$r->img);
                    unlink(webPath.'orgin_'.$r->img);
                    unlink(webPath.'small_'.$r->img);
                }
                catch(Exception $ex){}                   
            }
            //End delete old file
		}
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
        
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from product_image where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from product_image where id=".$_POST["idLoad"];
        //Delete old file
        $tb=mysql_query("select img from product_image where id=".$_POST['idLoad']);
        $r=mysql_fetch_object($tb);
        if(trim($r->img)!='') 
        {
            try
            {
                unlink(webPath.$r->img);
                unlink(webPath.'thumb_'.$r->img);
                unlink(webPath.'orgin_'.$r->img);
                unlink(webPath.'small_'.$r->img);
            }
            catch(Exception $ex){}                   
        }
        //End delete old file
		$test=mysql_query($sDelete);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li>
					<i class="fa fa-dashboard"></i> <a href="main.php?act=category">Danh mục sản phẩm</a>
				</li>
                <li>
					<i class="fa fa-dashboard"></i> <a href="main.php?act=category&id='.$tmp->id.'">'.$tmp->title.'</a>
				</li>
                <li>
                    <i class="fa fa-dashboard"></i> <a href="main.php?act=category&pId='.$tmp->pId.'">'.$tmp->pTitle.'</a>
                </li>
                <li class="active">
                   <i class="fa fa-wrench"></i> '.$tmp->pd_title.' 
                </li>
			</ol>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$msg.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Hình ảnh</th>											
							<th>Hiển thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from product_image where pId=$id order by id desc";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=10;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$active=$row->active==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>
	           <img src="'.myPath.(trim($row->img)==''?'no-image.jpg':'thumb_'.$row->img).'" class="img-responsive img-thumbnail" style="max-height:80px"/>
			</td>
			<td>'.$active.'</td>
			<td align="center">
        ';
	if(isset($_POST["Edit"])==1)
	{
		if($_POST["idLoad"]==$row->id)
		{
			$str.='
			<a href="'.$_SERVER['REQUEST_URI'].'" class="glyphicon glyphicon-refresh" aria-hidden="true"></a>
			';	
		}
		else
		{
			$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';	
		}	
	}
	else
	{
		$str.='
			<a href="javascript:operationFrm('.$row->id.",'E'".')" class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
			';		
	}
	
	$str.='
	<a href="javascript:operationFrm('.$row->id.",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=category&pd_id='.$id.'&',$page);
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			Cập nhật - Thêm mới thông tin
		</div>
		
		<div class="panel-body">
		<div class="row">
		
		<div class="col-lg-6">
		
			<div class="form-group">
				<label>Hình ảnh(640x539):</label>
				<input name="file" type="file"/>
			</div>	
		</div>
		<div class="col-lg-6">
            <div class="form-group">
				<label class="checkbox-inline">
					<input type="checkbox" name="active" '.($rowEdit->active==1?"checked='checked'":"").'>Hiển thị
				</label>
			</div>				
		</div>
                
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$str.='		
				<button type="submit" name="update" class="btn btn-default">Update</button>';
	}
	else
	{
		$str.='		
				<button type="submit" name="addNew" class="btn btn-default">Submit</button>';	
	}
	$str.='
			<button type="reset" class="btn btn-default" id="reset">Reset</button>
		</div>
		
	<div>
	<!--div row-->
	</div>
	<!--panel body-->
	</div>
	</div>
	</div>
	</form>
	';	
	return $str;
}
?>