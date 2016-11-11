// JavaScript Document
$.fn.menuSlide=function(wValue,sValue){
	var count=$(this).find(".eLeft .obs ul li").length;
	var fullWidth=wValue*count;
	var id=$(this);
	$(this).find(".eLeft .obs").width(fullWidth);
	$(this).find(".eRight .eNxt").on("click",function(){
		var c=id.find(".eLeft .obs").position();
		if((-c.left+sValue*wValue)<fullWidth)
		{				
			$(this).css("visibility","hidden");	
			id.find(".eLeft .obs").animate({"left":+(c.left-sValue*wValue)},300,function()
											{
												id.find(".eRight .eNxt").css("visibility","visible");
											});
		}	
	});
	$(this).find(".eRight .ePrv").on("click",function(){
		var c=id.find(".eLeft .obs").position();
		if(c.left<0)
		{	
			$(this).css("visibility","hidden");	
			id.find(".eLeft .obs").animate({"left":+(c.left+sValue*wValue)},300,function()
											{
												id.find(".eRight .ePrv").css("visibility","visible");
											});
		}
	});
	
}

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