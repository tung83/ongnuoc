/*------------------------------------------
Ngay tao :  17:49:13   09/05/2005 
Cong ty : Tan An Phuc
Du an   : Sieu thi FG
Lap trinh vien : Dang Thanh
------------------------Terexa--------------*/

function ktLienHe(fname)
{   
		//alert("Xin ban vui long nhap ten");
		var frdk = document[fname];
		//alert("Xin ban vui long nhap ten");
		
		if( isEmpty(frdk.HoVaTen.value)){
			alert("You please input fullname");
			frdk.HoVaTen.focus();
			return false;
		}
		if( isEmpty(frdk.DiaChi.value)){
			alert("you please input address");
			frdk.DiaChi.focus();
			return false;
		}if( isEmpty(frdk.DienThoai.value)){
			alert("you please input telephone");
			frdk.DienThoai.focus();
			return false;
		}
		
		
		/*
		if(isEmpty(frdk.TenSuDung.value) || (frdk.TenSuDung.value.length < 8)){
			alert("Xin ban vui long nhap ten su dung. It nhat 8 ki tu");
			frdk.TenSuDung.focus();
			return false;
		}
		
		if(isEmpty(frdk.NamSinh.value) || (!isNuber(frdk.NamSinh.value) )){
			alert("Xin ban vui long nhap nam sinh hop le.");
			frdk.NamSinh.focus();
			return false;
		}
		// Kiem tra gioi tinh 		
		var ok = false;
		for(i=0; i < frdk.GioiTinh.length; ++i){
				if(frdk.GioiTinh[i].checked ){
					ok = true;								 
					break;
				}
		}
		if( ! ok ) {
			alert("Xin ban vui long nhap gioi tinh");
			return false;
		}		
	*/		
		if( ! isEmail(frdk.Email.value)){
				alert("Email address invalid ! ");
				frdk.Email.focus();
				return false;
		}
		if( isEmpty(frdk.YeuCau.value)){
			alert("you please input content");
			frdk.YeuCau.focus();
			return false;
		}

		
	return true;
}
	