TreeGlobals = {
	browser : {
		OPERA	: navigator.userAgent.indexOf("Opera") > 0,
		NS4		: typeof document.layers != "undefined",
		ICAB	: navigator.userAgent.toLowerCase().indexOf("icab") > 0,
		IE5		: navigator.userAgent.indexOf("MSIE 5") > 0 && !this.OPERA,
		MAC		: navigator.platform.indexOf("PPC") > 0,
		MAC_IE5	: this.IE5 && navigator.platform.indexOf("PPC") > 0
	},
	// don't change these
	inited : false
};

TreeParams = {
	OPEN_MULTIPLE_MENUS	: false,
	OPEN_MULTIPLE_SUBMENUS : false,
	
	TIME_DELAY			: TreeGlobals.browser.MAC_IE5 ? 20 : 30,
	TIME_DELAY_OPEN		: this.TIME_DELAY,
	TIME_DELAY_CLOSE	: this.TIME_DELAY,
	OPEN_WHILE_CLOSING	: true,

	OPEN_MENU_ICON		: "../images/services_vn.gif",
	CLOSED_MENU_ICON	: "../images/services_vn.gif"
};

var the_div;

function toggleMenu(HtmlLabel) {
	if(TreeGlobals.browser.OPERA || TreeGlobals.browser.NS4) return; 
	var label = TreeFunctions.getLabel(HtmlLabel);
	if(label.menu.blocked) return;
	var root = label.menu.root();
	if( !label.menu.isSubmenu && root.menuToOpen
		|| label.menu.isSubmenu && label.menu.parentMenu.menuToOpen != null) return;
	if(label.isDepressed) {
		if (root.activeMenu == label.menu
			|| label.menu.isSubmenu && label.menu.parentMenu.activeMenu == label.menu) {
			TreeFunctions.closeMenu(label.menu);
			if(!label.menu.isSubmenu)
				root.activeMenu = null;
			else
				label.menu.parentMenu.activeMenu = null;
		}
	}
	else{ // push it in.
		if(!label.menu.isSubmenu)
			root.menuToOpen = label.menu;
		else
			label.menu.parentMenu.menuToOpen = label.menu;
		if(label.icon != null) {
			if (label.icon.name!='') {
				label.icon.src = TreeParams.OPEN_MENU_ICON;
				if (label.icon.name.indexOf('fo')==-1) {
					the_div = eval("a"+label.icon.name.replace('lo', '')+"d");
					the_div.style.visibility='visible';
				}
			} else {
				label.icon.src = "../images/2arw-b.gif";
			}
			if (label.icon.name.indexOf('fo')==0) label.icon.src = "../images/2arw-b.gif";
		}
			
		if(TreeParams.OPEN_MULTIPLE_MENUS && !label.menu.isSubmenu
				|| root.activeMenu == null
				|| (label.menu.isSubmenu &&
					(TreeParams.OPEN_MULTIPLE_SUBMENUS
					|| label.menu.parentMenu.activeMenu == null))
				)
				TreeFunctions.openMenu(label.menu);
				
		else if(root.activeMenu != null && !label.menu.isSubmenu) {
		
					TreeFunctions.closeMenu(root.activeMenu);
					
					if(TreeParams.OPEN_WHILE_CLOSING)
						TreeFunctions.openMenu(label.menu);
					else
						root.activeMenu.menuInCue = label.menu;
		}
		
		else if(label.menu.isSubmenu) {
			if(TreeParams.OPEN_WHILE_CLOSING)
				TreeFunctions.closeMenu(label.menu.parentMenu.activeMenu, label.menu);
			else
				root.activeMenu.menuInCue = label.menu;
			TreeFunctions.openMenu(label.menu);
		}
		if(!label.menu.isSubmenu)
			root.activeMenu = label.menu;
		else
			label.menu.parentMenu.activeMenu = label.menu;
	}
}

function activateMenu(sButtonId){
	if(!window.toggleMenu)
		return;
	var button = document.getElementById(sButtonId);
	if(!button) return;
	toggleMenu(button.getElementsByTagName("span")[0]);
}

function buttonOver(htmlLabel){
	window.status = htmlLabel.parentNode.id;
	label = TreeFunctions.getLabel(htmlLabel);
	if(new RegExp("labelHover").test(label.htmlElement.className))
		return;
	label.htmlElement.className += " labelHover";
}

function buttonOff(label){
	window.status= window.defaultStatus;
	TreeUtils.removeClass(label, "labelHover");
}

if(typeof document.getElementsByTagName != "function"
  || TreeGlobals.browser.OPERA)
	buttonOver = buttonOff = function(){};

Button = function(htmlElement, category){
	this.htmlElement = htmlElement;
	this.category = category;
	this.menu = new Menu(document.getElementById(this.category +"Menu"), this);
	var icons = htmlElement.getElementsByTagName("img");
	this.icon = (icons.length > 0) ?
				icons[0] : null;
	this.isIcon = false;
	if(htmlElement.tagName == "IMG"){
		this.isIcon = true;
		this.icon = htmlElement;
	}
	
	this.isDepressed = false;
};

Menu = function(htmlElement, label) {
	this.ownerButton = label;
	this.id = label.category; // a short-cut reference to this.ownerButton.category.
	this.htmlElement = htmlElement;
	this.items = TreeUtils.getChildNodesWithClass(this.htmlElement, "div", "menuNode");
	this.allItems = TreeUtils.getElementsWithClass(this.htmlElement, "div", "menuNode");
	this.cur = 0;
	this.blocked = false;
	
	this.parentMenu = TreeUtils.findAncestorWithClass(label.htmlElement,"menu");
	this.isSubmenu = this.parentMenu != null;
	this._root = null;
	
	
	if(this.isSubmenu)
		this.parentMenu.menuToOpen = null;
	
	
	this.menuToOpen = null;
	this.activeMenu = null;
	this.menuInCue = null;
};

Menu.prototype = {

	open : function(){
	
		this.itemsToOpen[this.cur].style.display = "block";
		
		if(++this.cur == this.itemsToOpen.length)
			this.performActionEnd("block");
	},
	close : function(){
		this.itemsToClose[this.cur].style.display = "none";
		
		if(++this.cur == this.itemsToClose.length)
			this.performActionEnd("none");
	},
	performActionEnd : function(sDisplay) {
		
		this.htmlElement.style.display = sDisplay;
		this.performActionTimer = clearInterval(this.performActionTimer);
		
		if(sDisplay=='block')
			if(this.isSubmenu)
				this.parentMenu.menuToOpen = null;
			else TreeList[this.root().id].menuToOpen = null;
			
			
		else {
			TreeFunctions.setDefaultLabel(this.ownerButton);
			
			if(!TreeGlobals.OPEN_WHILE_CLOSING
				&& this.menuInCue != null)
				TreeFunctions.openMenu(this.menuInCue);
		}
		
		TreeUtils.repaintFix(this._root);
		this.blocked = false;
	},
	
	root : function(){
		
		if(this._root == null) {
			
			var rt = TreeUtils.findAncestorWithClass(this.htmlElement, "AnimTree");
			
			if(rt == null) 
				rt = document.body;
				
			if(!rt.id)
				rt.id = "AnimTree_"+ Math.round(Math.random() * 100);
				
			if(TreeList[rt.id] != null){
				this._root = TreeList[rt.id];
				this._root.menus[this.id] = this;
			}
			else
				this._root = new Tree(rt, this);
		}
		return this._root;
	}
};


Tree = function(htmlElement, menu) {
	this.htmlElement = htmlElement;
	this.activeMenu = null;
	this.menus[menu.id] = menu;
	this.menuToOpen = null;
	this.id = htmlElement.id;
	
	TreeList[this.id] = this;
 };

Tree.prototype.menus = new Object();

TreeList = {};


TreeFunctions = {

getLabel : function(htmlLabel) {
	
	var menuName = TreeUtils.findAncestorWithClass(htmlLabel,"button").id;
	var b;
	
	for(var tree in TreeList) 
		if(TreeList[tree].menus[menuName] != null)
			return TreeList[tree].menus[menuName].ownerButton;
		
	
	return new Button(htmlLabel, menuName);
	
},


initMenu : function(){
	if(document.getElementById && !TreeGlobals.browser.OPERA && !TreeGlobals.inited){
		
		document.writeln("<style type='text/css'>");
		document.writeln("/* <![CDATA[ */");
		document.write(".menu, .menuNode{display: none;}");
		document.writeln("/* ]]> */");
		document.write("<"+"/style>");
		
		TreeGlobals.inited = true;
	}
},

openMenu : function(menu){ // because menuToOpen may change,
	
	menu.blocked = true;
	menu.cur = 0;
	menu.itemsToOpen = new Array();
	
	menu.htmlElement.style.display = "block";
	
	if(menu.itemsToClose && menu.itemsToClose.length > menu.items.length)
			menu.itemsToOpen = menu.itemsToClose.reverse();
	
	else
		menu.itemsToOpen = menu.items;
	
	if(!menu.ownerButton.isIcon)
		menu.ownerButton.htmlElement.className += " labelDown";
		
		
	menu.performActionTimer = setInterval(
								"TreeList."+menu.root().id+".menus." + menu.id +".open()", 
								TreeParams.TIME_DELAY);
				
				
	menu.ownerButton.isDepressed = true;
	
	// ADD HERE 
},

closeMenu : function(menu) {
	
	menu.blocked = true;
	menu.cur = 0;
	menu.itemsToClose = new Array();
	
	for(var i = menu.allItems.length,counter = 0; i > 1; i--)
	
		if(menu.allItems[i-1].style.display == "block")
			menu.itemsToClose[counter++] = menu.allItems[i-1];
			
			
	menu.itemsToClose[menu.itemsToClose.length] = menu.htmlElement;
	
	
	menu.performActionTimer = setInterval(
									"TreeList."+menu.root().id+".menus." + menu.id +".close()", 
									TreeParams.TIME_DELAY);
					
	menu.ownerButton.isDepressed = false;
	
},

setDefaultLabel : function(button){
	
	if(button.isIcon)
		return void( button.icon.src = TreeParams.CLOSED_MENU_ICON);
	
	TreeUtils.removeClass(button.htmlElement, "labelHover");
	TreeUtils.removeClass(button.htmlElement, "labelDown");
	
	if(button.icon != null) {
		if (button.icon.name!='') {
			button.icon.src = TreeParams.CLOSED_MENU_ICON;
			if (button.icon.name.indexOf('fo')==-1) {
				the_div = eval("a"+button.icon.name.replace('lo', '')+"d");
				the_div.style.visibility='hidden';
			}
		} else {
			button.icon.src = "../images/2arw.gif";
		}
		if (button.icon.name.indexOf('fo')==0) button.icon.src = "../images/2arw.gif";
	}
}

};

TreeFunctions.initMenu();


/* <>--<>--<>--<>--<>--<>-- UTILITY FUNCTIONS --<>--<>--<>--<>--<>--<> */

TreeUtils = {

getChildNodesWithClass : function(parent, tagName, klass){
	
	var collection;
	var returnedCollection = [];
	var collection = parent.childNodes;
	
	for(var i = 0, counter = 0; i < collection.length; i++){
		if(!collection[i].className
		|| collection[i].tagName.toUpperCase() != tagName.toUpperCase())
			continue;
		
		if( collection[i].className.test(klass, " "))
			returnedCollection[counter++] = collection[i];
	}
	return returnedCollection;
},

getElementsWithClass : function(parent, tagName, klass){
	var collection;
	var returnedCollection = [];
	
	if(parent.all && tagName == "*") collection = parent.all;
	else collection = parent.getElementsByTagName(tagName);
	for(var i = 0, counter = 0; i < collection.length; i++){
		
		if(collection[i].className != null
			&& collection[i].className.test(klass, " "))
			returnedCollection[counter++] = collection[i];
	}
	return returnedCollection;
},

findAncestorWithClass : function(el, klass){
	
	for(var parent = el.parentNode;parent != null;){
	
		if( parent.className != null && parent.className.test(klass, " "))
			return parent;
			
		parent = parent.parentNode;
	}
	return null;
},

removeClass : function(el, klass){
	
	var newClass = "";
	var list = el.className.split(" ");
	
	for(var i = 0; i < list.length; i++)
		if(list[i] != klass)
			newClass += list[i] + " ";
	el.className = newClass.normalize();
	
},

repaintFix : function(tree){
	
	if(!tree.activeMenu) return;
	
	tree.activeMenu.htmlElement.style.visibility = "hidden";
	tree.activeMenu.htmlElement.style.visibility = "";
}

};


String.prototype.test = function(inp, delim){
	var exps = getTokenizedExps(inp, delim);
	return( exps.global.test(this) || exps.ends.test(this) );
};

function getTokenizedExps(inp, delim) {

	return {
		global : new RegExp(delim+inp+delim, "\g"),
		ends : new RegExp("^"+inp+delim+"|^"+inp+"$|"+delim+inp+"$", "\g")
	};
}

String.prototype.trim = function(){
		return this.replace(/^\s+|\s+$/g, "");
};
String.prototype.normalize = function(){
		return this.trim().replace(/\s\s+/g, " ");
};