
function ktDangKy(fname)
{   		
		var frdk = document[fname];		
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Quý khách vui lòng nhập họ và tên !");
			frdk.HoVaTen.focus();
			return false;
		}
		if( isEmpty(frdk.NgaySinh.value)){
			alert("Quý khách vui lòng nhập ngày sinh !");
			frdk.NgaySinh.focus();
			return false;
		}
		if(isEmpty(frdk.DienThoai.value) || (!isNuber(frdk.DienThoai.value) )){
			alert("Quý khách vui lòng nhập số điện thoại hợp lệ !");
			frdk.DienThoai.value="";
			frdk.DienThoai.focus();
			return false;
		}
		if( ! isEmail(frdk.Email.value)){
				alert("Địa chỉ Email không hợp lệ !");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("Quý khách vui lòng nhập địa chỉ liên hệ ! ");
			frdk.DiaChi.focus();
			return false;
		}
		if(isEmpty(frdk.TenSuDung.value) || (frdk.TenSuDung.value.length < 4)){
			alert("Quý khách vui lòng nhập tên đăng nhập, tên đăng nhập ít nhất 4 kí tự. ");
			frdk.TenSuDung.focus();
			return false;
		}
		if( ! laMatKhau(frdk.MatKhau.value)){
			alert("Quý khách vui lòng nhập mật khẩu, mật khẩu ít nhất 6 kí tự.");
			frdk.MatKhau.focus();
			return false;
		}
		
		if( frdk.MatKhau.value != frdk.MatKhau2.value){
			alert("Mật khẩu không giống nhau ");
			frdk.MatKhau.focus();
			return false;
		}
		
	return true;
}
	