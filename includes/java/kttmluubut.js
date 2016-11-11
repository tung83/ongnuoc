/*------------------------------------------
Ngay tao :  17:49:13   09/05/2005 
Cong ty : Tan An Phuc
Du an   : Sieu thi FG
Lap trinh vien : Dang Thanh
------------------------Terexa--------------*/

function ktLuuBut(fname)
{   
		//alert("Xin ban vui long nhap ten");
		var frdk = document[fname];
		//alert("Xin ban vui long nhap ten");
		
		if( isEmpty(frdk.Ten.value)){
			alert("Xin ban vui long nhap ten");
			frdk.Ten.focus();
			return false;
		}
		if( isEmpty(frdk.NoiDung.value)){
			alert("Xin ban vui long nhap noi dung bai viet");
			frdk.NoiDung.focus();
			return false;
		}
		
	return true;
}
	