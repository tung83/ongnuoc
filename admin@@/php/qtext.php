<?php
function mainProcess()
{
    return qtext();
}
function qtext()
{
    $msg='';
    $id=intval($_GET['id']);
    $temp=mysql_query("select title,content from qtext where id=$id");
    $tmp=mysql_fetch_object($temp);
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$content=str_replace("'","",$_POST['content']);
	}
	if(isset($_POST["update"]))
	{
		$sUpdate="update qtext set content='$content'";
		$sUpdate.=" where id=".$id;
		$test=mysql_query($sUpdate);
		if($test) header("location:".$_SERVER['REQUEST_URI'],true);
		else $msg=mysql_error();
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i> Quản lý text
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
				<label>Nội dung :</label>
				<textarea class="ckeditor" name="content">'.$tmp->content.'</textarea>
			</div>				
			
		</div>
		
                
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>';
	
	$str.='		
            <button type="submit" name="update" class="btn btn-default" onclick="return confirm('."'Bạn có muốn cập nhật không?'".')">Update</button>';	
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