function ktDangKy(fname)
{   		
		var frdk = document[fname];		
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Please check FullName");
			frdk.HoVaTen.focus();
			return false;
		}
		if( isEmpty(frdk.NgaySinh.value)){
			alert("Please check Birth day");
			frdk.NgaySinh.focus();
			return false;
		}
		
		if(isEmpty(frdk.DienThoai.value) || (!isNuber(frdk.DienThoai.value) )){
			alert("Please check Telephone.");
			frdk.DienThoai.value="";
			frdk.DienThoai.focus();
			return false;
		}
		
		if( ! isEmail(frdk.Email.value)){
				alert("Invalid Email ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("please check Address ");
			frdk.DiaChi.focus();
			return false;
		}
		if(isEmpty(frdk.TenSuDung.value) || (frdk.TenSuDung.value.length < 4)){
			alert("please check Username");
			frdk.TenSuDung.focus();
			return false;
		}
		if( ! laMatKhau(frdk.MatKhau.value)){
			alert("please check Password");
			frdk.MatKhau.focus();
			return false;
		}
		
		if( frdk.MatKhau.value != frdk.MatKhau2.value){
			alert("password is not similar");
			frdk.MatKhau.focus();
			return false;
		}
		
	return true;
}
	