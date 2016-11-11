// JavaScript Document
//----------Dang Thanh --- Terexabenho------------TanAnPhuc---------------
function checkSelect( ojSelect, text ) {
	var k ;
	for (k=ojSelect.options.length-1; k>=0; k--) {
		if (ojSelect.options(k).text == text) {
			ojSelect.options(k).selected = true;
			return true;			
		}
	}
	return false;
}
/* cach su dung 
<script language="javascript" >
	var oj = document['tenForm']['tenSelect'];
	checkSelect(oj,'{TinhTrang}');
</script>
*/
