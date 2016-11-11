/*------------------------------------------
Ngay tao :  17:49:13   09/05/2005 
Cong ty : Tan An Phuc
Du an   : Sieu thi FG
Lap trinh vien : Dang Thanh
------------------------Terexa--------------*/

function ktcapnhat(fname)
{   
		//alert("Cap nhat thanh cong!");
		var frdk = document[fname];
		//alert("Cap nhat thanh cong!");
		if(! isEmpty(frdk.MatKhau.value) ){
			if( ! laMatKhau(frdk.MatKhau.value)){
				alert("Xin ban vui long nhap mat khau. It nhat 8 ki tu");
				frdk.MatKhau.focus();
				return false;
			}
			if( frdk.MatKhau.value != frdk.MatKhau01.value){
				alert("Mat khau khong giong nhau ");
				frdk.MatKhau.focus();
				return false;
			}
		}
			
		if( ! isEmpty(frdk.Email.value) ) {
			if( ! isEmail(frdk.Email.value)){
				alert("Dia chi Email khong hop le ");
				frdk.Email.focus();
				return false;
			}
			
		}
		
/*		if( ! isNubers(frdk.DienThoai.value)){
			alert("Dien thoai khong hop le");
			frdk.DienThoai.focus();
			return false;
			
		}
	*/	
	return true;
}
	