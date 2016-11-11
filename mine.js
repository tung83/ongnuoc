$('.carousel').carousel();

// JavaScript Document
function keypress(e)
{
	var keypressed = null;
	if (window.event)
	{
		keypressed = window.event.keyCode;
	}
	else
	{ 
		keypressed = e.which; 
	}
	
	if (keypressed < 48 || keypressed > 57)
	{ 
		if (keypressed == 8 || keypressed == 127||keypressed==9)
		{
			return;
		}
		return false;
	}
}
function checkmail(email) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(email))
        return false;
    else return true;
}
function chkContact()
{
	var frm=document.frmContact;
	if($.trim(frm.name.value)=="")
	{
		alert("Bạn vui lòng điền đầy đủ các thông tin có chứa dấu (*)");
		frm.name.focus();	
		return;		
	}
	if($.trim(frm.adds.value)=="")
	{
		alert("Bạn vui lòng điền đầy đủ các thông tin có chứa dấu (*)");frm.adds.focus();	
		return;
	}
	if($.trim(frm.phone.value)=="")
	{
		alert("Bạn vui lòng điền đầy đủ các thông tin có chứa dấu (*)");frm.phone.focus();
		return;
	}
	if($.trim(frm.email.value)=="")
	{
		alert("Bạn vui lòng điền đầy đủ các thông tin có chứa dấu (*)");frm.email.focus();
		return;	
	}
	if(checkmail(frm.email.value)==false)
	{
		alert("Email của bạn có định dạng không đúng!");frm.email.focus();
		return;	
	}
	if($.trim(frm.content.value)=="")
	{
		alert("Bạn vui lòng điền đầy đủ các thông tin có chứa dấu (*)");frm.content.focus();
		return;	
	}
	frm.action="";	
}



$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
          disableOn: 700,
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,

          fixedContentPos: false
        });
		
		
$('.simple-ajax-popup-align-top').magnificPopup({
		  disableOn: 600,
          type: 'ajax',
          overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
        });// JavaScript Document
        