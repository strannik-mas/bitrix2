
; /* Start:"a:4:{s:4:"full";s:87:"/bitrix/templates/web20/components/bitrix/system.auth.form/auth/script.js?1489665082338";s:6:"source";s:73:"/bitrix/templates/web20/components/bitrix/system.auth.form/auth/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function ShowLoginForm()
{
	var div = document.getElementById("login-form-window");
	if (!div)
		return;
	div.style.display = "block";
	document.body.appendChild(div);
	return false;
}

function CloseLoginForm()
{
	var div = document.getElementById("login-form-window");
	if (!div)
		return;

	div.style.display = "none";
	return false;
}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:75:"/bitrix/templates/web20/components/bitrix/menu/tabs/script.js?1489665082430";s:6:"source";s:61:"/bitrix/templates/web20/components/bitrix/menu/tabs/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
var jshover = function() {
	var sfEls = document.getElementById("web-blue-tabs-menu").getElementsByTagName("li");
	for (var i=0; i<sfEls.length; i++) 
	{
		sfEls[i].onmouseover=function()
		{
			this.className+=" jshover";
		}
		sfEls[i].onmouseout=function() 
		{
			this.className=this.className.replace(new RegExp(" jshover\\b"), "");
		}
	}
	}

if (window.attachEvent) 
  window.attachEvent("onload", jshover);
/* End */
;; /* /bitrix/templates/web20/components/bitrix/system.auth.form/auth/script.js?1489665082338*/
; /* /bitrix/templates/web20/components/bitrix/menu/tabs/script.js?1489665082430*/
