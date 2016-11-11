<?php
function mainProcess()
{
	return pages_seo();	
}
function pages_seo()
{
	$msg='';
	if(isset($_POST["update"]))
	{
        $meta_keyword=str_replace("'",'&rsquo;',$_POST["meta_keyword"]);
		$meta_description=str_replace("'",'&rsquo;',$_POST["meta_description"]);
	}
	
	if(isset($_POST["update"]))
	{
		$sUpdate="update menu set ";
		$sUpdate.="meta_keyword='$meta_keyword',meta_description='$meta_description'";
		$sUpdate.=" where id=".$_POST["idLoad"];
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	if(isset($_POST["Edit"])&&$_POST["Edit"]==1)
	{
		$sql="select * from menu where id=".$_POST["idLoad"];
		$tabEdit=mysql_query($sql);
		$rowEdit=mysql_fetch_object($tabEdit);
	}

	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> PAGES SEO
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
							<th>Trang</th>	
                           
							<th>meta_keyword</th>
                            <th>meta_description</th>						
							
							<th style="width:12% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$s="select id,title,meta_keyword,meta_description from menu order by id desc";
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
            
			<td>'.nl2br($row->meta_keyword).'</td>
            <td>'.nl2br($row->meta_description).'</td>
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
				  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$str.=ad_paging($lim,$count,'main.php?act=seo&',$page);
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
        				<input class="form-control" readonly required name="title" value="'.$rowEdit->title.'">
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