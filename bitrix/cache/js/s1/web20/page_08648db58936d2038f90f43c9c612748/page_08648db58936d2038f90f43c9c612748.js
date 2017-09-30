
; /* Start:"a:4:{s:4:"full";s:94:"/bitrix/components/bitrix/photogallery.detail.list/templates/.default/script.js?14896645912556";s:6:"source";s:79:"/bitrix/components/bitrix/photogallery.detail.list/templates/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function __photo_change_template(anchor, package_id)
{
	var item_value = anchor.href.match(/template\=(\w+)/gi);
	if (!item_value) { return false; }
	item_value = item_value[0].replace("template=", ""); 
	if (window['__photo_buffer']) {
		for (var ii in window['__photo_buffer'])
		{
			if (ii.indexOf('sight') >= 0) {	window['__photo_buffer'][ii] = false; }
		}
	}
	__photo_change_template_data('template', item_value, package_id, {'template' : item_value});
	
	var nodes = anchor.parentNode.parentNode.getElementsByTagName('li');
	for (var ii = 0; ii < nodes.length; ii++)
	{
		nodes[ii].className = nodes[ii].className.replace(/\s[a-z\-]+/gi, '');
	}
	anchor.parentNode.className += ' ' + anchor.parentNode.className + '-active';
}

function __photo_change_template_data(item_name, item_value, package_id, params)
{
	if (!window['__photo_buffer']) { window['__photo_buffer'] = {}; }
	if (window['__photo_buffer'][item_name + item_value + package_id])
	{
		var div = document.getElementById("photo_list_" + package_id);
		div.innerHTML = window['__photo_buffer'][item_name + item_value + package_id];
		if (window.__photo_to_init_slider)
		{
			__photo_to_init_slider();
		}
		if (null != jsUserOptions)
		{
			if(!jsUserOptions.options)
				jsUserOptions.options = new Object();
			jsUserOptions.options['photogallery.template.' + item_name] = ['photogallery', 'template', item_name, item_value, false];
			jsUserOptions.SendData(null);
		}
		return true;
	}

	var TID = jsAjax.InitThread();
	eval("jsAjax.AddAction(TID, function(data){" + 
		"try { " + 
			"jsAjaxUtil.CloseLocalWaitWindow(TID, 'photo_list_" + package_id + "'); " + 
			"var index1 = data.indexOf('<!-- Photo List " + package_id + " -->'); " + 
			"var index2 = data.indexOf('<!-- Photo List End " + package_id + " -->'); " + 
			"var div = document.getElementById('photo_list_" + package_id + "'); " + 
			"if (index1 >= 0 && index2 >= 0 && div) {" + 
				" window['__photo_buffer']['" + item_name + item_value + package_id + "'] = div.innerHTML = data.substring(index1, index2); " + 
				" if (window.__photo_to_init_slider) { " +
					" __photo_to_init_slider(); " + 
				" }" + 
			"} " + 
		"} catch (e) {alert(e.message);}});");
	var url = window.location.href.replace(/PICTURES\_SIGHT\=(\w+)/gi, '').replace(/\#(.*)/gi, '').replace(/template\=(\w+)/gi, ''); 
	params = (params ? params : {});
	params['package_id'] = package_id;
	params['sessid'] = phpVars.bitrix_sessid;
	jsAjaxUtil.ShowLocalWaitWindow(TID, 'photo_list_' + package_id, true); 
	jsAjax.Send(TID, url, params);
}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/components/bitrix/photogallery.detail.list/templates/slider_big/script_cursor.js?14896645923719";s:6:"source";s:88:"/bitrix/components/bitrix/photogallery.detail.list/templates/slider_big/script_cursor.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function BPCMixer(field, cursor, gradation, params)
{
	this.field = field;
	this.cursor = cursor;
	params = (params ? params : {});
	this.minus = (params['minus'] ? params['minus'] : false);
	this.plus = (params['plus'] ? params['plus'] : false);
	this.events = (params['events'] ? params['events'] : {});

	this.gradation = (gradation > 2 ? gradation : 7);
	this.current = 1;
	
	var _this = this;
	
	this.field.onmousedown = function(e) {
		if(!e) { e = window.event; } 
		_this.SetCursor(e.clientX + document.body.scrollLeft, true);
	}; 
	this.cursor.onmousedown = function(e) {
		_this.StartDrag(e, this);}
	this.cursor.onclick = function(e) {
		return false;}
	if (this.minus)
	{
		this.minus.onclick = function(e){
			if (_this.current > 1)
				_this.SetCursor(_this.current - 1);
		}
	}
	if (this.plus)
	{
		this.plus.onclick = function(e){
			if (_this.current < _this.gradation)
				_this.SetCursor(_this.current + 1);
		}
	}
	
	this.Init = function()
	{
		if (!this.params)
			this.params = jsUtilsPhoto.GetElementParams(this.field);
		this.current = (this.current > 0 ? this.current : 1);
		if (!this.cursor_params)
			this.cursor_params = jsUtilsPhoto.GetElementParams(this.cursor);
		this.params['real_width'] = this.params['width'] - this.cursor_params['width'];
		this.grating_period_px = Math.round(this.params['real_width'] / (this.gradation - 1));
		this.grating_period = Math.round(100 / (this.gradation - 1));
	}
	
	this.StartDrag = function(e, div)
	{
		if(!e)
			e = window.event;
		this.x = e.clientX + document.body.scrollLeft;
		this.y = e.clientY + document.body.scrollTop;
		this.floatDiv = div;

		jsUtils.addEvent(document, "mousemove", this.MoveDrag);
		document.onmouseup = this.StopDrag;
		if(document.body.setCapture)
			document.body.setCapture();

		document.onmousedown = jsUtils.False;
		var b = document.body;
		b.ondrag = jsUtils.False;
		b.onselectstart = jsUtils.False;
		b.style.MozUserSelect = _this.floatDiv.style.MozUserSelect = 'none';
	}

	this.StopDrag = function(e)
	{
		if(document.body.releaseCapture)
			document.body.releaseCapture();

		jsUtils.removeEvent(document, "mousemove", _this.MoveDrag);
		document.onmouseup = null;

		this.floatDiv = null;

		document.onmousedown = null;
		var b = document.body;
		b.ondrag = null;
		b.onselectstart = null;
		b.style.MozUserSelect = _this.floatDiv.style.MozUserSelect = '';
		b.style.cursor = '';
	}

	this.MoveDrag = function(e)
	{
		var x = e.clientX + document.body.scrollLeft;
		_this.SetCursor(x, true);
	}
	
	this.SetCursor = function(position, need_calc)
	{
		this.Init();
		
		if (need_calc == true)
		{
			this.params = jsUtilsPhoto.ObjectsMerge(this.params, jsUtils.GetRealPos(this.field));
			position = parseInt(position);
			position = Math.round((position - this.params['left']) / this.grating_period_px) + 1; 
		}
		
		position = (position < 1 ? 1 : (position > this.gradation ? this.gradation : position));
		
		if (this.current != position)
		{
			this.current = position;
			
			this.checkEvent('BeforeSetCursor', this.current);
			
			if (this.current <= 1)
				this.cursor.style.left = '0';
			else
			{
				var position = Math.round(((this.params['real_width'] * (this.current - 1) / (this.gradation - 1)) / this.params['width']) * 100);
				this.cursor.style.left = position + '%';
			}
			this.cursor.parentNode.style.display = 'none'; 
			this.cursor.parentNode.style.display = 'block'; 
			this.checkEvent('AfterSetCursor', this.current);
		}
	}
	this.checkEvent = function()
	{
		eventName = arguments[0];
		if (this[eventName]) {return this[eventName](arguments); } 
		if (this.events[eventName]) {return this.events[eventName](arguments, this); } 
		return true;
	}
}
bPhotoCursorLoad = true;
/* End */
;; /* /bitrix/components/bitrix/photogallery.detail.list/templates/.default/script.js?14896645912556*/
; /* /bitrix/components/bitrix/photogallery.detail.list/templates/slider_big/script_cursor.js?14896645923719*/
