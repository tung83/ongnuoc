
function ktCapNhatKH(fname)
{   
		var frdk = document[fname];
		if(isEmpty(frdk.MatKhauCu.value)){
			alert("Please input old password.");
			frdk.MatKhauCu.focus();
			return false;
		}
		if( ! laMatKhau(frdk.MatKhau.value)){
			alert("Please input new password.");
			frdk.MatKhau.focus();
			return false;
		}
		
		if( frdk.MatKhau.value != frdk.MatKhau2.value){
			alert("Password is not similar. ");
			frdk.MatKhau.focus();
			return false;
		}
		
	return true;
}


