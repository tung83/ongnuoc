
function ktDonHang(fname)
{   
		var frdk = document[fname];
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Vui lòng nhập họ và tên ! ");
			frdk.HoVaTen.focus();
			return false;
		}
		if( ! isEmail(frdk.Email.value)){
				alert("Email không hợp lệ ! ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("Vui lòng nhập địa chỉ liên lạc !");
			frdk.DiaChi.focus();
			return false;
		}
		if( isEmpty(frdk.DienThoai.value) || (!isNuber(frdk.DienThoai.value) )){
			alert("Vui lòng nhập số điện thoại !");
			frdk.DienThoai.focus();
			return false;
		}
		if( isEmpty(frdk.DiaChiGiao.value)){
			alert("Vui lòng nhập địa chỉ giao hàng !");
			frdk.DiaChiGiao.focus();
			return false;
		}
		
	return true;
}
function ktDonHang_en(fname)
{   
		var frdk = document[fname];
		if( isEmpty(frdk.HoVaTen.value)){
			alert("Please input Full Name !");
			frdk.HoVaTen.focus();
			return false;
		}
		if( ! isEmail(frdk.Email.value)){
				alert("Invalid Email ! ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("Please input Address !");
			frdk.DiaChi.focus();
			return false;
		}if( isEmpty(frdk.DienThoai.value) || (!isNuber(frdk.DienThoai.value) )){
			alert("Please input Telephone !");
			frdk.DienThoai.focus();
			return false;
		}
		
	return true;
}