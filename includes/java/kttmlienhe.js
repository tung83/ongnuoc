
function ktLienHe(fname)
{   
		var frdk = document[fname];		
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Quý khách vui lòng nhập tên ");
			frdk.HoVaTen.focus();
			return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("Quý khách vui lòng nhập địa chỉ");
			frdk.DiaChi.focus();
			return false;
		}if( isEmpty(frdk.DienThoai.value)){
			alert("Quý khách vui lòng nhập điện thoại ");
			frdk.DienThoai.focus();
			return false;
		}
		if( ! isEmail(frdk.Email.value)){
				alert("Địa chỉ email không hợp lệ ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.YeuCau.value)){
			alert("Quý khách vui lòng viết nội dung yêu cầu");
			frdk.YeuCau.focus();
			return false;
		}		
	return true;
}
function ktLienHe_en(fname)
{   
		var frdk = document[fname];		
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Please input full name ");
			frdk.HoVaTen.focus();
			return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("Please input address");
			frdk.DiaChi.focus();
			return false;
		}if( isEmpty(frdk.DienThoai.value)){
			alert("Please input telephone");
			frdk.DienThoai.focus();
			return false;
		}
		if( ! isEmail(frdk.Email.value)){
				alert("Invalid email ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.YeuCau.value)){
			alert("Please input content");
			frdk.YeuCau.focus();
			return false;
		}		
	return true;
}	