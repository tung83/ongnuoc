<?php
function mainProcess()
{
	if(isset($_GET['pId']))	return download();
    else return download_cate();		
}
function download_cate(){
    $msg='';
    $act='download';
    $table='download_cate';
    $lev=1;
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
        $meta_keyword=str_replace("'",'&rsquo;',$_POST["meta_keyword"]);
        $meta_description=str_replace("'",'&rsquo;',$_POST["meta_description"]);
		$active=$_POST["active"]=="on"?1:0;	
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into $table(title,active,meta_keyword,meta_description,lev";
		$sInsert.=") values('$title',$active,'$meta_keyword','$meta_description',$lev)";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update $table set title='$title',active=$active,";
        $sUpdate.="meta_keyword='$meta_keyword',meta_description='$meta_description'";
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
				<li class="active">
					<i class="fa fa-dashboard"></i> Danh mục download
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
                           
							
							<th>Hiển Thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from $table where lev=1";
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
function download()
{
	$msg='';
    $act='download';
    $table='download';
    $pId=intval($_GET['pId']);
    $cate=mysql_query("select id,title from download_cate where id=$pId");
    $cate=mysql_fetch_object($cate);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
        $file = $_FILES["file"]["name"];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
		$file=time().slug($_FILES["file"]["name"]).'.'.$ext;
		$active=$_POST["active"]=="on"?1:0;	
		$title=str_replace("'","&rsquo;",$_POST["title"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into download(active,title,pId";
		$sInsert.=") values($active,'$title',$pId)";
		$test=mysql_query($sInsert);
		$recent=mysql_insert_id();
		if($_FILES['file'])
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			mysql_query("update download set lnk='$file' where id=$recent");	
		}
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;//$msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update download set active=$active,title='$title'";
		if($_FILES['file'])
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],myPath.$file);
			$sUpdate.=",lnk='$file'";
		}
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from download where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from download where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> <a href="main.php?act='.$act.'">Danh mục Download</a>
				</li>
                <li>
                    '.$cate->title.'
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
							<th>Liên kết</th>
											
							<th>Hiển thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from download where pId=$pId order by id desc";
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
            <td>'.($row->title!=''?''.$row->title.'':'&nbsp;').'</td>
			<td>
	'.($row->lnk!=''?'<a href="'.myPath.$row->lnk.'">'.$row->lnk.'</a>':'&nbsp;').'
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
	$str.=ad_paging($lim,$count,'main.php?act=download&',$page);
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
				<label>Tiêu đề </label>
				<input class="form-control" name="title" type="text" value="'.$rowEdit->title.'">
			</div>
			
			<div class="form-group">
				<label>File download </label>
				<input type="file" name="file" />
			</div>					
			
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