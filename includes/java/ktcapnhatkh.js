
function ktCapNhatKH(fname)
{   
		var frdk = document[fname];
		if(isEmpty(frdk.MatKhauCu.value)){
			alert("Quý khách vui lòng nhập mật khẩu củ.");
			frdk.MatKhauCu.focus();
			return false;
		}
		if( ! laMatKhau(frdk.MatKhau.value)){
			alert("Quý khách vui lòng nhập mật khẩu mới, ít nhất 6 kí tự.");
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


