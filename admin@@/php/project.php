<?php
function mainProcess()
{
	if(isset($_GET["id"])) return product();
    else if(isset($_GET['pId'])) return cata_sub();
	else return cata();	
}
function cata()
{
	$msg='';
    $act='project';
    $table='project_cate';
    $lev=1;
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
        $meta_keyword=str_replace("'",'&rsquo;',$_POST["meta_keyword"]);
        $meta_description=str_replace("'",'&rsquo;',$_POST["meta_description"]);
		$file=time().$_FILES['file']['name'];
		$active=$_POST["active"]=="on"?1:0;	
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into project_cate(title,active,meta_keyword,meta_description,lev";
		$sInsert.=") values('$title',$active,'$meta_keyword','$meta_description',$lev)";
		$test=mysql_query($sInsert);
        if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(15, 16, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
			mysql_query("update project_cate set img='$file' where id=$recent");	
		}
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update project_cate set title='$title',active=$active,";
        $sUpdate.="meta_keyword='$meta_keyword',meta_description='$meta_description'";
        if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(15, 16, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
		    $sUpdate.=",img='$file'";	
		}
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from project_cate where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from project_cate where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> Danh mục tủ bảng điện
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
							<th>Tiêu Đề</th>
                           <th>Icon</th>
							
							<th>Hiển Thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from project_cate where lev=1";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=50;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$active=$row->active==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>'.$row->title.'</td>
            <td><img src="'.myPath.($row->img===''?def_icon:$row->img).'" class="img-responsive"/></td>
		
			<td>'.$active.'</td>
			<td align="center">
	<a href="main.php?act='.$act.'&pId='.$row->id.'" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>';
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
	$str.=ad_paging($lim,$count,'main.php?act='.$act.'&page=',$page);
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
 	    

        <div class="col-lg-12">		
			<div class="form-group">
				<label>Tiêu đề :</label>
				<input class="form-control" required name="title" value="'.$rowEdit->title.'">
			</div>	
            <div class="form-group">
				<label>Meta keyword :</label>
				<input class="form-control" name="meta_keyword" value="'.$rowEdit->meta_keyword.'">
                <p class="help-block">Mỗi keyword cách nhau bằng dấu ",".</p>
			</div>	
            <div class="form-group">
				<label>Meta Description :</label>
				<textarea class="form-control" name="meta_description">'.$rowEdit->meta_description.'</textarea>
                <p class="help-block">Trình bày thông tin tóm tắt trên Google</p>
			</div>		
            <div class="form-group">
				<label>Icon danh mục (15x16):</label>
				<input type="file" name="file"/>
			</div>		
            <div class="form-group">
				<label class="checkbox-inline">
					<input type="checkbox"  name="active" '.($rowEdit->active==1?"checked='checked'":"").'>Hiển Thị
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
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
	</form>
	';	
	return $str;	
}
function cata_sub()
{
    $msg='';
    $pId=intval($_GET['pId']);
    $lev=2;
    $act='project';
    $table='project_cate';
    $tmp=mysql_query("select id,title from project_cate where id=$pId");
    $tmp=mysql_fetch_object($tmp);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
		$meta_keyword=str_replace("'",'&rsquo;',$_POST["meta_keyword"]);
		$meta_description=str_replace("'",'&rsquo;',$_POST["meta_description"]);
		$active=$_POST["active"]=="on"?1:0;	
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into $table(title,active,pId,meta_keyword,meta_description,lev";
		$sInsert.=") values('$title',$active,$pId,'$meta_keyword','$meta_description',$lev)";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update $table set title='$title',active=$active";
        $sUpdate.=",meta_keyword='$meta_keyword',meta_description='$meta_description'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from $table where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from $table where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> <a href="main.php?act='.$act.'">Danh mục tủ bảng điện</a>
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
							<th>Tiêu Đề</th>
                            <th>meta_keyword</th>
                            <th>meta_description</th>	
							
							<th>Hiển Thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from $table where pId=$pId and lev=$lev";
	$tab=mysql_query($s);
	$count=mysql_num_rows($tab);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
	$lim=50;
	$start=($page-1)*$lim;
	$s.=" limit $start,$lim";
	$tab=mysql_query($s);
	while($row=mysql_fetch_object($tab))
	{
		$active=$row->active==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>'.$row->title.'</td>
            
		<td>'.nl2br($row->meta_keyword).'</td>
                            <td>'.nl2br($row->meta_description).'</td>
			<td>'.$active.'</td>
			<td align="center">
	<a href="main.php?act='.$act.'&id='.$row->id.'" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>';
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
	$str.=ad_paging($lim,$count,'main.php?act='.$act.'&pId='.$pId.'&page=',$page);
	$str.='			
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
 	    

        <div class="col-lg-12">		
			<div class="form-group">
				<label>Tiêu đề :</label>
				<input class="form-control" required name="title" value="'.$rowEdit->title.'">
			</div>		
            <div class="form-group">
				<label>Meta Keyword :</label>
				<input class="form-control" required name="meta_keyword" value="'.$rowEdit->meta_keyword.'">
                <p class="help-block">Mỗi keyword cách nhau bằng dấu ",".</p>
			</div>	
            <div class="form-group">
				<label>Meta Description :</label>
				<textarea class="form-control" name="meta_description">'.$rowEdit->meta_description.'</textarea>
                <p class="help-block">Trình bày thông tin tóm tắt trên Google</p>
			</div>			
            <div class="form-group">
				<label class="checkbox-inline">
					<input type="checkbox"  name="active" '.($rowEdit->active==1?"checked='checked'":"").'>Hiển Thị
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
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
	</form>
	';	
	return $str;
}
function product()
{
	$msg='';
	$pId=intval($_GET["id"]);
    $act='project';
    $table='project';
	$sub_cate=mysql_query("select id,title,pId from project_cate where id=$pId and lev=2");
    $sub_cate=mysql_fetch_object($sub_cate);
    $cate=mysql_query("select id,title,pId from project_cate where id=$sub_cate->pId and lev=1");
    $cate=mysql_fetch_object($cate);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
        $detail=str_replace("'",'&rsquo;',$_POST["detail"]);
		$content=str_replace("'",'',$_POST["content"]);
		$sum=str_replace("'",'&rsquo;',$_POST["sum"]);
		$file=time().$_FILES["file"]["name"];
        $price=intval($_POST['price']);
		$active=$_POST["active"]=="on"?1:0;
        $hot=$_POST["hot"]=="on"?1:0;
        $meta_keyword=str_replace("'",'&rsquo;',$_POST["meta_keyword"]);
		$meta_description=str_replace("'",'&rsquo;',$_POST["meta_description"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into $table(title,content,active,dates,pId,price,detail,feature,hot,meta_keyword,meta_description";
		$sInsert.=") values('$title','$content',$active,now(),$pId,$price,'$detail','$sum',$hot,'$meta_keyword','$meta_description')";
		$test=mysql_query($sInsert);
		$recent=mysql_insert_id();
		if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(600, 560, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
			mysql_query("update $table set img='$file' where id=$recent");	
		}
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update $table set title='$title',active=$active,meta_keyword='$meta_keyword',meta_description='$meta_description'";
		$sUpdate.=",content='$content',price=$price,detail='$detail',feature='$sum',hot=$hot";
		if(checkImg($file)==true)
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$rexobj = new resize(myPath.$file);
			$rexobj -> resizeImage(600, 560, 'exact');
			$rexobj->saveImage(myPath.$file,100);	
			$sUpdate.=",img='$file'";
		}
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from $table where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from $table where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> <a href="main.php?act='.$act.'">Danh mục tủ bảng điện</a>
				</li>
                <li>
					<i class="fa fa-wrench"></i> <a href="main.php?act='.$act.'&pId='.$cate->id.'">'.$cate->title.'</a>
				</li>
				<li class="active">
					<i class="fa fa-wrench"></i> '.$sub_cate->title.'
				</li>
			</ol>
		</div>
	</div>';
    
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$sUpdate.'</div>';	
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
							<th>Tiêu Đề</th>	
                           <th>Đơn Giá(VNĐ)</th>
							<th>Hình Ảnh</th>						
							<th>Hiển Thị</th>
                            <th>SP Mới</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from $table where pId=$pId order by id desc";
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
        $hot=$row->hot==1?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
		$str.='
		<tr>
			<td>'.$row->id.'</td>
			<td>'.$row->title.'</td>
            	<td>'.number_format($row->price,0,',','.').'</td>
			<td><img src="'.myPath.$row->img.'" class="img-responsive img-thumbnail" style="max-height:100px"/></td>
			<td>'.$active.'</td>
            <td>'.$hot.'</td>
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
	$str.=ad_paging($lim,$count,'main.php?act='.$act.'&id='.$pId.'&page=',$page);
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		<div class="row">
		<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>

		<div class="col-lg-12">
            
        			<div class="form-group">
        				<label>Tiêu đề :</label>
        				<input class="form-control" required name="title" value="'.$rowEdit->title.'">
        			</div>	
                    	
                   <div class="form-group">
        				<label>Đơn giá :</label>
        				<input class="form-control" type="number" required name="price" value="'.$rowEdit->price.'">
        			</div>	
                    <div class="form-group">
        				<label>Hightlight features :</label>
        				<textarea name="sum" class="form-control">'.$rowEdit->feature.'</textarea>
        			</div>
                    <div class="form-group">
        				<label>Meta Keyword :</label>
        				<input class="form-control" required name="meta_keyword" value="'.$rowEdit->meta_keyword.'">
                        <p class="help-block">Mỗi keyword cách nhau bằng dấu ",".</p>
        			</div>	
                    <div class="form-group">
        				<label>Meta Description :</label>
        				<textarea class="form-control" name="meta_description">'.$rowEdit->meta_description.'</textarea>
                        <p class="help-block">Trình bày thông tin tóm tắt trên Google</p>
        			</div>	
                    <div class="form-group">
        				<label>Tính năng :</label>
        				<textarea name="content" class="ckeditor">'.$rowEdit->content.'</textarea>
        			</div>
                    <div class="form-group">
        				<label>Thông số kỹ thuật :</label>
        				<textarea name="detail" class="ckeditor">'.$rowEdit->detail.'</textarea>
        			</div>	
        	
			<div class="form-group">
				<label>Hình ảnh (600x560):</label>
				<input type="file" name="file"/>
			</div>
			<div class="form-group">
				<label class="checkbox-inline">
					<input type="checkbox" name="active" '.($rowEdit->active==1?"checked='checked'":"").'>Hiển thị					
				</label>
			</div>	
            <div class="form-group">
				<label class="checkbox-inline">
					<input type="checkbox" name="hot" '.($rowEdit->hot==1?"checked='checked'":"").'>SP mới					
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
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
	</form>
	';	
	return $str;		
}
?>