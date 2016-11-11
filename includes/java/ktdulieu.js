
function isNuber(c)
{
	return (c>="0" && c<="9");
}
function isNubers(str)
{
var isOK=true;
for(var i=0;i<str.length;i++)
	{
		if(!isNumer(str.charAt(i)))
			{
				isOK=false;
				break;
			}
	}
	return isOK;	
}

function isEmail(s)
	{   
		if (s=="")
			return false;
		if(s.indexOf(" ")>0)
			return false;
		if(s.indexOf("@")==-1)
			return false;
		var i = 1;
		var sLength = s.length;
		if (s.indexOf(".")==-1)
			return false;
		if (s.indexOf("..")!=-1)
			return false;
		if (s.indexOf("@")!=s.lastIndexOf("@"))
			return false;
		if (s.lastIndexOf(".")==s.length-1)
			return false;
		return true;
}

function isEmpty(s)
{   
	return ((s == null) || (s.length == 0))
}

function isWhitespace (s)
{   
	var whitespace = " \t\n\r";
	var i;

  if (isEmpty(s)) return true;
  for (i = 0; i < s.length; i++)
  {   
    var c = s.charAt(i);
    if (whitespace.indexOf(c) == -1) return false;
  }
  return true;
}

function laMatKhau(s)
{   
	return ((s != null) && (s.length > 5))
}
function isYear(argYear) {
	if ((isWhiteSpace(argYear)) || (argYear.toString().length > 4) || (argYear.toString().length == 3))
		return false
	
	switch (argYear.toString().length) {
		case 1:
			if (argYear >=0 && argYear < 10)
				return true
			else
				return false
			
		case 2:
			if (argYear >=0 && argYear < 100)
				return true
			else
				return false
			
		case 4:
			if (((argYear >=1900) || (argYear >=2000)) && ((argYear < 3000) || (argYear < 2000)))
				return true
			else
				return false
		
		default:
			return false
	}
}

