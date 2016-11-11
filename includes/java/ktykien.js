

function ktYkien(fname)
{   
		var frdk = document[fname];		
		if( isEmpty(frdk.txthovaten.value)){
			alert("Quý khách vui lòng nhập họ và tên ");
			frdk.txthovaten.focus();
			return false;
		}
		if( isEmpty(frdk.txtemail.value)){
			alert("Quý khách vui lòng nhập email ");
			frdk.txtemail.focus();
			return false;
		}
		if( ! isEmail(frdk.txtemail.value)){
				alert("Địa chỉ email không hợp lệ ");
				frdk.txtemail.focus();
				return false;
		}
		if( isEmpty(frdk.txttieude.value)){
			alert("Quý khách vui lòng nhập tiêu đề");
			frdk.txttieude.focus();
			return false;
		}
		if( isEmpty(frdk.txtnoidung.value)){
			alert("Quý khách vui lòng nhập ý kiến ");
			frdk.txtnoidung.focus();
			return false;

		}
		alert("Gửi Thành Công!");
	return true;

}

