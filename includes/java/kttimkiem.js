/*------------------------------------------
Ngay tao :  17:49:13   09/05/2005 
Cong ty : Tan An Phuc
Du an   : Sieu thi FG
Lap trinh vien : Dang Thanh
------------------------Terexa--------------*/

function ktTimKiem(fname)
{   
		//alert("Xin ban vui long nhap ten");
		var frdk = document[fname];
		//alert("Xin ban vui long nhap ten");
		
		if( isEmpty(frdk.TuKhoa.value)){
			alert(" Vui lòng nhập tên sản phẩm cần tìm");
			frdk.TuKhoa.focus();
			return false;
		}
	return true;
}
	
function ktTimKiem_en(fname)
{   
		//alert("Xin ban vui long nhap ten");
		var frdk = document[fname];
		//alert("Xin ban vui long nhap ten");
		
		if( isEmpty(frdk.TuKhoa.value)){
			alert(" Please input product name need search");
			frdk.TuKhoa.focus();
			return false;
		}
	return true;
}