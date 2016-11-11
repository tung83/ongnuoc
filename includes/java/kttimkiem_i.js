

function ktTimKiem_I(fname)
{   
		//alert("Xin ban vui long nhap ten");
		var frdk = document[fname];
		//alert("Xin ban vui long nhap ten");
		
		if( isEmpty(frdk.TuKhoa.value)){
			alert(" you please input product name need search");
			frdk.TuKhoa.focus();
			return false;
		}
	return true;
}
	