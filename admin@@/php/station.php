<?php
function mainProcess()
{
    if(isset($_GET['id'])) return hotline();
	else return station();	
}
function hotline()
{
    $msg='';
	$pId=intval($_GET["id"]);
	$tb=mysql_query("select id,hotline_title from station where id=$pId");
	$r=mysql_fetch_object($tb);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
        $hotline=str_replace("'",'&rsquo;',$_POST["hotline"]);
		$skype=str_replace("'",'',$_POST["skype"]);
		$active=$_POST["active"]=="on"?1:0;
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into online_support(title,phone,skype,active,station_id";
		$sInsert.=") values('$title','$hotline','$skype',$active,$pId)";
		$test=mysql_query($sInsert);
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else $msg=$sInsert;			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update online_support set title='$title',active=$active";
		$sUpdate.=",skype='$skype',phone='$hotline'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from online_support where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from online_support where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> <a href="main.php?act=station">Trụ sở công ty</a>
				</li>
                
				<li class="active">
					<i class="fa fa-wrench"></i> '.$r->hotline_title.'
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
                           <th>Hotline</th>
							<th>Skype</th>						
							<th>Hiển Thị</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from online_support where station_id=$pId order by id desc";
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
			<td>'.$row->title.'</td>
            	<td>'.$row->phone.'</td>
			<td>'.$row->skype.'</td>
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
	$str.=ad_paging($lim,$count,'main.php?act=serv&id=$pId&',$page);
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
        				<label>Hotline :</label>
        				<input class="form-control" required name="hotline" value="'.$rowEdit->phone.'">
        			</div>	
                   <div class="form-group">
        				<label>Skype :</label>
        				<input class="form-control" type="text" required name="skype" value="'.$rowEdit->skype.'">
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
			<button type="reset" class="btn btn-default">Reset</button>
		</div>
	</div>
	</form>
	';	
	return $str;
}
function station()
{
	$msg='';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		
		$lnk=str_replace("'","",$_POST["lnk"]);
		$content=str_replace("'","&rsquo;",$_POST["content"]);		
		$title=str_replace("'","&rsquo;",$_POST["title"]);
        $hotline_title=str_replace("'","&rsquo;",$_POST["hotline_title"]);
        $hotline=str_replace("'","&rsquo;",$_POST["hotline"]);
	}
	if(isset($_POST["addNew"]))
	{
		$sInsert="insert into station(title,content,hotline,hotline_title";
		$sInsert.=") values('$title','$content','$hotline','$hotline_title')";
		$test=mysql_query($sInsert);
		$recent=mysql_insert_id();
	
		if($test)
		{
			header("location:".$_SERVER['REQUEST_URI'],true);
		}
		else echo $sInsert;//$msg=mysql_error();			
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update station set title='$title',content='$content',hotline='$hotline',hotline_title='$hotline_title'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from station where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$sDelete="delete from station where id=".$_POST["idLoad"];
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
					<i class="fa fa-dashboard"></i> Trụ sở công ty
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
                            <th>Tiêu đề - Số đt(Hotline)</th>
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select * from station order by id desc";
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
			<td>'.($row->hotline_title!=''?''.$row->hotline_title.'':'&nbsp;').' / '.$row->hotline.'</td>
			<td align="center">
   <a href="main.php?act=station&id='.$row->id.'" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>
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
	$str.=ad_paging($lim,$count,'main.php?act=station&',$page);
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
				<label>Hotline(Tiêu đề)</label>
				<input class="form-control" name="hotline_title" type="text" value="'.$rowEdit->hotline_title.'">
			</div>
            <div class="form-group">
				<label>Hotline </label>
				<input class="form-control" name="hotline" type="text" value="'.$rowEdit->hotline.'">
			</div>		
            <hr class="devide"/>
            <div class="form-group">
				<label>Tiêu đề </label>
				<input class="form-control" name="title" type="text" value="'.$rowEdit->title.'">
			</div>			
			<div class="form-group">
				<label>Nội dung</label>
				<textarea name="content" class="ckeditor">'.$rowEdit->content.'</textarea>
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