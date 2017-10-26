/* BEGIN: GLOBAL */

// Global/framework.js
//
// Base framework for all Web Applications
//
// ©2016 Xojo Inc. -- All Rights Reserved 
// This code contains patent-pending technology.

// Array Support
if (typeof Array.prototype.indexOf != "function") {
	Array.prototype.indexOf = function (obj) {
		var i;
		for (i = 0; i < this.length; i++) {
			if (this[i] == obj) {
				return i;
			}
		}
		return -1;
	};
}

if(typeof String.prototype.trim != "function") {
	String.prototype.trim = function() {
		var str = this.replace(/(^\s*)|(\s*$)/g,"");
		var end = str.length - 1;
		var ws = /\s/;
		
		while(ws.test(str.charAt(end))) {
			end --;
		}
		return str.slice(0,end+1);
	}
}

// RGBA Support
function testRGBASupport ()
{
	var element = document.createElement("a");
	try {
		element.style.backgroundColor = 'rgba(0,0,0,0)';
		return true;
	} catch (err) {
		return false;
	}
}

// Xojo Namespace
var Xojo = {
	version: 6,
	apiVersionNum: 6,
  mode: 1,
	begin: function () {},
	init: function () {},
	getElementID: function () {},
	postLoadObjects: [],
	wheelTargets: [],
	refreshObjects: [],
	imageCache: [],
	controls: [],
	loaders: [],
	pageWindows: [],
	styleSheetLookups: [],
	changedFields: [],
	holdInterval: null,
	holdOffset: null,
	dragTarget: null,
	dragCallback: null,
	activeLoader: null,
	pageID: null,
	pageFinishedLoading: false,
	appURL: '',
	sessionURL: '',
	sessionID: '',
	refreshLocks: 0,
	serializer: null,
	rgbaSupported: testRGBASupport(),
	opacitySupported: true,
	stopInit: false,
	platform: -1,
	engine: -1,
	browser: -1,
	interactionAllowed: true,
	view: {},
	comm: {},
	menus: {},
	input: {
		begin: function () {},
		move: function () {},
		end: function () {},
		enter: function () {},
		exit: function () {},
		install: function () {},
		isTouchUI: function () {},
		getCoordinates: function () {},
		refireMove: function () {},
		forwardEvent: function () {},
		mouseEventData: function () {},
		doubleClick: function() {},
		keyDown: function () {},
		keyUp: function () {},
		keyPress: function () {},
		keyQueue: [],
		setFocusControl: function () {},
		mIsTouchUI: false,
		target: null,
		holdInterval: 0,
		lastCoordinates: [],
		lastEvent: null,
		setLastEvent: function () {},
		installedElements: [],
		pointerTarget: null,
		focusControl: null,
		touchHandler: function() {}
	},
	control: {},
	console: {
		error: function () {},
		log: function () {},
		present: function () {},
		dismiss: function () {},
		isVisible: function () {},
		submit: function () {},
		mIsVisible: false
	},
	events: {
		addListener: function () {},
		removeListener: function () {},
		preventDefault: function () {},
		stopPropagation: function () {},
		fireEvent: function() {},
		fireSessionTimeout: function() {},
		sessionTimeoutReset: function() {},
		setSessionTimeout: function() {},
		sessionTimeoutTimer: null
	},
	DOM: {
		addClass: function () {},
		removeClass: function () {},
		getAppliedStyle: function () {},
		getArrayOfClassNames: function () {},
		hasClass: function () {},
		get: function() {},
		getElementsByClassName: function() {}
	},
	utils: {
		toCamelCase: function() {},
		toHyphens: function() {},
		tabCapture: function() {}
	},
	ua: { }
};

Xojo.postLoadCleanup = function() {
	postLoadObjects = [];
}

// Communication functions
Xojo.comm = {
	begin: function () {
		try {
			if(Xojo.comm.websockets.enabled == true && !!window.WebSocket && Xojo.mode == 1) {
				Xojo.comm.websockets.didConnect = false;
				var schema = 'ws://';
				if(Xojo.secure) { schema = 'wss://'; }
				var host = schema + window.location.host + '/' + Xojo.sessionID + "/comm/push";
				var sock = new WebSocket(host);
				sock.onopen = function () {
					clearTimeout(Xojo.comm.websockets.timeout);
					Xojo.comm.websockets.didConnect = true;
					Xojo.comm.sendOpen();
					//Set up a heartbeat for the WebSocket
					Xojo.comm.websockets.heartbeat = setInterval(Xojo.comm.ajax.ping,10000);
				};
				sock.onerror = function (e) {
					if(Xojo.comm.websockets.socket.readyState > 1) {
						clearTimeout(Xojo.comm.websockets.heartbeat);
						clearTimeout(Xojo.comm.websockets.timeout);
						setTimeout(Xojo.comm.ajax.begin,100);
					}
				};
				sock.onclose = function () {
					if(Xojo.comm.websockets.didConnect) {
						//if it connected before, try again.
						Xojo.comm.begin(); 				
					} else {	
						clearTimeout(Xojo.comm.websockets.heartbeat);
						clearTimeout(Xojo.comm.websockets.timeout);
						setTimeout(Xojo.comm.ajax.begin,100);
					}
				};
				sock.onmessage = function (event) {
					clearTimeout(Xojo.comm.websockets.timeout);
					if(event.data!="") { 
						Xojo.comm.processResponse(event.data);
					}
				};
				Xojo.comm.websockets.socket = sock;
				//timeout for browsers using new websocket protocol w/ no fallback
				Xojo.comm.websockets.socket.timeout = setTimeout(Xojo.comm.websockets.socket.onerror,3000); 
			} else {
				setTimeout(Xojo.comm.ajax.begin,100);
			}
		} catch (wsErr) {
			setTimeout(Xojo.comm.ajax.begin,100);
		}
	},
	sendOpen: function () {
		if (Xojo.comm.inited === false) {
			Xojo.view.setLoaderState(2);
			var currentDate = new Date();
			var offset;
			if (currentDate.getTimeZoneOffset) {
				offset = currentDate.getTimeZoneOffset();
			} else {
				offset = currentDate.getTimezoneOffset();
				offset = offset / 60;
			}
			offset = offset * -1;
			
			var dimensions = Xojo.view.sizing.dimensions();
			
			var mydata = {};
			mydata.width = dimensions.width;
			mydata.height = dimensions.height;
			mydata.gmtOffset = offset;
			mydata.urlHash = window.location.hash;
			mydata.pixelDensity = window.devicePixelRatio || 1;
			
			var userdata = [JSON.stringify(mydata)];
			
			Xojo.comm.triggerEvent('Event','Open',userdata);
			Xojo.comm.inited = true;
			Xojo.view.monitorHashTag(window.location.hash);
			Xojo.view.monitorDPR(window.devicePixelRatio);
		}
	},
	triggerEvent: function (control,event,userdata,ev) {
		var url = Xojo.sessionURL + '/comm/event/';
		if ((control !== null) && (control !== '')) {
			url = url + control + '.' + event;
		}
		
		if ((!userdata) || (userdata.constructor.toString().indexOf('Array') === -1)) {
			userdata = [];
		}
		
		for(i=0;i<userdata.length;i++) {
			userdata[i] = encodeURIComponent(userdata[i]);
		}
		
		var queries = prepareFormData(Xojo.changedFields);
		if(userdata.length>0) {
			queries.push('params=' + encodeURIComponent(userdata.join('&')));
		}
		Xojo.changedFields = [];
		
		if (Xojo.comm.websockets.connected() === true) {
			queries.push('url=' + url);
			queries.push('WSCookies=' + document.cookie);
			Xojo.comm.websockets.socket.send(queries.join('&'));
		} else {
			var xhr;
			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				Xojo.console.error("This browser does not support AJAX.");
				return;
			}
		
			xhr.open('POST',url,true);
			xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			xhr.setRequestHeader('Cache-Control','no-cache');
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					if (this.status === 200) {
						Xojo.comm.processResponse(this.responseText);
					} else if (this.status === 404) {
						Xojo.console.log("Requested content was not found.");
					} else {
						Xojo.console.log("HTTP Error: " + this.statusText);
					}
				}
			};
			xhr.send(queries.join('&'));
		}
	},
	processResponse: function (response) {
		var obj;
		try {
			if(response==="") response = "{}";
			obj = JSON.parse(response);
		} catch (jsonErr) {
			Xojo.console.error("Trouble evaluating response: " + jsonErr.message + "\nSource: " + response);
			return null;
		}
		
		// update the html
		if(obj.html) {
			var c = obj.html.length;
			var i = 0;
			var parent, target, o;
			for (i = 0; i < c; i++) {
				o = obj.html[i];
				
				parent = document.getElementById(o.parent);
				target = document.createElement('div');
				target.innerHTML = o.source;
				target = target.children[0];
				
				if (parent) {
					switch (o.mode) {
					case '1': // source should replace parent content
						parent.innerHTML = o.source;
						break;
					case '2': // source should be appended to parent content
						parent.appendChild(target);
						break;
					case '3': // source should be prepended to parent content
						parent.insertBefore(target,parent.firstChild);
						break;
					case '4': // parent should be removed
						if (parent.parentNode) {
							parent.parentNode.removeChild(parent);
						}
						Xojo.cleanControlArrays();

						break;
					case '5': // source should replace parent
						if (parent.parentNode) {
							var destination = parent.parentNode;
							destination.removeChild(parent);
							destination.appendChild(target);
						}
						break;
					}
				}
			}
		}
		
		// insert css
		if(obj.cssSource) {
			var css = obj.cssSource;
			var cssID = obj.cssID || "";
			createStyleSheet(css,cssID);
		}
		// execute the returned javascript
		if (obj.jsSource) {
			Xojo.view.refresh.lock();
			try {
				eval(obj.jsSource);
			} catch (jsErr) {
				Xojo.console.error("Could not execute returned javascript: " + jsErr.message + "\nSource: " + obj.jsSource);
			}
			Xojo.view.refresh.unlock();
		}
		
		// done
		didFinishLoading();
		
		// execute post-load
		var object;
		for(i=0;i<Xojo.postLoadObjects.length;i++) {
			try {
				object = Xojo.postLoadObjects[i];
				object.postLoad();
			} catch(err) { }
		}
		
		setTimeout(Xojo.postLoadCleanup,10);
		
		return obj;
	},
	ajax: {
		begin: function () {
			Xojo.comm.sendOpen();
			
			if ((Xojo.comm.websockets.connected() === true) || (Xojo.comm.ajax.xhr !== null)) {
				return;
			}
			
			if(Xojo.ua.mobile=="iOS" && EventSource) {
				Xojo.eventsource = new EventSource(Xojo.sessionURL + '/comm/serverevent');
				Xojo.eventsource.msgcount = 5; //Allow for some initial errors during connection
				Xojo.eventsource.onmessage = function (event) {
					Xojo.comm.processResponse(event.data);
					Xojo.eventsource.msgcount = 5;
				};
				Xojo.eventsource.onerror = function (event) {
					Xojo.eventsource.msgcount--;
					var evcnt = Xojo.eventsource.msgcount;
					if (Xojo.eventsource.msgcount < 1) {
						Xojo.view.preventInteraction();
					}
				};
			} else {
				var xhr;
				if (window.XMLHttpRequest) {
					xhr = new XMLHttpRequest();
				} else {
					return;
				}
				
				xhr.open('POST',Xojo.sessionURL + '/comm/push',true);
				xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
				xhr.setRequestHeader('Cache-Control','no-cache');
				xhr.onreadystatechange = function () {
					if (this.readyState === 4) {
						Xojo.comm.ajax.xhr = null;
						if (this.status === 200) {
							Xojo.comm.processResponse(this.responseText);
						} else {
							setTimeout(Xojo.comm.ajax.ping,3000);
						}
					}
				};
				xhr.send();
				Xojo.comm.ajax.xhr = xhr;
			}
		},
		end: function (gracefully) {
			if (gracefully === null) {
				gracefully = false;
			}
			if (Xojo.comm.ajax.xhr) {
				Xojo.comm.ajax.xhr.abort();
				Xojo.comm.ajax.xhr = null;
				if (gracefully === false) {
					preventPageInteraction();
				}
			}
		},
		ping: function () {
			//If we're connected via websockets, send a ping there...
			if(Xojo.comm.websockets.connected()===true) {
				var url = Xojo.sessionURL + '/comm/ping';
				var queries = [];
				queries.push('url=' + url);
				queries.push('WSCookies=' + document.cookie);
				Xojo.comm.websockets.socket.send(queries.join('&'));
				return;
			}
			var xhr;
			if (window.XMLHttpRequest) {
				xhr = new XMLHttpRequest();
			} else {
				return;
			}
			
			xhr.open('POST',Xojo.sessionURL + '/comm/ping',true);
			xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			xhr.setRequestHeader('Cache-Control','no-cache');
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					if (this.status === 200) {
						Xojo.comm.processResponse(this.responseText);
					} else {
						Xojo.view.preventInteraction();
					}
				}
			};
			xhr.send();
		},
		xhr: null
	},
	websockets: {
		connected: function () {
			if ((Xojo.comm.websockets.socket !== null) && (Xojo.comm.websockets.socket.readyState === 1)) {
				return true;
			} else {
				return false;
			}
		},
		socket: null,
		enabled: false
	},
	terminate: function () {
		if (Xojo.comm.websockets.connected() === true) {
			Xojo.comm.websockets.socket.onclose = function () {};
			Xojo.comm.websockets.socket.close();
		}
		if (Xojo.comm.ajax.xhr !== null) {
			Xojo.comm.ajax.xhr.abort();
			Xojo.comm.ajax.xhr = null;
		}
		Xojo.view.preventInteraction();
	},
	serverMonitor: function() {
		var xhr = new XMLHttpRequest();
		xhr.onload = Xojo.comm.serverMonitorLoad;
		xhr.onerror = Xojo.comm.serverMonitorError;
		xhr.open("GET","xojo/ping", true);
		xhr.send();
	},
	serverMonitorLoad: function(ev) {
		switch(this.status) {
		case 200:
			//good, now lets reload
			location.reload();
			break;
		default:
		  // Wait 5 seconds and try again
			setTimeout(Xojo.comm.serverMonitor, 5000);
			break;
		}
	},
	serverMonitorError: function(ev) {
		  // Wait 5 seconds and try again
			setTimeout(Xojo.comm.serverMonitor, 5000);
	},
	inited: false
};

// View Control
Xojo.view = {
	showOverlay: function () {
		if (Xojo.view.overlayCount == 0) {
			var overlay = document.getElementById('XojoOverlay');
			if (overlay) {
				overlay.style.display = 'block';
			}
			
			Xojo.view.preventScrolling();
		}
		Xojo.view.overlayCount = Xojo.view.overlayCount + 1;
	},
	hideOverlay: function () {
		if (Xojo.view.overlayCount <= 0) {
			return;
		}
		
		Xojo.view.overlayCount = Xojo.view.overlayCount - 1;
		
		if (Xojo.view.overlayCount == 0) {
			var overlay = document.getElementById('XojoOverlay');
			if (overlay) {
				overlay.style.display = 'none';
			}
			
			Xojo.view.allowScrolling();
		}
	},
	overlayCount: 1,
	preventScrolling: function () {
		Xojo.menus.dismissAllMenus();
		if (Xojo.view.scrollPreventionCount === 0) {
			var container = document.getElementById('XojoContainer');
			if (container) {
				container.style.minWidth = '100%';
				container.style.minHeight = '100%';
			}
		}
		Xojo.view.scrollPreventionCount = Xojo.view.scrollPreventionCount + 1;
	},
	allowScrolling: function () {
		Xojo.menus.dismissAllMenus();
		if (Xojo.view.scrollPreventionCount <= 0) {
			return;
		}
		
		Xojo.view.scrollPreventionCount = Xojo.view.scrollPreventionCount - 1;
		
		if (Xojo.view.scrollPreventionCount === 0) {
			var container = document.getElementById('XojoContainer');
			var page = document.getElementById(Xojo.view.currentPage);
			if ((container) && (page)) {
				container.style.minWidth = page.style.minWidth;
				container.style.minHeight = page.style.minHeight;
			}
		}
	},
	scrollPreventionCount: 1,
	dismissLoader: function () {
		try { clearTimeout(loaderTimeout); } catch(err) { }
		Xojo.view.setLoaderState(3);
		document.getElementById('XojoLoader').style.top = '100%';
		setTimeout("document.getElementById('XojoLoader').style.display = 'none'",100);
		setTimeout(Xojo.view.hideOverlay,101);
	},
	setLoaderState: function (state) {
		var bar = document.getElementById('XojoLoaderBar');
		bar.style.backgroundPosition = '0px -' + (state * 21) + 'px';
	},
	showPage: function (pageID) {
		if (Xojo.view.currentPage !== '') {
			var el = document.getElementById(Xojo.view.currentPage);
			if (el) {
				el.style.display = 'none';
			}
			Xojo.comm.triggerEvent(Xojo.view.currentPage,'Hidden');
		}
		
		var page = document.getElementById(pageID);
		if(page) {
			var container = document.getElementById('XojoPages');
			container.style.minWidth = page.style.minWidth;
			container.style.minHeight = page.style.minHeight;
			
			var container = document.getElementById('XojoContainer');
			container.style.minWidth = page.style.minWidth;
			container.style.minHeight = page.style.minHeight;
			
			page.style.left = '0';
			page.style.display = '';
		}
		Xojo.view.currentPage = pageID;
		Xojo.view.sizing.notifyServer();
		setTimeout("Xojo.comm.triggerEvent('" + pageID + "','Shown')",1);
	},
	setStatus: function (message) {
		try {
			window.status = message;
		} catch (statErr) {
		}
	},
	setHashTag: function (hash) {
		Xojo.view.lastHashTag = hash;
		window.location.hash = hash;
	},
	monitorHashTag: function (startingTag) {
		Xojo.view.lastHashTag = startingTag;
		
		var fn = function () {
			if (window.location.hash != Xojo.view.lastHashTag) {
				Xojo.view.lastHashTag = window.location.hash;
				Xojo.comm.triggerEvent('Event','HashTagChanged',[window.location.hash]);
			}
		};
		if("onhashchange" in window) {
			Xojo.events.addListener(window,"hashchange",fn,false);
		} else {
			setInterval(fn,250);
		}
	},
	monitorDPR: function (startingValue) {
		Xojo.view.devicePixelRatio = startingValue;
		
		var fn = function () {
			var dpr = window.devicePixelRatio || 1;
			if (dpr != Xojo.view.devicePixelRatio) {
				Xojo.view.devicePixelRatio = dpr;
				var data = {pixelDensity: Xojo.view.devicePixelRatio};
				Xojo.comm.triggerEvent('Event','pixelRatioChanged',[JSON.stringify(data)]);
				// Go through all of the controls and trigger refreshes
				for(i=0;i<Xojo.controls.length;i++) {
					Xojo.controls[Xojo.controls[i]].scaleFactorChanged();
				}
			}
		};
		setInterval(fn,500);
	},
	preventInteraction: function () {
		// Turn off the disconnect confirmation dialog (the user is disconnected already)
		Xojo.view.confirmationText = null;
		// Fire the Session disconnect event
		var currentPageEl = document.getElementById("XojoSession");
		if(currentPageEl) {
			Xojo.events.fireEvent(currentPageEl, "disconnect");
		}
		Xojo.view.showOverlay();
		if(Xojo.eventsource) { Xojo.eventsource.close(); delete Xojo.eventsource; }
		var blocker = document.getElementById('XojoDisconnect');
		if ((blocker) && (blocker.style.display !== 'block')) {
			blocker.style.display = 'block';
			blocker.style.zIndex = Xojo.DOM.getMaxZ() + 10;
			setTimeout("document.getElementById('XojoDisconnect').style.top = '0%'",10);
			
			//Stop any controls that continue to cause events to fire
			for(var i=0;i<Xojo.controls.length;i++) {
				var ctl = Xojo.controls[Xojo.controls[i]];
				try { if(ctl.constructor == timer) { ctl.setMode(0); }} catch(err) { }
				try { if(ctl.constructor == movieplayer) {
						var id = ctl.controlID;
						var el = document.getElementById(id);
						el.parentNode.removeChild(el);  }} catch(err) { }
				try { if(ctl.constructor == spinner) { ctl.setEnabled(false); }} catch(err) { }
			}			
		}
		
		// Start looking for the server to return
		Xojo.comm.serverMonitor();
	},
	allowInteraction: function () {
		var blocker = document.getElementById('XojoDisconnect');
		if ((blocker) && (blocker.style.display === 'block')) {
			blocker.style.top = '100%';
			setTimeout("document.getElementById('XojoDisconnect').style.display = 'none'",10);
			setTimeout(Xojo.view.hideOverlay,11);
		}
	},
	handleConfirmation: function (event) {
		if ((Xojo.view.confirmationText !== null) && (Xojo.view.confirmationText !== '')) {
			var r = Xojo.view.confirmationText;
			event = event || window.event;
			if (event !== null) {
				event.returnValue = r;
			}
			return r;
		}
	},
	confirmationText: null,
	currentPage: '',
	lastHashTag: '',
	sizing: {
		resized: function () {
			Xojo.menus.dismissAllMenus();
			if (Xojo.view.sizing.notifyDelay !== 0) {
				clearTimeout(Xojo.view.sizing.notifyDelay);
			}
			Xojo.view.sizing.notifyControls(false);
			Xojo.view.sizing.notifyDelay = setTimeout(Xojo.view.sizing.notifyServer,250);
		},
		rotated: function () {
			Xojo.view.sizing.notifyServer();
			if(window.orientation !== undefined) {
				Xojo.comm.triggerEvent('Event','OrientationChanged',[window.orientation]);
			}
		},
		notifyControls: function (complete) {
			if (Xojo.stopInit === true) {
				return;
			}
			
			var object, i;
			Xojo.view.refresh.lock();
			for (i = 0; i < Xojo.view.sizing.targets.length; i++) {
				object = Xojo.view.sizing.targets[i];
				object.resize();
				if (complete === true) {
					object.resizeComplete();
				}
			}
			Xojo.view.refresh.unlock();
		},
		notifyServer: function () {
			Xojo.view.sizing.notifyControls(true);
			
			var userdata = [];
			var d = Xojo.view.sizing.dimensions();
			userdata.push(d.width);
			userdata.push(d.height);
			Xojo.comm.triggerEvent('Event','Resized',userdata);
		},
		dimensions: function () {
			var view = document.getElementById('XojoContainer');
			return { width : view.offsetWidth , height : view.offsetHeight };
		},
		notifyDelay: 0,
		targets: []
	},
	refresh: {
		lock: function () {
			Xojo.view.refresh.lockCount = Math.max(Xojo.view.refresh.lockCount,0) + 1;
		},
		unlock: function (caller) {
			if (Xojo.view.refresh.lockCount <= 0) {
				return;
			}
			Xojo.view.refresh.lockCount = Xojo.view.refresh.lockCount - 1;
			
			if (Xojo.view.refresh.lockCount === 0) {
				var i,obj;
				for (i = 0; i < Xojo.view.refresh.targets.length; i++) {
					obj = Xojo.view.refresh.targets[i];
					var el = document.getElementById(obj.controlID);
					if(el) {
						if ((obj.refresh) && (obj != caller)) {
							obj.refresh();
						}
					}
				}
				Xojo.view.refresh.targets = [];
			}
		},
		locked: function () {
			if (Xojo.view.refresh.lockCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		lockCount: 0,
		targets: []
	}
};

Xojo.menus = {
	create: function (menu,depth) {
		if (typeof(depth) === 'undefined') {
			depth = -1;
		}
		
		var action = function (event) {
			event = event || window.event;
			Xojo.events.preventDefault(event);
			Xojo.events.stopPropagation(event);
			
			if (Xojo.menus.mouseDownItem === null) {
				return;
			}
			
			var menuItem;
			if (this.id) {
				menuItem = this;
			} else if ((event.srcElement) || (event.target)) {
				menuItem = event.srcElement || event.target;
			} else {
				Xojo.menus.mouseDownItem = null;
				return;
			}
			var menuID = menuItem.id.substring(0,10);
			menuItem = document.getElementById(menuID + '_item');
			
			if ((menuItem.className == 'item') && (menuItem === Xojo.menus.mouseDownItem)) {
				if (Xojo.menus.items.indexOf(menuID) === -1) {
					if (Xojo.menus.menuTarget !== null) {
						Xojo.menus.menuTarget.menuItemSelected(menuID);
					}
					Xojo.menus.dismissAllMenus();
				}
			}
		};
		
		var mouseDown = function (event) {
			event = event || window.event;
			Xojo.events.preventDefault(event);
			Xojo.events.stopPropagation(event);
			
			var menuItem;
			if (this.id) {
				menuItem = this;
			} else if ((event.srcElement) || (event.target)) {
				menuItem = event.srcElement || event.target;
			} else {
				return;
			}
			var menuID = menuItem.id.substring(0,10);
			Xojo.menus.mouseDownItem = document.getElementById(menuID + '_item');
		};
		
		var item = document.getElementById(menu.menuID + '_item');
		if (!item) {
			item = document.createElement('li');
			item.id = menu.menuID + '_item';
			
			Xojo.events.addListener(item,'mousedown',mouseDown,false);
			Xojo.events.addListener(item,'contextmenu',action,false);
		}
		
		if (menu.text === '-') {
			item.className = 'separator';
			return item;
		}
		
		var caption;
		if (item.children.length < 1) {
			caption = document.createElement('span');
			caption.id = menu.menuID + "_caption";
			Xojo.events.addListener(caption,'mousedown',mouseDown,false);
			Xojo.events.addListener(caption,'contextmenu',action,false);
			item.appendChild(caption);
		} else {
			caption = item.children[0];
		}
		
		if (menu.children.length > 0) {
			Xojo.menus.items[menu.menuID] = menu;
			Xojo.menus.items.push(menu.menuID);
			
			caption.className = 'submenu';
			
			var shell = document.getElementById(menu.menuID);
			if (!shell) {
				shell = document.createElement('div');
				shell.id = menu.menuID;
				shell.style.display = 'none';
				
				var pointer = document.createElement('img');
				pointer.id = menu.menuID + '_pointer';
				pointer.className = 'pointer';
				pointer.src = '/framework/spacer.gif';
				Xojo.events.addListener(pointer,'contextmenu',Xojo.input.contextClick,false);
				shell.appendChild(pointer);
				
				document.getElementById('XojoMenus').appendChild(shell);
			}
			var ul = document.getElementById(menu.menuID + '_inner');
			if (!ul) {
				ul = document.createElement('ul');
				ul.id = menu.menuID + '_inner';
				ul.className = 'menu';
				ul.style.display = 'inline-block';
				shell.appendChild(ul);
			} else {
				if (ul.hasChildNodes()) {
					while (ul.childNodes.length >= 1) {
						ul.removeChild(ul.firstChild);
					}
				}
			}
			
			var i;
			for (i = 0; i < menu.children.length; i++) {
				ul.appendChild(Xojo.menus.create(menu.children[i],depth + 1));
			}
			
			var showMenu = function (event) {
				event = event || window.event;
				Xojo.events.preventDefault(event);
				Xojo.events.stopPropagation(event);
				
				var menuItem;
				if (this.id) {
					menuItem = this;
				} else if ((event.srcElement) || (event.target)) {
					menuItem = event.srcElement || event.target;
				} else {
					return;
				}
				var menuID = menuItem.id.substring(0,10);
				menuItem = document.getElementById(menuID + '_item');
				
				var pos = getPosition(menuItem);
				var r = new Rect(pos.x,pos.y,menuItem.offsetWidth,menuItem.offsetHeight);
				var x = r.right;
				var edge = 1;
				var width = document.getElementById('XojoContainer').offsetWidth - 40;
				if (width - r.right < r.width()) {
					x = r.left;
					edge = 3;
				}
				Xojo.menus.displayMenuForPoint(menuItem.id.substring(0,10),x,r.verticalCenter(),edge,depth + 1);
			};
			Xojo.events.addListener(item,'mouseover',showMenu,false);
		} else {
			var hideMenus = function (event) {
				event = event || window.event;
				Xojo.events.preventDefault(event);
				Xojo.events.stopPropagation(event);
				
				Xojo.menus.dismissAllMenus(depth + 1);
			};
			Xojo.events.addListener(item,'mouseover',hideMenus,false);
		}
		
		if (menu.enabled === true) {
			Xojo.events.addListener(item,'mouseup',action,false);
			item.className = 'item';
		} else {
			item.className = 'disabled';
		}
		while (caption.firstChild !== null) {
			caption.removeChild(caption.firstChild);
		}
		caption.appendChild(document.createTextNode(menu.text));
		
		return item;
	},
	remove: function (menuID) {
	},
	findSuitableEdgeForRect: function (originRect, menuRect) {
		var dimensions = Xojo.view.sizing.dimensions();
		var bottomSpace = dimensions.height - originRect.bottom;
		var rightSpace = dimensions.width - originRect.right;
		var leftSpace = originRect.left;
		var topSpace = originRect.top;
		
		if (topSpace >= menuRect.height() + 20) {
			return 0;
		}
		if (bottomSpace >= menuRect.height() + 20) {
			return 2;
		}
		if (rightSpace >= menuRect.width() + 20) {
			return 1;
		}
		if (leftSpace >= menuRect.width() + 20) {
			return 3;
		}
		
		return 4;
	},
	displayMenuForRect: function (menuID, rect, mouseX, mouseY, depth) {
		if (Xojo.menus.visibleItems.length === 0) {
			var container = document.getElementById('XojoMenus');
			container.style.visibility = 'hidden';
			container.style.display = 'block';
		}
		var menu = document.getElementById(menuID);
		if(menu) {
			menu.style.display = 'inline-block';
			menu.style.zIndex = Xojo.DOM.getMaxZ();
			var ul = document.getElementById(menuID + '_inner');
			var menuRect = new Rect(0,0,ul.offsetWidth,ul.offsetHeight)
			var edge = Xojo.menus.findSuitableEdgeForRect(rect,menuRect);
		
			switch (edge) {
				case 0:
					Xojo.menus.displayMenuForPoint(menuID,rect.horizontalCenter(),rect.top,edge,depth);
					break;
				case 1:
					Xojo.menus.displayMenuForPoint(menuID,rect.right,rect.verticalCenter(),edge,depth);
					break;
				case 2:
					Xojo.menus.displayMenuForPoint(menuID,rect.horizontalCenter(),rect.bottom,edge,depth);
					break;
				case 3:
					Xojo.menus.displayMenuForPoint(menuID,rect.left,rect.verticalCenter(),edge,depth);
					break;
				case 4:
					if ((mouseX === null) || (mouseY === null)) {
						Xojo.menus.displayMenuForPoint(menuID,rect.horizontalCenter(),rect.verticalCenter(),edge,depth);
					} else {
						Xojo.menus.displayMenuForPoint(menuID,mouseX,mouseY,-1,depth);
					}
					break;
			}
		}
	},
	displayMenuForPoint: function (menuID, x, y, edge, depth) {
		if (typeof(depth) === 'undefined') {
			depth = 0;
		}
		Xojo.menus.dismissAllMenus(depth);
		
		var container = document.getElementById('XojoMenus');
		var menu = document.getElementById(menuID);
		var pointer = document.getElementById(menuID + '_pointer');
		var menuInner = document.getElementById(menuID + '_inner');
		if ((container) && (menu) && (menuInner) && (pointer)) {
		} else {
			return;
		}
		container.style.visibility = 'hidden';
		container.style.display = 'block';
		menu.style.display = 'inline-block';
		container.style.visibility = 'visible';
		menu.style.zIndex = Xojo.DOM.getMaxZ();
		var menuRect = new Rect(0,0,menuInner.offsetWidth,menuInner.offsetHeight);
		
		switch (edge) {
			case -1:
				var originRect = new Rect(x - 1,y - 1,2,2);
				edge = Xojo.menus.findSuitableEdgeForRect(originRect,menuRect);
				break;
			case 5:
				var rightSpace = container.offsetWidth - x;
				if (rightSpace > menuRect.width()) {
					edge = 1;
				} else {
					edge = 3;
				}
				break;
			case 6:
				var bottomSpace = container.offsetHeight - y;
				if (bottomSpace > menuRect.height()) {
					edge = 2;
				} else {
					edge = 0;
				}
				break;
		}
		
		if (x < 35) {
			edge = 1;
		} else if (x > container.offsetWidth - 35) {
			edge = 3;
		}
		
		var menuOuter,pointerRect,diff;
		
		menu.style.position = 'absolute';
		switch (edge) {
			case 0:
				menuOuter = new Rect(x - Math.floor(menuRect.width() / 2),y - (menuRect.height() + 20),menuRect.width(),menuRect.height() + 20);
				pointerRect = new Rect(Math.floor(menuRect.width() / 2) - 20,menuRect.height(),40,20);
				
				if (menuOuter.left < 10) {
					diff = 10 - menuOuter.left;
					menuOuter.offset(diff,0);
					pointerRect.offset(diff * -1,0);
				} else if (menuOuter.right > container.offsetWidth - 10) {
					diff = menuOuter.right - (container.offsetWidth - 10);
					menuOuter.offset(diff * -1,0);
					pointerRect.offset(diff,0);
				}
				pointerRect.left = Math.min(Math.max(pointerRect.left,5),menuRect.width() - 45);
				pointerRect.right = pointerRect.left + 40;
				
				menu.style.top = menuOuter.top + 'px';
				menu.style.left = menuOuter.left + 'px';
				menu.style.visibility = 'visible';
				menuInner.style.top = '0px';
				menuInner.style.left = '0px';
				pointer.width = '40';
				pointer.height = '20';
				pointer.style.top = pointerRect.top + 'px';
				pointer.style.left = pointerRect.left + 'px';
				pointer.style.backgroundPosition = '0px 20px';
				
				break;
			case 1:
				menuOuter = new Rect(x,y - Math.floor(menuRect.height() / 2),menuRect.width() + 20,menuRect.height());
				pointerRect = new Rect(0,Math.floor(menuRect.height() / 2) - 20,20,40);
				
				if (menuOuter.top < 10) {
					diff = 10 - menuOuter.top;
					menuOuter.offset(0,diff);
					pointerRect.offset(0,diff * -1);
				} else if (menuOuter.bottom > container.offsetHeight - 10) {
					diff = menuOuter.bottom - (container.offsetHeight - 10);
					menuOuter.offset(0,diff * -1);
					pointerRect.offset(0,diff);
				}
				pointerRect.top = Math.min(Math.max(pointerRect.top,5),menuRect.height() - 45);
				pointerRect.bottom = pointerRect.top + 40;
				
				menu.style.top = menuOuter.top + 'px';
				menu.style.left = menuOuter.left + 'px';
				menu.style.visibility = 'visible';
				menuInner.style.top = '0px';
				menuInner.style.left = '20px';
				pointer.width = '20';
				pointer.height = '40';
				pointer.style.top = pointerRect.top + 'px';
				pointer.style.left = pointerRect.left + 'px';
				pointer.style.backgroundPosition = '0px 0px';
				break;
			case 2:
				menuOuter = new Rect(x - Math.floor(menuRect.width() / 2),y,menuRect.width(),menuRect.height() + 20);
				pointerRect = new Rect(Math.floor(menuRect.width() / 2) - 20,0,40,20);
				
				if (menuOuter.left < 10) {
					diff = 10 - menuOuter.left;
					menuOuter.offset(diff,0);
					pointerRect.offset(diff * -1,0);
				} else if (menuOuter.right > container.offsetWidth - 10) {
					diff = menuOuter.right - (container.offsetWidth - 10);
					menuOuter.offset(diff * -1,0);
					pointerRect.offset(diff,0);
				}
				pointerRect.left = Math.min(Math.max(pointerRect.left,5),menuRect.width() - 45);
				pointerRect.right = pointerRect.left + 40;
				
				menu.style.top = menuOuter.top + 'px';
				menu.style.left = menuOuter.left + 'px';
				menu.style.visibility = 'visible';
				menuInner.style.top = '20px';
				menuInner.style.left = '0px';
				pointer.width = '40';
				pointer.height = '20';
				pointer.style.top = pointerRect.top + 'px';
				pointer.style.left = pointerRect.left + 'px';
				pointer.style.backgroundPosition = '0px 0px';
				break;
			case 3:
				menuOuter = new Rect(x - (menuRect.width() + 20),y - Math.floor(menuRect.height() / 2),menuRect.width() + 20,menuRect.height());
				pointerRect = new Rect(menuRect.width(),Math.floor(menuRect.height() / 2) - 20,20,40);
				
				if (menuOuter.top < 10) {
					diff = 10 - menuOuter.top;
					menuOuter.offset(0,diff);
					pointerRect.offset(0,diff * -1);
				} else if (menuOuter.bottom > container.offsetHeight - 10) {
					diff = menuOuter.bottom - (container.offsetHeight - 10);
					menuOuter.offset(0,diff * -1);
					pointerRect.offset(0,diff);
				}
				pointerRect.top = Math.min(Math.max(pointerRect.top,5),menuRect.height() - 45);
				pointerRect.bottom = pointerRect.top + 40;
				
				menu.style.top = menuOuter.top + 'px';
				menu.style.left = menuOuter.left + 'px';
				menu.style.visibility = 'visible';
				menuInner.style.top = '0px';
				menuInner.style.left = '0px';
				pointer.width = '20';
				pointer.height = '40';
				pointer.style.top = pointerRect.top + 'px';
				pointer.style.left = pointerRect.left + 'px';
				pointer.style.backgroundPosition = '20px 0px';
				break;
			case 4:
				menuOuter = new Rect(x - Math.floor(menuRect.width() / 2),y - Math.floor(menuRect.height() / 2),menuRect.width(),menuRect.height());
				
				menu.style.top = menuOuter.top + 'px';
				menu.style.left = menuOuter.left + 'px';
				menu.style.visibility = 'visible';
				menuInner.style.top = '0px';
				menuInner.style.left = '0px';
				pointer.style.display = 'none';
				break;
		}
		Xojo.menus.visibleItems.push(menuID);
	},
	dismissMenu: function (menuID) {
		var idx = Xojo.menus.visibleItems.indexOf(menuID);
		if (idx === -1) {
			return;
		}
		
		var menu = document.getElementById(menuID);
		menu.style.display = 'none';
		Xojo.menus.visibleItems.splice(idx,1);
		
		if (Xojo.menus.visibleItems.length === 0) {
			var container = document.getElementById('XojoMenus');
			container.style.display = 'none';
			container.style.visibility = 'visible';
			Xojo.menus.menuTarget = null;
		}
	},
	dismissAllMenus: function (depth) {
		if (typeof(depth) === 'undefined') {
			depth = 0;
		}
		while (Xojo.menus.visibleItems.length > depth) {
			Xojo.menus.dismissMenu(Xojo.menus.visibleItems[depth]);
		}
	},
	items: [],
	visibleItems: [],
	mouseDownItem: null,
	menuTarget: null,
	action: 'context'
};

// Library Functions
Xojo.begin = function (sessionID)
{
	if (Xojo.stopInit === true) {
		return;
	}
	
	Xojo.sessionID = sessionID;
	Xojo.appURL = '';
	Xojo.sessionURL = Xojo.appURL + "/" + Xojo.sessionID;
	
	try {
		var test = document.createElement('div');
		var touchEventName = 'ontouchstart';
		Xojo.input.mIsTouchUI = (touchEventName in test);
		if (!Xojo.input.mIsTouchUI) {
			test.setAttribute(touchEventName,'return');
			Xojo.input.mIsTouchUI = typeof test[touchEventName] == 'function';
		}
		test = null;
	} catch (touchErr) {
		Xojo.input.mIsTouchUI = false;
	}
	
	if(Xojo.input.mIsTouchUI) {
		document.getElementById("XojoMenus").onmousedown = "";
	}
	
	document.onmousemove = Xojo.input.move;
	document.onmouseup = Xojo.input.end;
	document.onkeydown = Xojo.input.keyDown;
	document.onkeyup = Xojo.input.keyUp;
	document.onkeypress = Xojo.input.keyPress;
	window.onresize = Xojo.view.sizing.resized;
	
	Xojo.events.addListener(document,'mousewheel', trackMouseWheel, false);
	Xojo.events.addListener(window,'mousewheel', trackMouseWheel, false);
	Xojo.events.addListener(window,'DOMMouseScroll', trackMouseWheel, false);
		
	window.onbeforeunload = Xojo.view.handleConfirmation;
	
	Xojo.events.addListener(document.body,'contextmenu',Xojo.input.contextClick,false);
	Xojo.events.addListener(window,'blur',function () { Xojo.menus.dismissAllMenus(0) },false);
	
	cacheImage('/framework/appicon128.png',true);
	cacheImage('/framework/pagestop.png',true);
	cacheImage('/framework/dimmer.png',true);
	cacheImage('/framework/pointer.png',true);
	
	cacheImage('/framework/appicon256.png',true);
	cacheImage('/framework/pagestop@2x.png',true);
	cacheImage('/framework/dimmer@2x.png',true);
	cacheImage('/framework/pointer@2x.png',true);
	
	setTimeout('Xojo.view.setLoaderState(1)',0);
	var commDelay = 100;
	if(Xojo.ua.mobile=="Android") { commDelay = 1000; };
	setTimeout(Xojo.comm.begin,commDelay);
	if(window.orientation !== undefined) {
		Xojo.comm.triggerEvent('Event','OrientationChanged',[window.orientation]);
	}
	
	Xojo.view.sizing.resized();
};

Xojo.cleanControlArrays = function() {
 var j;
 //Control array
 for(j = 0;j<Xojo.controls.length;j++) {
    if(Xojo.controls[j].object == undefined || Xojo.controls[j].object() == null) {
	  Xojo.controls.splice(j,1);
      j--;
    }
  }
  //wheelTargets
  for(j = 0;j<Xojo.wheelTargets.length;j++) {
    if(Xojo.wheelTargets[j].object == undefined || Xojo.wheelTargets[j].object() == null) {
	  Xojo.wheelTargets.splice(j,1);
      j--;
    }
  }
  //PostLoadObjects
  for(j=0;j<Xojo.postLoadObjects.length;j++) {
  	if(Xojo.postLoadObjects[j].object == undefined || Xojo.postLoadObjects[j].object() == null) {
	  Xojo.postLoadObjects.splice(j,1);
      j--;
    }
  }
};

Xojo.refreshControl = function(controlID) {
	try {
		var el = Xojo.controls[controlID];
		if(el) { el.refresh(); }
	} catch(err) { }
};

function didFinishLoading (pageID)
{
	var i, controlID, control;
	for (i = 0; i < Xojo.controls.length; i++) {
		control = Xojo.controls[Xojo.controls[i]];
		if (control.hasFinishedLoading === false) {
			control.didFinishLoading();
			control.hasFinishedLoading = true;
		}
	}
}

function getPosition (e)
{ 
	var left = 0; 
	var top  = 0; 
	var margin = 0;
	if(e) {
		while (e.offsetParent){ 
			left += e.offsetLeft - Math.max(0,e.scrollLeft);
			margin = parseInt(Xojo.DOM.getAppliedStyle(e,"margin-left"));
			if(isNaN(margin)) margin = 0;
			left += Math.max(0,margin);
			margin = parseInt(Xojo.DOM.getAppliedStyle(e,"border-left-width"));
			if(isNaN(margin)) margin = 0
			left += margin;
			left += parseInt(Xojo.DOM.getAppliedStyle(e,"padding-left"));
			
			
			top += e.offsetTop - Math.max(0,e.scrollTop);
			margin = parseInt(Xojo.DOM.getAppliedStyle(e,"margin-top"));
			if(isNaN(margin)) margin = 0;
			top += Math.max(0,margin);
			margin = parseInt(Xojo.DOM.getAppliedStyle(e,"border-top-width"));
			if(isNaN(margin)) margin = 0
			top += margin;
			top += parseInt(Xojo.DOM.getAppliedStyle(e,"padding-top"));
			
			e = e.offsetParent;
		} 
		
		left += e.offsetLeft; 
		top += e.offsetTop; 
	}
	return {x:left, y:top};
}

function mouseCoords (ev)
{
	ev = ev || window.event;
	if (!ev) {
		return {x:0, y:0};
	}
	if (ev.pageX || ev.pageY) {
		return {x:ev.pageX, y:ev.pageY};
	}
	return {x:ev.clientX + document.body.scrollLeft - document.body.clientLeft, y: ev.clientY + document.body.scrollTop - document.body.clientTop};
}

function getMouseOffset (target, ev)
{
	ev = ev || window.event;
	var docPos = getPosition(target);
	var mousePos = mouseCoords(ev);
	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function prepareFormData (fields)
{
	var queries = [];
	var element;
	var v;
	for (var i = 0; i < fields.length; i++) {
		element = fields[i];
		if (element && element.object()) {
			v = encodeURIComponent(element.value());
			queries.push(element.name() + '=' + v);
		}
	}
	return queries;
}

function addPostLoadObject (object)
{
	Xojo.postLoadObjects.push(object);
}

function getPageDimensions ()
{
	var page = document.getElementById(Xojo.view.currentPage);
	return { width : page.offsetWidth , height : page.offsetHeight };
}

function addWheelTarget (target)
{
	Xojo.wheelTargets.push(target);
}

function preventEventDefault(ev)
{
	if (ev) {
		if (ev.preventDefault) {
			ev.preventDefault();
		}
		ev.returnValue = false;
	}
}

function trackMouseWheel (ev)
{
	var deltaX = 0;
	var deltaY = 0;
	if (!ev) {
		ev = window.event;
	}
	if ((ev.wheelDeltaX !== undefined) && (ev.wheelDeltaY !== undefined)) {
		deltaX = ev.wheelDeltaX / 120;
		deltaY = ev.wheelDeltaY / 120;
		if (!window.opera) {
			deltaX = deltaX * -1;
			deltaY = deltaY * -1;
		}
	} else if (ev.wheelDelta) {
		deltaY = ev.wheelDelta / 120;
		if (!window.opera) {
			deltaY = deltaY * -1;
		}
	} else if (ev.detail) {
		deltaY = (ev.detail / 3);
	}
	
	if ((deltaX === 0) && (deltaY === 0)) {
		return;
	}
	
	var i, target, pos, mousePos, handled;
	
	mousePos = mouseCoords(ev);
	handled = false;
	//get the list of wheeltarget ids
	var wheelTargetIDs = [];
	for (i=0;i<Xojo.wheelTargets.length;i++) {
		try {
			wheelTargetIDs[wheelTargetIDs.length] = Xojo.wheelTargets[i].controlID;
		} catch(err) { }
	}
	
	//Now lets look at the hierarchy to see if the mouse is actually scrolling in one of those controls
	var el = ev.target;
	while(el && (el.id == "" || (el.id != "" && wheelTargetIDs.indexOf(el.id) < 0))) {
		el = el.parentNode;
	}
	
	//If we still have an item that can be scrolled, do that
	if(el) {
		target = Xojo.controls[el.id];
		if(target.object()) {
			pos = getPosition(target.object());			
			if ((handled === false) && (mousePos.x >= pos.x) && (mousePos.x <= pos.x + target.object().offsetWidth) && (mousePos.y >= pos.y) && (mousePos.y <= pos.y + target.object().offsetHeight)) {
				if (target.enabled()) {
					handled = target.mouseWheel(deltaX, deltaY);
				}
			}
		} else {
			Xojo.wheelTargets.splice(i,1);
			i--;
		}
	}
		
	if ((handled === true) && (ev)) {
		Xojo.events.preventDefault(ev);
	}
}

function findChildrenByClass (parent, className)
{
	var results = Array();
	if(parent) {
		var children = parent.children;
		var i;
		for (i = 0; i < children.length; i++) {
			if (Xojo.DOM.hasClass(children.item(i),className)) {
				results.push(children.item(i));
			}
		}
	}
	return results;
}

function isRefreshingLocked ()
{
	return (Xojo.refreshLocks > 0);

}

function cacheImage (url,useCache)
{
	if(useCache==undefined) useCache = true;
	var img,i,s;
	if(useCache) {
		for (i = 0; i < Xojo.imageCache.length; i++) {
			s = Xojo.imageCache[i].src;
			s = s.substring(s.length - url.length);
			if (s === url) {
				img = Xojo.imageCache[i];
				break;
			}
		}
	}
	
	if (img == null) {
		img = new Image();
		img.onload = function() {
			var index = Xojo.imageCache.indexOf(this);
			if(index !== -1) {
				Xojo.imageCache.splice(index,1);
			}
		}
		Xojo.imageCache.push(img);
		img.src = url;
		img.loaded = false;
	} else {
		img.loaded = true;
	}
	
	return img;
}

// Compatibility

function outerHTML (element)
{
	try {
		if (Xojo.serializer === null) {
			Xojo.serializer = new XMLSerializer();
		}
		return Xojo.serializer.serializeToString(element);
	} catch (err) {
		return element.outerHTML;
	}
}

function createRandomString (length)
{
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var s = "";
	var i,r;
	for (i = 0; i < length; i++) {
		r = Math.floor(Math.random() * chars.length);
		s += chars.substring(r,r + 1);
	}
	return s;
}

// Style Manipulation

function createStyleSheet (source,id)
{
	if (source == "") { return; }
	var sheet;
	var oldsheet = document.getElementById(id);
	if(!oldsheet || id == "") {
		sheet = document.createElement("style");
		sheet.type = "text/css";
		sheet.rel = "stylesheet";
		sheet.media = "all";
		if(id!==undefined && id!=="") sheet.id = id;
	} else {
		sheet = oldsheet;
	}

	try {
		sheet.innerHTML = source;
	} catch (err) {
		try {
			sheet.styleSheet.cssText = source;
		} catch (err) {
			sheet.style.cssText = source;
		}
	}
	if(!oldsheet) {
		document.getElementsByTagName("head")[0].appendChild(sheet);
	}
	return sheet;
}

function markControlChanged (field)
{
	var i;
	for (i = 0; i < Xojo.changedFields.length; i++) {
		if (Xojo.changedFields[i] == field) {
			return;
		}
	}
	Xojo.changedFields.push(field);
}

// Input Support (Mouse, Fingers, etc)

Xojo.input.isTouchUI = function ()
{
	return Xojo.input.mIsTouchUI;
};

Xojo.input.install = function (element)
{
	try {
		if (Xojo.input.installedElements.indexOf(element.id) == -1) {
			Xojo.events.addListener(element,"mousedown",Xojo.input.begin,false);
			Xojo.events.addListener(element,"mouseover",Xojo.input.enter,false);
			Xojo.events.addListener(element,"mouseout",Xojo.input.exit,false);
			Xojo.events.addListener(element,"dblclick",Xojo.input.doubleClick,false);
			Xojo.input.installedElements.push(element.id);
		}
	} catch(err) {
	}
};

Xojo.input.begin = function (event)
{
	var id = '';
	if (this.id) {
		id = this.id.substring(0,8);
	} else if ((event.srcElement) || (event.target)) {
		var element = event.srcElement || event.target;
		while (((element.id === '') || (Xojo.controls[element.id] == undefined)) && (element.offsetParent !== null)) {
			element = element.offsetParent;
		}
		id = element.id.substring(0,8);
	}
	if ((id === '') || (!event)) {
		return;
	}
	
	var control = Xojo.controls[id];
	if (!control) {
		return;
	}
	
	return Xojo.input.forwardEvent(event, control);
};

Xojo.input.move = function (event)
{
	event = event || window.event;
	if (Xojo.input.target !== null) {
		Xojo.input.setLastEvent(Xojo.input.target.controlObject(),event);
		if (Xojo.input.lastCoordinates.length > 0) {
			preventEventDefault(event);
		}
		return;
	} else if (Xojo.input.pointerTarget !== null) {
		var control = Xojo.input.pointerTarget;
		Xojo.input.setLastEvent(control.controlObject(),event);
		if (Xojo.input.lastCoordinates.length > 0) {
			if (control.implementedEvents.indexOf('MouseMove') > -1) {
				Xojo.comm.triggerEvent(control.controlID,'MouseMove',Xojo.input.mouseEventData(event,Xojo.input.lastCoordinates),event);
			}
			var handled = control.mouseMove(event, Xojo.input.lastCoordinates);
			if (handled === true) {
				preventEventDefault(event);
			}
		}
		return;
	}
};

Xojo.input.end = function (event)
{
	if (!event) {
		event = Xojo.input.lastEvent;
	}
	if (Xojo.input.target === null) {
		return;
	}
	if (Xojo.input.holdInterval > 0) {
		clearInterval(Xojo.input.holdInterval);
		Xojo.input.holdInterval = 0;
	}
	
	if (Xojo.input.lastCoordinates.length > 0) {
		if (Xojo.input.target.implementedEvents.indexOf('MouseUp') > -1) {
			Xojo.comm.triggerEvent(Xojo.input.target.controlID,'MouseUp',Xojo.input.mouseEventData(event,Xojo.input.lastCoordinates),event);
		}
		var handled = Xojo.input.target.touchEnd(event, Xojo.input.lastCoordinates);
		if (handled === true) {
			try {
			preventEventDefault(event);
			} catch(e) { }
		}
	}
	Xojo.input.target = null;
	Xojo.input.lastCoordinates = [];
};

Xojo.input.enter = function (event)
{
	if (Xojo.input.pointerTarget !== null) {
		return;
	}
	var id = '';
	if (this.id) {
		id = this.id.substring(0,8);
	} else if ((event.srcElement) || (event.target)) {
		var element = event.srcElement || event.target;
		while (((element.id === '') || (Xojo.controls[element.id] == undefined)) && (element.offsetParent !== null)) {
			element = element.offsetParent;
		}
		id = element.id.substring(0,8);
	}
	if ((id === '') || (!event)) {
		return;
	}
	
	var control = Xojo.controls[id];
	if (!control) {
		return;
	}
	
	Xojo.input.pointerTarget = control;
	Xojo.input.setLastEvent(control.controlObject(),event);
	if (Xojo.input.lastCoordinates.length > 0) {
		var el = control.object();
		if(el) {
			var pos = getPosition(el);
			var mc = mouseCoords(event);
			if(!control.mouseWithin && mc.x >= pos.x && mc.x <= (pos.x + el.offsetWidth) && mc.y >= pos.y && mc.y <= (pos.y + el.offsetHeight)) {
				control.mouseWithin = true;
				if (control.implementedEvents.indexOf('MouseEnter') > -1) {
					Xojo.comm.triggerEvent(control.controlID,'MouseEnter',null,event);
				}
			}
			var handled = control.mouseEnter(event);
			if (handled === true) {
				preventEventDefault(event);
			}
		}
	}
};

Xojo.input.exit = function (event)
{
	if (Xojo.input.pointerTarget === null) {
		return;
	}
	
	var control = Xojo.input.pointerTarget;
	
	Xojo.input.setLastEvent(control.controlObject(),event);
	if (Xojo.input.lastCoordinates.length > 0) {
		var el = control.object();
		if(el) {
			var pos = getPosition(el);
			var mc = mouseCoords(event);
			if(control.mouseWithin && (mc.x < pos.x || mc.x > (pos.x + el.offsetWidth) || mc.y < pos.y || mc.y > (pos.y + el.offsetHeight))) {
				control.mouseWithin = false;
				if (control.implementedEvents.indexOf('MouseExit') > -1 && Xojo.view.currentPage != control.controlID) {
					Xojo.comm.triggerEvent(control.controlID,'MouseExit',null,event);
				}
			}
			var handled = control.mouseExit(event);
			if (handled === true) {
				preventEventDefault(event);
			}
		}
	}
	
	Xojo.input.pointerTarget = null;
};

Xojo.input.doubleClick = function (event)
{
	var id = '';
	if (this.id) {
		id = this.id.substring(0,8);
	} else if ((event.srcElement) || (event.target)) {
		var element = event.srcElement || event.target;
		while (((element.id === '') || (Xojo.controls[element.id] == undefined)) && (element.offsetParent !== null)) {
			element = element.offsetParent;
		}
		id = element.id.substring(0,8);
	}
	if ((id === '') || (!event)) {
		return;
	}
	
	var control = Xojo.controls[id];
	if (!control) {
		return;
	}
	
	//Check to see if the event is in a scrollbar on a listbox
	if(Xojo.DOM.hasClass(event.currentTarget,'scrollbar') && control.constructor == listbox) {
		return;
	}
	
	Xojo.input.setLastEvent(control.controlObject(),event);
	if (Xojo.input.lastCoordinates.length > 0) {
		if (control.implementedEvents.indexOf('DoubleClick') > -1) {
			Xojo.comm.triggerEvent(control.controlID,'DoubleClick',Xojo.input.mouseEventData(event,Xojo.input.lastCoordinates),event);
		}
		var handled = control.doubleClick(event);
		if (handled === true) {
			preventEventDefault(event);
		}
	}
};

Xojo.input.getCoordinates = function (target, event)
{
	var results = [];
	var result;
	var offset = {x: 0, y: 0};
	if (target) {
		offset = getPosition(target);
	}
	if (event.touches !== undefined && event.touches.length>0) {
		for (var i = 0; i < event.touches.length; i++) {
			result = {pageX: event.touches[i].pageX, pageY: event.touches[i].pageY};
			result.x = result.pageX - offset.x;
			result.y = result.pageY - offset.y;
			results.push(result);
		}
	} else {
		if (event.pageX !== undefined) {
			result = {pageX: event.pageX, pageY: event.pageY};
		} else {
			result = {
				pageX: event.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft),
				pageY: event.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)
			};
		}
		result.x = result.pageX - offset.x;
		result.y = result.pageY - offset.y;
		results.push(result);
	}
	return results;
};

Xojo.input.refireMove = function ()
{
	if (Xojo.input.target === null) {
		return;
	}
	if (Xojo.input.lastCoordinates.length > 0) {
		if (Xojo.input.target.implementedEvents.indexOf('MouseDrag') > -1) {
			Xojo.comm.triggerEvent(Xojo.input.target.controlID,'MouseDrag',Xojo.input.mouseEventData(event,Xojo.input.lastCoordinates),event);
		}
		Xojo.input.target.touchMove(null, Xojo.input.lastCoordinates);
	}
};

Xojo.input.forwardEvent = function (event, control)
{
	Xojo.input.target = control;
	Xojo.input.setLastEvent(control.controlObject(),event);
	var r = false;
	if (Xojo.input.lastCoordinates.length > 0) {
		if (control.implementedEvents.indexOf('MouseDown') > -1) {
			Xojo.comm.triggerEvent(control.controlID,'MouseDown',Xojo.input.mouseEventData(event,Xojo.input.lastCoordinates),event);
		}
		var handled = control.touchBegin(event, Xojo.input.lastCoordinates);
		if (handled === true) {
			r = true;
			preventEventDefault(event);
		} 
		if(!handled || !Xojo.input.isTouchUI()) {
			if(control.mEnabled) {
				handled = control.touchMove(event, Xojo.input.lastCoordinates);
			}
			if (handled === true) {
				Xojo.input.holdInterval = setInterval(Xojo.input.refireMove,10);
			}
		}
	}
	return r;
};

Xojo.input.mouseEventData = function (event, coordinates)
{
	var userdata = [];
	
	userdata.push(coordinates.length);
	for (var i = 0; i < coordinates.length; i++) {
			userdata.push(coordinates[i].pageX);
			userdata.push(coordinates[i].pageY);
		}
	var btn = event.button;
	//IE9+ has different button numbers
	if(Xojo.ua.ie >= 9) {
		switch(btn) {
			case 0: btn = 1; break;
			case 1: btn = 3; break;
		}
	}
	userdata.push(btn);
	
	return userdata;
};

Xojo.input.keyDown = function (event)
{
	event = event || window.event;
	var handled = false;
	var charCode = (event.charCode) ? event.charCode : ((event.which) ? event.which : 0);
	var keyName = (event.key) ? event.key : String.fromCharCode(charCode);
	var props = [event.keyCode,charCode,keyName,event.shiftKey,event.ctrlKey,event.altKey];
	if(event.metaKey) props[props.length] = event.metaKey;
	if (Xojo.input.focusControl !== null) {
		handled = Xojo.input.focusControl.keyDown(event);
		if(event.keyCode == 46 || event.keyCode == 8) {
			//Make sure Backspace and Delete keys fire KeyPressed event (WebKit)
			Xojo.input.handleKey(event,"KeyPressed");
		}
	}
};

Xojo.input.keyPress = function (event) {
	event = event || window.event;
	var charCode = (event.charCode) ? event.charCode : ((event.which) ? event.which : 0);
	if(!(event.keyCode == 8 || event.keyCode == 46)) { //Don't let keyPress fire for Delete or Backspace
		if(Xojo.input.handleKey(event,"KeyPressed")) {
			return true;
		}
	}
}

Xojo.input.keyUp = function (event)
{
	event = event || window.event;
	try {
		if (event.keyCode == 13) {
			//Don't fire a keyUp event for Return/Enter
			Xojo.events.stopPropagation(event);
			Xojo.events.preventDefault(event);
			return;
		}
	} catch(err) {}
	var charCode = (event.charCode) ? event.charCode : ((event.which) ? event.which : 0);
	var keyName = (event.key) ? event.key : String.fromCharCode(charCode);
	if(keyName.length !== 1 && keyName.indexOf("U+")==-1) {
		return Xojo.input.handleKey(event,"KeyPressed");
	}
};

Xojo.input.handleKey = function(event, eventName) {
	var handled = false;
	var charCode = (event.charCode) ? event.charCode : ((event.which) ? event.which : 0);
	var keyName = (Xojo.input.lastKey !== "" && Xojo.input.lastKey !== undefined) ? Xojo.input.lastKey : ((event.key) ? event.key : String.fromCharCode(charCode));
	var keyCode = (event.keyCode > 0) ? event.keyCode : charCode;
	if (event.modifiers) {
		event.shiftKey = (event.shiftKey || (event.modifiers && 4));
		event.altKey = (event.altKey || (event.modifiers && 1));
		event.ctrlKey = (event.ctrlKey || (event.modifiers && 2));
	}
	var props = [keyCode,charCode,keyName,event.shiftKey,event.ctrlKey,event.altKey];
	if(event.metaKey) props[props.length] = event.metaKey;
	if (Xojo.input.focusControl !== null) {
		handled = Xojo.input.focusControl.keyUp(event);
		if ((handled !== true) && (Xojo.input.focusControl.implementedEvents.indexOf('KeyPressed') > -1)) {
			setTimeout(function() { Xojo.comm.triggerEvent(Xojo.input.focusControl.controlID,'KeyPressed',props,event); },0);
			handled = true;
		}
	}
	try {
		if ((handled !== true) && (Xojo.controls[Xojo.view.currentPage].implementedEvents.indexOf('KeyPressed') > -1)) {
			setTimeout(function() { Xojo.comm.triggerEvent(Xojo.view.currentPage,'KeyPressed',props,event); },0);
			handled = true;
		}
	} catch(ex) { }
	return handled;
}

Xojo.input.setFocusControl = function (control)
{
	if(Xojo.input.focusControl !== null) {
		if(Xojo.input.focusControl.implementedEvents.indexOf('LostFocus') > -1) {
			Xojo.comm.triggerEvent(Xojo.input.focusControl.controlID,'LostFocus',"",null);
		}
		if(typeof Xojo.input.focusControl.lostFocus === "function") {
			Xojo.input.focusControl.lostFocus();
		}
	}
	Xojo.input.focusControl = control;
	if(Xojo.input.focusControl !== null) {
		if(Xojo.input.focusControl.implementedEvents.indexOf('GotFocus') > -1) {
			Xojo.comm.triggerEvent(Xojo.input.focusControl.controlID,'GotFocus',"",null);
		}
		if(typeof Xojo.input.focusControl.gotFocus === "function") {
			Xojo.input.focusControl.gotFocus();
		}
	}
};

Xojo.input.setLastEvent = function (relObject,event) {
	if(event) {
		Xojo.input.lastCoordinates = Xojo.input.getCoordinates(relObject,event);
		Xojo.input.lastEvent = event;
	}
};

Xojo.input.touchHandler = function (event) {
    var touches = event.changedTouches,
        first = touches[0],
        type = "";
         switch(event.type)
    {
        case "touchstart": type="mousedown"; break;
        case "touchmove":  type="mousemove"; break;        
        case "touchend":   type="mouseup"; break;
        default: return;
    }

             //initMouseEvent(type, canBubble, cancelable, view, clickCount, 
    //           screenX, screenY, clientX, clientY, ctrlKey, 
    //           altKey, shiftKey, metaKey, button, relatedTarget);
    
    //Find a control that has an id that we recognize
    var target = first.target;
    var ctl = Xojo.controls[target.id];
    while(target && ctl==undefined) {
    	target = target.parentNode;
    	ctl = Xojo.controls[target.id];
    }
    if(target) {
    	//Tell the legacy system that we have a target
    	Xojo.input.lastCoordinates = Xojo.input.getCoordinates(target,event);
    	Xojo.input.lastEvent = event;
    	Xojo.input.target = ctl;
    	
    	//Create our own mouse event
		var simulatedEvent = document.createEvent("MouseEvent");
		simulatedEvent.initMouseEvent(type, true, true, window, 1, 
								  first.screenX, first.screenY, 
								  first.clientX, first.clientY, false, 
								  false, false, false, 0/*left*/, null);
								  
		//Send it to our target control
		target.dispatchEvent(simulatedEvent);
		
		//Don't actually fire the mouse event that follows
		event.preventDefault();
    }
};

Xojo.input.contextClick = function (event) {
	event = event || window.event;
	if (!event) {
		return;
	}
	
	var id = '';
	if (this.id) {
		id = this.id.substring(0,8);
	} else if ((event.srcElement) || (event.target)) {
		var element = event.srcElement || event.target;
		while (((element.id === '') || (Xojo.controls[element.id] == undefined)) && (element.offsetParent !== null)) {
			element = element.offsetParent;
		}
		id = element.id.substring(0,8);
	}
	if ((id === '') || (!event)) {
		return;
	}
	
	var control = Xojo.controls[id];
	if (!control) {
		return;
	}
	
	var coordinates = Xojo.input.getCoordinates(null,event);
	var menuID = control.menuID;
	if (menuID === '') {
		menuID = Xojo.controls[Xojo.view.currentPage].menuID;
	}
	if (menuID !== '') {
		Xojo.menus.dismissAllMenus();
		Xojo.menus.displayMenuForPoint(menuID,coordinates[0].pageX,coordinates[0].pageY,-1);
		Xojo.menus.menuTarget = control;
		Xojo.menus.action = 'context';
	}
	Xojo.events.preventDefault(event);
	Xojo.events.stopPropagation(event);
	return false;
};

// Control methods
Xojo.control.destroy = function(controlid) {
	try {
		Xojo.controls[controlid].destroy();
	} catch(ex) { }
};

// Debug Console

Xojo.console.error = function (msg) {
	var alerter = document.getElementById('XojoAlerter');
	if(alerter) {
		alerter.style.zIndex = Xojo.DOM.getMaxZ()+10;
		var el = document.getElementById('XojoAlerterDialog');
		if(!el) { el = document.getElementById('XojoAlerterSmallDialog'); }
		if(el) {
			var form = el.children[0];
			if (form) {
				form.errorbody.value = msg;
				Xojo.console.present();
				form.userdetails.focus();
			}
		}
	}
};

Xojo.console.log = function (msg) {
	if ((typeof console) != 'undefined') {
		console.log(msg);
	}
};

Xojo.console.isVisible = function () {
	return Xojo.console.mIsVisible;
};

Xojo.console.present = function () {
	var cn = document.getElementById('XojoAlerter');
	if(!cn) { cn = document.getElementById('XojoAlerterSmall'); }
	if (cn) {
		if (Xojo.console.mIsVisible === false) {
			Xojo.console.mIsVisible = true;
			Xojo.view.showOverlay();
			
			cn.style.display = 'block';
			setTimeout("document.getElementById('" + cn.id + "').style.opacity = '1'",1);
		}
	}
};

Xojo.console.dismiss = function () {
	var cn = document.getElementById('XojoAlerter');
	if(!cn) { cn = document.getElementById('XojoAlerterSmall'); }
	if (cn) {
		if (Xojo.console.mIsVisible === true) {
			Xojo.console.mIsVisible = false;
			
			cn.style.opacity = '0';
			setTimeout("document.getElementById('" + cn.id + "').style.display = 'none'",260);
			setTimeout(Xojo.view.hideOverlay,260);
		}
	}
};

Xojo.console.submit = function () {
	var el = document.getElementById('XojoAlerterDialog');
	if(!el) { el = document.getElementById('XojoAlerterSmallDialog'); }
	if(el) {
		Xojo.view.confirmationText = null;
		var form = el.children[0];
		if (form) {
			form.submit();
		}
	}
};

// Events
Xojo.events.listeners = [];
Xojo.events.listenersDisabled = [];

//Define the listenerClass
Xojo.events.listener = function(el, eventName, callback, capture) {
	this.el = el;
	this.event = eventName;
	this.callback = callback;
	this.capture = capture;
};

Xojo.events.addListener = function (el, eventName, fn, capture) {
	if(window.addEventListener) {
		el.addEventListener(eventName, fn, capture);
	} else {
		if(el.attachEvent) {
			el.attachEvent("on"+eventName, fn);
		} else {
			el["on"+eventName] = fn;
		}
	}
	var lstn = new Xojo.events.listener(el,eventName,fn,capture);
	Xojo.events.listeners.push(lstn);
};

Xojo.events.removeListener = function (el, eventName, fn, capture) {
	if(window.removeEventListener) {
		el.removeEventListener(eventName, fn, capture);
	} else {
		if(el.detachEvent) {
			el.detachEvent("on"+eventName, fn);
		} else {
			el["on"+eventName] = null;
		}
	}
	for(var i=0;i<Xojo.events.listeners.length;i++) {
		var lstn = Xojo.events.listeners[i];
		if((lstn.el.nodeName == el.nodeName || lstn.el.id == el.id) && lstn.event == eventName && lstn.callback == fn && lstn.capture == capture) {
			Xojo.events.listeners.splice(i,1);
		}
	}
};

Xojo.events.preventDefault = function (event) {
	event.returnValue = false;
	if (event.preventDefault) {
		event.preventDefault();
	}
};

Xojo.events.stopPropagation = function (event) {
	event.cancelBubble = true;
	if (event.stopPropagation) {
		event.stopPropagation();
	}
};

Xojo.events.fireEvent = function(el, eventname, props) {
	props = props || null;
	if(document.createEvent) {
		var evt = document.createEvent("Event");
		evt.initEvent(eventname,true,true);
		for(var prop in props) {
		    if(props.hasOwnProperty(prop))
		    	evt[prop] = props[prop];
		}
		return el.dispatchEvent(evt);
	} else { //Do it the IE way
		var ieEvt = document.createEventObject();
		ieEvt.cancelBubble = false;
		ieEvt.returnValue = true;
		for(var prop in props) {
		    if(props.hasOwnProperty(prop))
		    	ieEvt[prop] = props[prop];
		}
		var ieEventName = "on"+eventname;
		try {
			el.fireEvent(ieEventName,ieEvt);
		} catch(err) {
			//If IE<9 and it is a custom event, call it directly
			for(var i=0;i<Xojo.events.listeners.length;i++) {
				var lstn = Xojo.events.listeners[i];
				if((lstn.el.nodeName == el.nodeName || lstn.el.id == el.id) && lstn.event == eventname) {
					lstn.callback(ieEvt);
				}
			}
		}
	}
};

Xojo.events.fireSessionTimeout = function() {
	clearTimeout(Xojo.events.sessionTimeoutTimer);
	Xojo.comm.triggerEvent('Event','TimedOut',null);
};

Xojo.events.sessionTimeoutReset = function(event) {
	if(Xojo.events.sessionTimeoutTimer !== null) {
		clearTimeout(Xojo.events.sessionTimeoutTimer);
	}
	Xojo.events.sessionTimeoutTimer = setTimeout(Xojo.events.fireSessionTimeout,Xojo.events.sessionTimeoutPeriod);
};

Xojo.events.setSessionTimeout = function(secs) {
	Xojo.events.sessionTimeoutPeriod = secs * 1000;
	if(Xojo.events.sessionTimeoutTimer !== null) {
		Xojo.events.removeListener(document,'mousemove',Xojo.events.sessionTimeoutReset,false);
		Xojo.events.removeListener(document,'keyup',Xojo.events.sessionTimeoutReset,false);	
		Xojo.events.removeListener(document,"touchbegin",	Xojo.events.sessionTimeoutReset,false);	
		clearTimeout(Xojo.events.sessionTimeoutTimer);	
	}
	if(secs>0) {
		Xojo.events.sessionTimeoutTimer = setTimeout(Xojo.events.fireSessionTimeout,Xojo.events.sessionTimeoutPeriod);
		Xojo.events.addListener(document,'mousemove',Xojo.events.sessionTimeoutReset,false);
		Xojo.events.addListener(document,'keyup',Xojo.events.sessionTimeoutReset,false);
		Xojo.events.addListener(document,'touchbegin',Xojo.events.sessionTimeoutReset,false);
	}
};

//Drag & Drop
Xojo.dragDrop = {};
Xojo.dragDrop.dragStyle = {};

// Drag & Drop: Methods
Xojo.dragDrop.addDropZone = function(controlID, mimeType, acceptedAction, subControlID) {
	if(!subControlID) { subControlID = controlID; }
	if(!Xojo.dragDrop.dropZones) { Xojo.dragDrop.dropZones = []; }
	if(!Xojo.dragDrop.dropZones[subControlID]) { Xojo.dragDrop.dropZones[subControlID] = [];}
	// Add the specified mimetype/action to the dropzones for the specified control
	Xojo.dragDrop.dropZones[subControlID][Xojo.dragDrop.dropZones[subControlID].length] = {"type":mimeType, "action":acceptedAction, "parentControl":controlID};
	
	// If this is the first one, add the listeners
	if(Xojo.dragDrop.dropZones[subControlID].length==1) {
		var el = document.getElementById(subControlID);
		Xojo.events.addListener(el,"dragover",Xojo.dragDrop.dragover);
		Xojo.events.addListener(el,"dragenter",Xojo.dragDrop.dragenter);
		Xojo.events.addListener(el,"drop",Xojo.dragDrop.drop);
		Xojo.events.addListener(el,"dragend",Xojo.dragDrop.dragend);
		Xojo.events.addListener(el,"dragleave",Xojo.dragDrop.dragleave);
		
	}
};

Xojo.dragDrop.addDragZone = function(controlID, mimeType, allowedActions, subControlID) {
	if(!subControlID) { subControlID = controlID; }
	if(!Xojo.dragDrop.dragZones) { Xojo.dragDrop.dragZones = []; }
	if(!Xojo.dragDrop.dragZones[subControlID]) { Xojo.dragDrop.dragZones[subControlID] = [];}
	
	// Add the specified mimetype/action to the dragzones for the specified control
	Xojo.dragDrop.dragZones[subControlID][Xojo.dragDrop.dragZones[subControlID].length] = {"type":mimeType, "actions":allowedActions, "parentControl":controlID};
	
	// If this is the first item, add the listeners
	if(Xojo.dragDrop.dragZones[subControlID].length==1) {
		var el = document.getElementById(subControlID);
		el.setAttribute('draggable','true');
		Xojo.events.addListener(el,"dragstart",Xojo.dragDrop.dragstart);
		Xojo.events.addListener(el,"drag",Xojo.dragDrop.drag);
		Xojo.events.addListener(el,'selectstart', Xojo.dragDrop.selectStop);
	}
};

Xojo.dragDrop.removeDragZone = function(controlID, mimeType, subControlID) {
	if(!subControlID) { subControlID = controlID; }
	if(!Xojo.dragDrop.dragZones[subControlID]) { return; }
	
	//Remove the dragZones for the specified mimetype
	var dragZones = Xojo.dragDrop.dragZones[subControlID];
	for(i=0;i<dragZones.length;i++) {
		if(dragZones[i].type == mimeType) {
			dragZones.splice(i,1);
			i = i - 1;
		}
	}
	Xojo.dragDrop.dragZones[subControlID] = dragZones;
	
	if(Xojo.dragDrop.dragZones[subControlID].length == 0) {
		var el = document.getElementById(subControlID);
		el.removeAttribute('draggable');
		Xojo.events.removeListener(el,"dragstart",Xojo.dragDrop.dragstart);
		Xojo.events.removeListener(el,"drag",Xojo.dragDrop.drag);
		Xojo.events.removeListener(el,"selectstart",Xojo.dragDrop.selectStop);
	}
};
	
Xojo.dragDrop.removeDropZone = function(controlID, mimeType, subControlID) {
	if(!subControlID) { subControlID = controlID; }
	if(!Xojo.dragDrop.dropZones[subControlID]) { return;}
	
	//Remove the dropzones for the specified mimetype
	var dropZones = Xojo.dragDrop.dropZones[subControlID];
	for(i=0;i<dropZones.length;i++) {
		if(dropZones[i].type == mimeType) {
			dropZones.splice(i,1);
			i = i - 1;
		}
	}
	Xojo.dragDrop.dropZones[subControlID] = dropZones;
	
	//If they've all been removed, turn off the events
	if(Xojo.dragDrop.dropZones[subControlID].length == 0) {
		//Remove the drop events
		var el = document.getElementById(subControlID);
		Xojo.events.removeListener(el,"dragover",Xojo.dragDrop.dragover);
		Xojo.events.removeListener(el,"dragenter",Xojo.dragDrop.dragenter);
		Xojo.events.removeListener(el,"dragleave",Xojo.dragDrop.dragleave);
		Xojo.events.removeListener(el,"drop",Xojo.dragDrop.drop);
		Xojo.events.removeListener(el,"dragend",Xojo.dragDrop.dragend);
		delete Xojo.dragDrop.dropZones[subControlID];
	}
};
	
Xojo.dragDrop.selectStop = function(ev) {
	try {
		ev.target.dragDrop();
		ev.preventDefault();
		ev.stopPropagation();
	} catch(err) { }
};

Xojo.dragDrop.setDragStyle = function(controlID,classname) {
	if(!Xojo.dragDrop.dragStyle) { Xojo.dragDrop.dragStyle = []; }
	Xojo.dragDrop.dragStyle[controlID] = classname;
};

Xojo.dragDrop.toDragMode = function(value) {
	switch(value) {
	case 0: return "none";
	case 1: return "copy";
	case 2: return "move";
	case 3: return "copyMove";
	case 4: return "link";
	case 5: return "copyLink";
	case 6: return "linkMove";
	case 7: return "all";
	case 15: return "all";
	}
};

Xojo.dragDrop.fromDragMode = function(value) {
	switch(value) {
	case "none": return 0;
	case "copy": return 1;
	case "move": return 2;
	case "copyMove": return 3;
	case "link": return 4;
	case "copyLink": return 5;
	case "linkMove": return 6;
	case "all": return 7;
	}
};

Xojo.dragDrop.findValidDragSource = function(ev) {
	var el = ev.target;
	var validNode = el;
	
	//Move up through the hierarchy looking for an element that is a dropzone
	while(!el.hasAttribute('draggable') || el.getAttribute('draggable') == "false") {
		//Move up to the parent node
		el = el.parentNode;
		//If it's still valid...
		if(el.hasAttribute('draggable') && el.getAttribute('draggable') == "true") {
			validNode = el;
		}
	}
	
	if(validNode) {
		return validNode.id;
	}
};

Xojo.dragDrop.matchDragType = function(type) {
	var data = "";
	try {
		data = Xojo.dragDrop.dragTypes[type];
	} catch(err) {
		data = "";
	}
	return data;
};

Xojo.dragDrop.isValidDropTarget = function(ev) {
	var el = ev.target;
	var validNode = el;
	var i=0, j=0;
	Xojo.dragDrop.hoverlist = [];
	
	//Move up through the hierarchy looking for an element that is a dropzone
	while(!Xojo.dragDrop.dropZones[el.id]) {
		//Move up to the parent node
		el = el.parentNode;
		//If it's still valid...
		if(el && Xojo.dragDrop.dropZones[el.id]) {
			validNode = el;
			//Add the id to the list of items we are hovering over
			Xojo.dragDrop.hoverlist[Xojo.dragDrop.hoverlist.length] = el.id;
		}
	}
	
	
	//If the thing we found is a valid node, has dropZones defined and is enabled,
	//Check to see if this control can receive this type of drag.
	if(validNode && Xojo.dragDrop.dropZones[validNode.id]) {
		//For each of the defined dropZones...
		for(i=0;i<Xojo.dragDrop.dropZones[validNode.id].length;i++) {
			try {
				//Compare against each of the drag types...
				for(j=0;j<Xojo.dragDrop.dragTypes.length;j++) {
					//Check the data transfer types
					var srcType = Xojo.dragDrop.dragTypes[j].type;
					if(srcType == 'Text' || Xojo.dragDrop.dropZones[validNode.id][i].type == srcType) {
						//Check the data transfer actions
						var acceptAction = Xojo.dragDrop.toDragMode(Xojo.dragDrop.dropZones[validNode.id][i].action);
						var effectAllowed = Xojo.dragDrop.dragAction || ev.dataTransfer.effectAllowed;
						if(ev.dataTransfer.effectAllowed == "all" || acceptAction == "all" || ev.dataTransfer.effectAllowed.indexOf(acceptAction) > -1) {
							// or the values together
							var action = Xojo.dragDrop.fromDragMode(effectAllowed) & Xojo.dragDrop.fromDragMode(acceptAction);
							var actionText = Xojo.dragDrop.toDragMode(action);
							//If a match occurs, set the dropEffect and return the controlID							
							try {
								ev.dataTransfer.effectAllowed = actionText;
								Xojo.dragDrop.dropAction = actionText;
								ev.dataTransfer.dropEffect = actionText;
							} catch(err) { }
							//Only return a valid node if the resulting action is not "none"
							if(actionText != "none") {
								return validNode.id;
							}
						}
					}
				}
			} catch(err) { 
				//IE9 doesn't have "types" per se, so everything is technically allowed.
				var acceptAction = Xojo.dragDrop.toDragMode(Xojo.dragDrop.dropZones[validNode.id][i].action);
				if(ev.dataTransfer.effectAllowed == "all" || acceptAction == "all" || ev.dataTransfer.effectAllowed.indexOf(acceptAction) > -1) {
					// or the values together
					var action = Xojo.dragDrop.fromDragMode(ev.dataTransfer.effectAllowed) & Xojo.dragDrop.fromDragMode(acceptAction);
					var actionText = Xojo.dragDrop.toDragMode(action);
					//If a match occurs, set the dropEffect and return the controlID
					try {
						ev.dataTransfer.effectAllowed = actionText;
						ev.dataTransfer.dropEffect = actionText;
						Xojo.dragDrop.dropAction = actionText;
					} catch(err) { }
					return validNode.id;
				}
			}
		}
	}
};

// Drag & Drop: Runtime events

Xojo.dragDrop.dragstart = function(ev) {
	//set up datatransfers for all of the data types we can send
	try {
		var el = ev.target;
		ev.dataTransfer.items = [];
		var id = Xojo.dragDrop.findValidDragSource(ev);
		var tag = (el.tag)?"_" + el.tag: el.getAttribute("dragtag");
		var dragModes = 0;
		Xojo.dragDrop.dragTypes = [];
		if(Xojo.dragDrop.dragZones[id] && !el.disabled) {
			for(i=0;i<Xojo.dragDrop.dragZones[id].length;i++) {
				var type = Xojo.dragDrop.dragZones[id][i].type;
				Xojo.dragDrop.dragTypes[Xojo.dragDrop.dragTypes.length] = {"type": type, "control": id, "tag": tag, "action": Xojo.dragDrop.dragZones[id][i].actions, "parentControl": Xojo.dragDrop.dragZones[id][i].parentControl, "location": [ev.offsetX, ev.offsetY]};
				try {
					ev.dataTransfer.setData(type, id + tag);
				} catch(err) {
					ev.dataTransfer.setData('Text', id+tag);
				}
				dragModes = dragModes | Xojo.dragDrop.dragZones[id][i].actions;
			}
			var dm = Xojo.dragDrop.toDragMode(dragModes);
			ev.dataTransfer.effectAllowed = dm;
			Xojo.dragDrop.dragAction = dm;
			ev.dataTransfer.dropEffect = dm;
		} else {
			ev.preventDefault();
			return false;
		}
	} catch(err) { }
};

Xojo.dragDrop.dragenter = function(ev) {
	// Match the allowed dropzone types with the types in the dataTransfer object to see if this is allowedActions
	var id = Xojo.dragDrop.isValidDropTarget(ev);
	if(id) {
		Xojo.dragDrop.dragTarget = document.getElementById(id);
			if(Xojo.dragDrop.dragTarget && Xojo.dragDrop.dragStyle[id]) {
				Xojo.DOM.addClass(Xojo.dragDrop.dragTarget,Xojo.dragDrop.dragStyle[id]);
			}
		return Xojo.dragDrop.dragAllow(ev);
	}
};

Xojo.dragDrop.dragleave = function(ev) {
	//Remove the dragstyle
	try {
		var el = Xojo.dragDrop.dragTarget;
		if(el) {
			var id = el.id;
			if(Xojo.dragDrop.hoverlist.indexOf(id)==-1) {
					if(Xojo.dragDrop.dragStyle[id]) {
						Xojo.DOM.removeClass(el,Xojo.dragDrop.dragStyle[id]);
					}
				return Xojo.dragDrop.dragAllow(ev);
			}
		}
	} catch(err) { }
};

Xojo.dragDrop.dragAllow = function(ev) {
	//Returning false or preventing the default action is telling the browser to ALLOW the drag.
	if(ev.preventDefault) { 
		ev.preventDefault(); 
	} else {
		return false;
	} 
}

Xojo.dragDrop.dragover = function(ev) {
	//Maintain the valid drop target list
	var id = Xojo.dragDrop.isValidDropTarget(ev);
	// If we're hovering over a valid dragtarget and a dragtarget has been set
	if(id && Xojo.dragDrop.dragTarget) {
		// Make sure the DOM object actually exists
		var el = document.getElementById(id);
		if(el) {
			// If the developer wanted a style applied, add it to the object
			if(Xojo.dragDrop.dragStyle[id]) {
				Xojo.DOM.addClass(el,Xojo.dragDrop.dragStyle[id]);
			}
			Xojo.dragDrop.dragAllow(ev);
		}
	}
};

Xojo.dragDrop.drop = function(ev) {
	var i=0;j=0;thisZone=null;
	var el = Xojo.dragDrop.dragTarget;
	if(el) {
		//Trigger the event on the server
		var data = "";tag = "";parentControl = ""; targetControl = ""; origin = "";
		try {
			//Loop through the datatypes and find the first item that matches one of our drop types
			for(i=0;i<Xojo.dragDrop.dragTypes.length;i++) {
				var dt = Xojo.dragDrop.dragTypes[i]; //Remove
				for(j=0;j<Xojo.dragDrop.dropZones[el.id].length;j++) {
					targetControl = Xojo.dragDrop.dropZones[el.id][0].parentControl;
					var dz = Xojo.dragDrop.dropZones[el.id][j]; //Remove
					if(Xojo.dragDrop.dropZones[el.id][j].type == Xojo.dragDrop.dragTypes[i].type) {
						data = Xojo.dragDrop.dragTypes[i].parentControl;
						tag = Xojo.dragDrop.dragTypes[i].tag;
						origin = Xojo.dragDrop.dragTypes[i].location;
						if(!tag) { tag = " "; }
						break;
					}
				}
			}
		} catch(err) {
			if(data=="") {
				data = Xojo.dragDrop.dragTypes['Text'].control;
			}
		}
		var dropX = (ev.offsetX?ev.offsetX:ev.layerX);
		var dropY = (ev.offsetY?ev.offsetY:ev.layerY);
		
		var effAllowed = ev.dataTransfer.effectAllowed;
		var dropAct = Xojo.dragDrop.dropAction;
		var dropEffect = (ev.dataTransfer.dropEffect!="none")?ev.dataTransfer.dropEffect:ev.dataTransfer.effectAllowed;
		
		var dropAction = (Xojo.dragDrop.fromDragMode(effAllowed) & Xojo.dragDrop.fromDragMode(dropEffect) & Xojo.dragDrop.fromDragMode(dropAct));
		
		Xojo.comm.triggerEvent(targetControl,'Drop',[data,tag,dropAction,dropX,dropY,ev.target.id,origin[0],origin[1]]);
	
		//Remove the dragstyle
		if(Xojo.dragDrop.dragStyle[el.id]) {
			var el = document.getElementById(el.id);
			Xojo.DOM.removeClass(el,Xojo.dragDrop.dragStyle[el.id]);
		}
		
		// Clear the dragTarget
		delete Xojo.dragDrop.dragTarget;
		
		//Prevent the event from bubbling
		if(ev.preventDefault) {
			ev.preventDefault();
		} else {
			return false;
		}
	}
};

Xojo.dragDrop.dragend = function(ev) {
	if (ev.dataTransfer.dropEffect === "none")
        return;
};


//DOM
Xojo.DOM.addClass = function(el,className) {
	if(el && el.className != undefined && className != undefined && className != "") {
		var classNames = Xojo.DOM.getArrayOfClassNames(el);
		if(classNames.indexOf(className)===-1) {
			classNames.push(className);
			el.className = classNames.join(' ');
		}
	}
};

Xojo.DOM.removeClass = function(el,className) {
	if(el && el.className != undefined && className != undefined && className != "") {
		var classNames = Xojo.DOM.getArrayOfClassNames(el);
		var newNames = [];
		for(var i = 0; i < classNames.length; i++) {
			if(className != classNames[i]) {
				newNames.push(classNames[i]);
			}
		}
		el.className = newNames.join(' ');
	}
};

Xojo.DOM.hasClass = function(el,className) {
	if(el && el.className != undefined && className != undefined && className != "") {
		var classNames = Xojo.DOM.getArrayOfClassNames(el);
		for(var i = 0;i<classNames.length;i++) {
			if(className == classNames[i]) {
				return true;
			}
		}
	}
	return false;
};

Xojo.DOM.getAppliedStyle = function(el,styleName) {
	var style = "";
	if(el) {
		if(window.getComputedStyle) {
			style = el.ownerDocument.defaultView.getComputedStyle(el,null).getPropertyValue(Xojo.utils.toHyphens(styleName));
		} else if(el.currentStyle) {
			style = el.currentStyle[Xojo.utils.toCamelCase(styleName)];
		}
	}
	return style;
};

Xojo.DOM.getArrayOfClassNames = function(el) {
	var classNames = [];
	if(el && el.className) {
		classNames = el.className.split(' ');
	}
	return classNames;
};

Xojo.DOM.get = function(el) {
	if(typeof el === "string") {
		el = document.getElementById(el);
	}
	return el;
};

Xojo.DOM.getElementsByClassName = function(className, tag, root) {
	tag = tag || "*";
	root = (root) ? Xojo.DOM.get(root) : null || document;
	if(!root) {
		return [];
	}
	var nodes = [];
	var elements = root.getElementsByTagName(tag);
	var hasClass = Xojo.DOM.hasClass;
	for(var i = 0, len = elements.length; i < len; ++i) {
		if(hasClass(elements[i], className)) {
			nodes[nodes.length] = elements[i];
		}
	}
	return nodes;
};

Xojo.DOM.getMaxZ = function() {
	var i = 0;
	var maxZ = -100000;
	var els = [];
	try {
		els = document.all;
		if(!els) {
			els = document.getElementsByTagName("*");
		}
	} catch(docallerr) { }
	if(els!==undefined) {
		for(i=0;i<els.length;i++) {
			try {
				var nextZ = Xojo.DOM.getAppliedStyle(els[i],'zIndex');
				if(nextZ=='auto') nextZ = 0;
				nextZ = parseInt(nextZ);
				if(!isNaN(nextZ)) {
					maxZ = Math.max(maxZ,nextZ);
				}
			} catch(err) { }
		}
	} else {
		return 'auto';
	}
	return maxZ;
};

// Utils
Xojo.utils.toCamelCase = function(hyphenatedValue) {
	var result = hyphenatedValue.replace(/-\D/g, function(character) { return character.charAt(1).toUpperCase(); });
	return result;
};

Xojo.utils.toHyphens = function(camelCaseValue) {
	var result = camelCaseValue.replace(/[A-Z]/g, function(character) { return ('-' + character.charAt(0).toLowerCase());});
	return result;
};

Xojo.utils.tabCapture = function() {
	if(event.keyCode==9) {
		Xojo.events.preventDefault(event);
		return false;
	}
	return true;
};

Xojo.utils.getCookie = function(cookieName) {
	if(cookieName == undefined || cookieName == "") return "";
	var all = document.cookie;
	if(all === "") return "";
	var list = all.split("; ");
	for(var i = 0;i < list.length; i++) {
		var cookie = list[i];
		var p = cookie.indexOf("=");
		var name = cookie.substring(0,p);
		if(name==cookieName) {
			return decodeURIComponent(cookie.substring(p+1));
		}
	}
	return "";
};

Xojo.utils.getBrowserSpecs = function() {
	var specs = [];
	specs['pixelDensity'] = window.devicePixelRatio || 1; 
	return specs;
};

// Framework Base Class

function frameworkSubclass (subClass, baseClass)
{	
	function inheritance() {}
	inheritance.prototype = baseClass.prototype;
	
	subClass.prototype = new inheritance();
	subClass.prototype.constructor = subClass;
	subClass.baseConstructor = baseClass;
	subClass.superClass = baseClass.prototype;
}

function frameworkObject (target, events)
{
	if (!events) {
		events = [];
	}
	
	this.controlID = target;
	this.implementedEvents = events;
	
	var installEvents = ['MouseDown','MouseUp','MouseDrag','MouseEnter','MouseExit','MouseMove','DoubleClick'];
	for (var i = 0; i < events.length; i++) {
		if (installEvents.indexOf(events[i]) > -1) {
			Xojo.input.install(this.object());
			break;
		}
	}
	
	Xojo.controls.push(this.controlID);
	Xojo.controls[this.controlID] = this;
}

frameworkObject.prototype = {
	controlID: "",
	mMouseOffsetX: 0,
	mMouseOffsetY: 0,
	mMouseDown: false,
	mEnabled: false,
	mBaseStyle: "",
	mOpacity: 1.0,
	shouldBecomeVisible: true,
	hasFinishedLoading: false,
	implementedEvents: [],
	addImpEvt: function(name) {
		if(name!='' && this.implementedEvents.indexOf(name)==-1) {
			this.implementedEvents[this.implementedEvents.length] = name;
		}
	},
	delImpEvt: function(name) {
		try {
			this.implementedEvents.splice(this.implementedEvents.indexOf(name),1);
		} catch(e) { }
	},
	menuID: "",
	name: function () {
		return this.controlID;
	},
	object: function (suffix) {
		if(suffix==undefined) { suffix = ""; }
		return document.getElementById(this.controlID + suffix);
	},
	controlObject: function(suffix) {
		//By default the control is the same as the entire element. Controls can override.
		return this.object(suffix);
	},
	parent: function () {
		return this.object().offsetParent;
	},
	value: function () {
		return null;
	},
	mouseDown: function () {
	},
	mouseDrag: function () {
	},
	mouseUp: function () {
	},
	mouseWheel: function () {
	},
	resize: function () {
		this.refresh();
	},
	resizeComplete: function () {
	},
	destroy: function () {
		try {
			var parent = this.object().parentElement;
			if (parent) {
				parent.removeChild(this.object());
			}
        } catch(e) { }
		Xojo.controls.splice(Xojo.controls.indexOf(this.controlID),1);
		delete Xojo.controls[this.controlID];
	},
	enabled: function () {
		return this.mEnabled;
	},
	setEnabled: function (value) {
		this.setDisabledAppearance(! value);
		this.mEnabled = value;
	},
	setDisabledAppearance: function (value) {
		var el = this.object();
		if (! value) {
			Xojo.DOM.removeClass(el,"disabled");
		} else {
			Xojo.DOM.addClass(el,"disabled");
		}
		if(typeof this.setAppearance == 'function') {
			this.setAppearance(!value);
		}
	},
	visible: function ()
	{
		if (this.object().style.visibility == 'hidden') {
			return false;
		} else {
			return true;
		}
	},
	setVisible: function (value)
	{
		if (value) {
			this.object().style.display = 'block';
		} else {
			this.object().style.display = 'none';
		}
	},
	setStyle: function (value)
	{
		var className;
		if (value === null) {
			className = "";
		} else {
			className = value;
		}
		if (this.mBaseStyle !== "") {
			if (className !== "") {
				className = this.mBaseStyle + " " + className;
			} else {
				className = this.mBaseStyle;
			}
		}
		this.object().className = className;
	},
	refresh: function ()
	{
		if (Xojo.view.refresh.locked() === true) {
			if (Xojo.view.refresh.targets.indexOf(this) == -1) {
				Xojo.view.refresh.targets.push(this);
			}
		} else {
			this.willRefresh();
		}
	},
	willRefresh: function ()
	{
		// subclasses should override
	},
	focus: function ()
	{
		// subclasses should override
	},
	didFinishLoading: function ()
	{
		var obj = this.object();
		if (!obj) {
			return;
		}
		var style = obj.style;
		if (!style) {
			return;
		}
		if (this.shouldBecomeVisible === false) {
			style.display = 'none';
		}
		style.visibility = 'visible';
		this.finishedLoading();
	},
	finishedLoading: function ()
	{
		// subclasses should override
	},
	opacity: function ()
	{
		if (this.object().style.opacity) {
			return parseFloat(this.object().style.opacity);
		} else {
			return this.mOpacity;
		}
	},
	setOpacity: function (value)
	{
		this.mOpacity = value;
		
		var obj = this.object();
		obj.style.opacity = value.toString();
		try {
			obj.filters.alpha.opacity = value*100;
		} catch(err) {
			obj.style.filter = "alpha(opacity=" + (value*100) + ")";
		}
	},
	touchBegin: function (event, coordinates)
	{
	},
	touchMove: function (event, coordinates)
	{
	},
	touchEnd: function (event, coordinates)
	{
	},
	mouseEnter: function (event)
	{
	},
	mouseMove: function (event, coordinates)
	{
	},
	mouseExit: function (event)
	{
	},
	doubleClick: function (event, coordinates)
	{
	},
	keyDown: function (event)
	{
	},
	keyUp: function (event)
	{
	},
	presentContextualMenu: function (event) {
		if (this.menuID !== "") {
			var rect = this.bounds();
			
			Xojo.menus.dismissAllMenus();
			if (event) {
				var coordinates = Xojo.input.getCoordinates(null,event);
				Xojo.menus.displayMenuForPoint(this.menuID,coordinates[0].pageX,coordinates[0].pageY,-1);
			} else {
				Xojo.menus.displayMenuForRect(this.menuID,rect,null,null);
			}
			Xojo.menus.menuTarget = this;
			Xojo.menus.action = 'context';
		}
	},
	bounds: function () {
		var pos = getPosition(this.object());
		return new Rect(pos.x,pos.y,this.object().offsetWidth,this.object().offsetHeight);
	},
	menuItemSelected: function (menuID) {
		Xojo.comm.triggerEvent(this.controlID,'MenuSelected',[menuID]);
	},
	setCursor: function(name) {
		this.object().style.cursor = name;
	},
	scaleFactorChanged: function() {
	}
};

// Rect

function Rect (left, top, width, height) {
	this.left = left;
	this.top = top;
	this.right = left + width;
	this.bottom = top + height;
}

Rect.prototype = {
	left: 0,
	top: 0,
	right: 0,
	bottom: 0,
	width: function () {
		return this.right - this.left;
	},
	height: function () {
		return this.bottom - this.top;
	},
	area: function () {
		return this.width() * this.height();
	},
	union: function (source) {
		var result = new Rect(0,0,0,0);
		result.left = Math.min(this.left,source.left);
		result.top = Math.min(this.top,source.top);
		result.right = Math.max(this.right,source.right);
		result.bottom = Math.max(this.bottom,source.bottom);
		return result;
	},
	containsPoint: function (x,y) {
		if ((x >= this.left) && (x <= this.right) && (y >= this.top) && (y <= this.bottom)) {
			return true;
		} else {
			return false;
		}
	},
	offset: function (left, top) {
		this.left = this.left + left;
		this.top = this.top + top;
	},
	verticalCenter: function () {
		return this.top + Math.floor(this.height() / 2);
	},
	horizontalCenter: function () {
		return this.left + Math.floor(this.width() / 2);
	}
};

/*
Copyright © 2011 Yahoo! Inc. All rights reserved.
Redistribution and use of this software in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
- Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
- Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
- Neither the name of Yahoo! Inc. nor the names of YUI's contributors may be used to endorse or promote products derived from this software without specific prior written permission of Yahoo! Inc.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
// User agent sniffer
Xojo.ua = function() {
	var numberfy = function(s) {
		var c = 0;
		return parseFloat(s.replace(/\./g, function() {
			return (c++ == 1) ? '' : '.';
		}));
	},
	nav = navigator,
	o = {
		ie: 0,
		opera: 0,
		gecko: 0,
		webkit: 0,
		mobile: null,
		air: 0,
		caja: nav.cajaVersion,
		secure: false,
		os: null
	},	    
	ua = navigator && navigator.userAgent, 
	loc = window && window.location,
	href = loc && loc.href,
	m;
	o.secure = href && (href.toLowerCase().indexOf("https") === 0);
    if (ua) {
        if ((/windows|win32/i).test(ua)) {
            o.os = 'windows';
        } else if ((/macintosh/i).test(ua)) {
            o.os = 'macintosh';
        } else if ((/linux/i).test(ua)) {
        	o.os = 'linux';
        }
    
        // Modern KHTML browsers should qualify as Safari X-Grade
        if ((/KHTML/).test(ua)) {
            o.webkit=1;
        }

        // Modern WebKit browsers are at least X-Grade
        m=ua.match(/AppleWebKit\/([^\s]*)/);
        if (m&&m[1]) {
            o.webkit=numberfy(m[1]);

            // Mobile browser check
            if (/ Mobile\//.test(ua)) {
                o.mobile = "iOS"; // iPhone or iPod Touch
            } else if(/Android/.test(ua)) {
            	o.mobile = "Android";
            } else {
                m=ua.match(/NokiaN[^\/]*/);
                if (m) {
                    o.mobile = m[0]; // Nokia N-series, ex: NokiaN95
                }
            }

            m=ua.match(/AdobeAIR\/([^\s]*)/);
            if (m) {
                o.air = m[0]; // Adobe AIR 1.0 or better
            }

        }

		// Check for IE Edge because it identifies itself as webkit
		m=ua.match(/Edge\/([0-9.]+)/);
		if(m&&m[1]) {
			o.ie=numberfy(m[1]);
		}
		
        if (!o.webkit) { // not webkit
            // @todo check Opera/8.01 (J2ME/MIDP; Opera Mini/2.0.4509/1316; fi; U; ssr)
            m=ua.match(/Opera[\s\/]([^\s]*)/);
            if (m&&m[1]) {
                o.opera=numberfy(m[1]);
                m=ua.match(/Opera Mini[^;]*/);
                if (m) {
                    o.mobile = m[0]; // ex: Opera Mini/2.0.4509/1316
                }
            } else { // not opera or webkit
                m=ua.match(/MSIE\s([^;]*)/);
                if (m&&m[1]) {
                    o.ie=numberfy(m[1]);
                } else { // Check for IE11
                	m=ua.match(/Trident\/7\.0.*rv:([0-9.]+)/);
                	if(m&&m[1]) {
                		o.ie=numberfy(m[1]);
                	} else { // not opera, webkit, or ie
						m=ua.match(/Gecko\/([^\s]*)/);
						if (m) {
							o.gecko=1; // Gecko detected, look for revision
							m=ua.match(/rv:([^\s\)]*)/);
							if (m&&m[1]) {
								o.gecko=numberfy(m[1]);
							}
						}
					}
                }
            }
        }
    }

    return o;
}();

// Xojo.loadScript - Loads a script onto the page
Xojo.loadScript = function(id,url,callback,location,source) {
	if(location==undefined || location=="") location = "head";
	var targetEl = location;
	if(typeof location == "string") {
		targetEl = document.getElementsByTagName(location)[0];
		if(!targetEl) {
			targetEl = document.getElementById(location);
		}
	}
	if(targetEl) {
		var el = document.getElementById(id);
		if(!el) {
			var script = document.createElement("script");
			if(id!="") { script.id = id; }
			script.type = "text/javascript";
			if(url!=undefined && url!="") {
				if(script.readyState) {
					script.onreadystatechange = function(){
						if(script.readyState == "loaded" || script.readyState == "complete") {
							script.onreadystatechange = null;
							if(callback!=null && callback!="" && callback != undefined) {
								callback();
							}
						}
					};
				} else {
					script.onload = function(ev) {
						if(callback!=null && callback!="" && callback != undefined) {
							callback();
						}
					};
				}
				script.src = url;
			} else {
				if(source != undefined && source != "" && source != undefined) script.innerHTML = source;
				if(callback!=null && callback!="" && callback != undefined) {
					callback();
				}
			}
			targetEl.appendChild(script);
		} else {
			callback();
		}
	}
};

/* END: GLOBAL */

/* BEGIN: POPUPMENU */

// PopupMenu/framework.js
//
// Utility class for working with popupmenus
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function popupmenu (target, events)
{
	popupmenu.baseConstructor.call(this,target,events);
	addPostLoadObject(this);
	
	var cid = this.controlID;
	Xojo.events.addListener(this.field(),"change",function () { Xojo.controls[cid].action() },false);
	Xojo.events.addListener(this.field(),"keyup",function () { Xojo.controls[cid].action() },false);
	Xojo.events.addListener(this.field(),"focus",function () { Xojo.input.setFocusControl(Xojo.controls[cid]) },false);
	Xojo.events.addListener(this.field(),"blur",function () { Xojo.input.setFocusControl(null) },false);
}
frameworkSubclass(popupmenu, frameworkObject);
popupmenu.prototype.selectedIndex = -1;

popupmenu.prototype.action = function ()
{
	if(this.field().selectedIndex != this.selectedIndex) {
		this.selectedIndex = this.field().selectedIndex;
		markControlChanged(this);
		Xojo.comm.triggerEvent(this.controlID,'SelectionChanged');
	}
};

popupmenu.prototype.field = function ()
{
	return this.object().children[0];
};

popupmenu.prototype.willRefresh = function ()
{
	this.field().selectedIndex = this.selectedIndex;
};

popupmenu.prototype.appendRow = function (row)
{
	var field = this.field();
	field.length = field.length + 1;
	field.options[field.length - 1] = new Option(row,field.length - 1);
};

popupmenu.prototype.insertRow = function (index,row)
{
	var field = this.field();
	field.length = field.length + 1;
	
	var i;
	for (i = field.length - 1; i > index; i--) {
		field.options[i].text = field.options[i - 1].text;
		field.options[i].value = i;
	}
	
	field.options[index] = new Option(row,index);
	
	if (field.selectedIndex >= index) {
		this.selectedIndex = this.selectedIndex + 1;
		field.selectedIndex = this.selectedIndex;
	}
};

popupmenu.prototype.removeRow = function (index)
{
	var field = this.field();
	if ((index < 0) || (index >= field.length)) {
		return;
	}
	
	if (field.length == 1) {
		field.length = 0;
		return;
	}
	
	if (index == field.length - 1) {
		field.length = Math.max(field.length - 1,0);
		return;
	}
	
	var i;
	for (i = index; i < field.length - 1; i++) {
		field.options[i].text = field.options[i + 1].text;
		field.options[i].value = i;
	}
	
	if (field.selectedIndex > index) {
		this.selectedIndex = this.selectedIndex - 1;
		field.selectedIndex = this.selectedIndex;
	} else if (field.selectedIndex == index) {
		this.selectedIndex = -1;
		field.selectedIndex = -1;
	}
	
	field.length = Math.max(field.length - 1,0);
};

popupmenu.prototype.changeRow = function (index,row)
{
	this.field().options[index].text = row;
};

popupmenu.prototype.deleteAllRows = function ()
{
	this.field().length = 0;
	this.selectedIndex = -1;
};

popupmenu.prototype.value = function ()
{
	return this.selectedIndex;
};

popupmenu.prototype.setValue = function (input)
{
	var i;
    var field = this.field();
    var options = field.options;
	
	input = parseInt(input,10);
	this.selectedIndex = input;
    
    for (i = 0; i < options.length; i++) {
        options[i].defaultSelected = (i == input);
    }
    field.selectedIndex = input;
};

popupmenu.prototype.setEnabled = function (input)
{
	if (input === true) {
		this.field().disabled = false;
	} else {
		this.field().disabled = true;
	}
	this.mEnabled = input;
};

popupmenu.prototype.length = function ()
{
	return this.field().length;
};

popupmenu.prototype.setLength = function (input)
{
	this.field().length = input;
};

popupmenu.prototype.setStyle = function (value)
{
	var className;
	if (value === null) {
		className = "";
	} else {
		className = value;
	}
	if (this.mBaseStyle !== "") {
		if (className !== "") {
			className = this.mBaseStyle + " " + className;
		} else {
			className = this.mBaseStyle;
		}
	}
	this.field().className = className;
};

popupmenu.prototype.postLoad = function ()
{
	this.refresh();
};

popupmenu.prototype.keyDown = function (event)
{
	return true;
};

popupmenu.prototype.keyUp = function (event)
{
	return true;
};

popupmenu.prototype.setCursor = function(name) {
	this.object("_inner").style.cursor = name;
};

/* END: POPUPMENU */

/* BEGIN: TEXTLABEL */

// TextLabel/framework.js
//
// Utility class for working with TextLabels
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function textlabel (target,events)
{
	textlabel.baseConstructor.call(this,target,events);
	this.refresh();
}
frameworkSubclass(textlabel, frameworkObject);
textlabel.prototype.mMultiline = false;

textlabel.prototype.value = function ()
{
	return this.innerCell().innerHTML;
};

textlabel.prototype.object = function() {
	return document.getElementById(this.controlID);
};

textlabel.prototype.controlObject = function() {
	return document.getElementById(this.controlID).children[0];
};

textlabel.prototype.setAppearance = function(enabled) {
	try {
		var el = this.innerCell().children[0];
		if(enabled) {
			if(this.storedhref) el.setAttribute('href',this.storedhref);
			el.style.textDecoration = "";
			Xojo.DOM.removeClass(el,'disabled');
		} else {
			this.storedhref = el.getAttribute('href');
			el.removeAttribute('href');
			if(this.storedhref != undefined && this.storedhref != "") {
				el.style.textDecoration = "underline";
				Xojo.DOM.addClass(el,'disabled');
			}
		}
	} catch(err) {	}
}

textlabel.prototype.setEnabled = function(value)
{
	this.mEnabled = value;
	var obj = this.controlObject();
	if(value) {
		Xojo.DOM.removeClass(obj,'disabled');
	} else {
		Xojo.DOM.addClass(obj,'disabled');
	}
	this.setAppearance(value);
};

textlabel.prototype.setVisible = function(value) {
	if (value) {
			this.object().style.display = 'block';
			this.controlObject().style.display = 'block';
		} else {
			this.object().style.display = 'none';
			this.controlObject().style.display = 'none';
		}
};

textlabel.prototype.setURL = function (url)
{
	var el = document.getElementById(this.controlID);
	var child = el.children[0];
	if(child.tagName == "A") {
		child.setAttribute("href",url);
	}
};

textlabel.prototype.setValue = function (input)
{
	// Duplicate the structure exactly as it was delivered from the app
	var el = document.getElementById(this.controlID);
	var child = el.children[0];
	if(child.tagName == "A") {
		child.innerHTML = input;
	} else {
		// Label
		var p = document.createElement("p");
		p.style.margin = '0px';
		p.innerHTML = input;
		var ic = this.innerCell();
		ic.innerHTML = p.outerHTML;
	}
	// Force a full refresh
	this.willRefresh();
	this.refresh();
};

textlabel.prototype.innerCell = function ()
{
	var obj = this.controlObject();
	return obj;
};

textlabel.prototype.setMultiline = function (value)
{
	if (value != this.mMultiline) {
		this.mMultiline = value;
		this.refresh();
	}
};

textlabel.prototype.willRefresh = function ()
{
	var cell = this.object().children[0];
	var lineHeight = 0;
	cell.style.marginTop = '';
	cell.style.height = '';
	cell.style.maxHeight = '';
	if (this.mMultiline) {
		cell.style.top = '0px';
		cell.style.bottom = '0px';
	} else {
		lineHeight = cell.offsetHeight;
		cell.style.top = '50%';
		cell.style.bottom = '';
		cell.style.marginTop = '-' + Math.floor(lineHeight / 2) + 'px';
		cell.style.height = lineHeight + 'px';
		cell.style.maxHeight = cell.style.height;
	}
};

textlabel.prototype.setCursor = function(name) {
	var el = this.controlObject();
	el.style.cursor = name;
	if(el.children.length>0) {
		if(el.children[0].hasAttribute("href")) {
			el.children[0].style.cursor = name;
		}
	}
};

/* END: TEXTLABEL */

/* BEGIN: BUTTON */

// Button/framework.js
//
// Provides sync abilities to WebButton class
//
// ©2016 Xojo Inc -- All Rights Reserved
// This code contains patent-pending technology.

function button (target, events)
{
	button.baseConstructor.call(this,target,events);
	
	var cid = this.controlID;
	Xojo.events.addListener(this.field(),"click",function () { Xojo.controls[cid].action() },false);
	Xojo.events.addListener(this.field(),"focus",function () { Xojo.input.setFocusControl(Xojo.controls[cid]) },false);
	Xojo.events.addListener(this.field(),"blur",function () { Xojo.input.setFocusControl(null) },false);
}
frameworkSubclass(button, frameworkObject);

button.prototype.action = function ()
{
	if (this.implementedEvents.indexOf('Action') > -1) {
		if(this.autoDisable) {
			this.field().disabled = true;
		}
		Xojo.comm.triggerEvent(this.controlID,'Action');
	}
}

button.prototype.field = function ()
{
	return this.object().children[0];
};

button.prototype.controlObject = function() {
	return this.field();
}

button.prototype.value = function ()
{
	return this.field().value;
};

button.prototype.setAutoDisable = function(value) {
	this.autoDisable = value;
};

button.prototype.setValue = function (input)
{
	this.field().value = input;
};

button.prototype.setEnabled = function (value)
{
	if (value === true) {
		this.field().disabled = false;
	} else {
		this.field().disabled = true;
	}
	this.mEnabled = value;
};

button.prototype.setStyle = function (value)
{
	var className;
	if (value === null) {
		className = "";
	} else {
		className = value;
	}
	if (this.mBaseStyle !== "") {
		if (className !== "") {
			className = this.mBaseStyle + " " + className;
		} else {
			className = this.mBaseStyle;
		}
	}
	this.field().className = className;
};

button.prototype.keyDown = function (event)
{
	return true;
};

button.prototype.keyUp = function (event)
{
	return true;
};

button.prototype.focus = function() {
	this.object("_inner").focus();
};

button.prototype.setCursor = function(name) {
	this.object('_inner').style.cursor = name;
};

/* END: BUTTON */

/* BEGIN: ANIMATOR */

// Animator/framework.js
//
// Client-side Animator doesn't get much better than this
//
// ©2016 Xojo Inc -- All Rights Reserved
// This code contains patent-pending technology.

function animatedObject (target, prefix)
{
	this.object = target;
	
	var style = target.object().style;
	this.TransitionProperty = style[prefix + 'TransitionProperty'];
	this.TransitionDuration = style[prefix + 'TransitionDuration'];
	this.TransitionTimingFunction = style[prefix + 'TransitionTimingFunction'];
	
	var parts = style[prefix + 'Transform'].split(' ');
	var i, chunk, part;
	var transforms = [];
	part = '';
	for (i = 0; i < parts.length; i++) {
		chunk = parts[i];
		if (chunk.substring(chunk.length - 1) == ')') {
			transforms.push(part + chunk);
			part = '';
		} else {
			part += chunk;
		}
	}
	
	this.Transforms = transforms;
}

animatedObject.prototype = {
	object: null,
	TransitionProperty: '',
	TransitionDuration: '',
	TransitionTimingFunction: '',
	Transforms: [],
	OnComplete: '',
	addValues: function (properties, duration, timingFunction, transform)
	{
		if (this.TransitionProperty == 'none') {
			this.TransitionProperty = '';
		}
		if (this.TransitionProperty.indexOf(properties) == -1) {
			if (this.TransitionProperty !== '') {
				this.TransitionProperty += ', ';
			}
			this.TransitionProperty += properties;
		}
		this.TransitionDuration = duration;
		this.TransitionTimingFunction = timingFunction;
		
		if (transform) {
			var transformName = transform.substring(0,transform.indexOf("("));
			var i, functionName, found;
			
			found = false;
			for (i = 0; i < this.Transforms.length; i++) {
				functionName = this.Transforms[i];
				if (functionName.substring(0,transformName.length) == transformName) {
					found = true;
					this.Transforms[i] = transform;
				}
			}
			
			if (!found) {
				this.Transforms.push(transform);
			}
		}
	}
};

function animator (target, events)
{
	animator.baseConstructor.call(this,target,events);
	addPostLoadObject(this);
	
	var element = document.createElement('a');
	var prefixes = ['','Webkit','Moz','O','ms'];
	var i;
	for (i = 0; i < prefixes.length; i++) {
		var type = typeof element.style[prefixes[i] + 'Transform'];
		if (type !== 'undefined') {
			this.jsPrefix = prefixes[i];
			break;
		}
	}
	
	if ((Xojo.platform == 2) && (Xojo.browser == 1)) {
		// Nasty bug in Windows Safari. I hate this, but there's no other way.
		this.jsPrefix = '';
		this.cssPrefix = '';
		this.css3D = false;
		this.css2D = false;
		this.cssTransitions = false;
	} else {
		if ((typeof element.style[this.jsPrefix + 'Perspective']) !== 'undefined') {
			this.css3D = true;
		}
		if ((typeof element.style[this.jsPrefix + 'Transition']) !== 'undefined') {
			this.cssTransitions = true;
		}
		if ((typeof element.style[this.jsPrefix + 'Transform']) !== 'undefined') {
			this.css2D = true;
		}
		if (this.jsPrefix !== '') {
			this.cssPrefix = '-' + this.jsPrefix.toLowerCase() + '-';
		}
	}
	
	markControlChanged(this);
}
frameworkSubclass(animator,frameworkObject);
animator.prototype.objects = [];
animator.prototype.timeouts = [];
animator.prototype.moveActions = [];
animator.prototype.resizeActions = [];
animator.prototype.opacityActions = [];
animator.prototype.colorActions = [];
animator.prototype.cssPrefix = '';
animator.prototype.jsPrefix = '';
animator.prototype.css2D = false;
animator.prototype.css3D = false;
animator.prototype.cssTransitions = false;

animator.prototype.value = function ()
{
	var s = '';
	if ((this.css2D) && (this.cssTransitions)) {
		s = "true";
	} else {
		s = "false";
	}
	if ((this.css3D) && (this.cssTransitions)) {
		s += " true";
	} else {
		s += " false";
	}
	if (Xojo.opacitySupported === true) {
		s += " true";
	} else {
		s += " false";
	}
	return s;
};

animator.prototype.findIndexForObject = function (target)
{
	var i, obj;
	for (i = 0; i < this.objects.length; i++) {
		obj = this.objects[i];
		if (obj.object == target) {
			return i;
		}
	}
	
	obj = new animatedObject(target,this.jsPrefix);
	this.objects.push(obj);
	return this.objects.length - 1;
};

animator.prototype.postLoad = function ()
{
	var i, obj, style;
	for (i = 0; i < this.timeouts.length; i++) {
		clearTimeout(this.timeouts[i]);
	}
	this.timeouts = [];
	
	for (i = 0; i < this.objects.length; i++) {
		obj = this.objects[i];
		if (obj.object.object() === null) {
			continue;
		}
		style = obj.object.object().style;
		
		style[this.jsPrefix + 'TransitionProperty'] = obj.TransitionProperty;
		style[this.jsPrefix + 'TransitionDuration'] = obj.TransitionDuration + 's';
		style[this.jsPrefix + 'TransitionTimingFunction'] = obj.TransitionTimingFunction;
		style[this.jsPrefix + 'Transform'] = obj.Transforms.join(' ');
		
		if ((typeof(obj.left) !== 'undefined') && (obj.left !== null)) {
			style.left = obj.left;
		}
		if ((typeof(obj.top) !== 'undefined') && (obj.top !== null)) {
			style.top = obj.top;
		}
		if ((typeof(obj.width) !== 'undefined') && (obj.width !== null)) {
			style.width = obj.width;
		}
		if ((typeof(obj.height) !== 'undefined') && (obj.height !== null)) {
			style.height = obj.height;
		}
		if ((typeof(obj.opacity) !== 'undefined') && (obj.opacity !== null)) {
			obj.object.setOpacity(obj.opacity);
		}
		if ((typeof(obj.color) !== 'undefined') && (obj.color !== null)) {
			style.backgroundColor = obj.color;
		}
        if ((typeof(obj.marginLeft) !== 'undefined') && (obj.marginLeft !== null)) {
            style.marginLeft = obj.marginLeft;
        }
        if ((typeof(obj.marginTop) !== 'undefined') && (obj.marginTop !== null)) {
            style.marginTop = obj.marginTop;
        }
		
		if (obj.OnComplete !== '') {
			this.timeouts.push(setTimeout(obj.OnComplete,obj.TransitionDuration * 1000));
		}
		this.timeouts.push(setTimeout("Xojo.controls['" + this.controlID + "'].complete(Xojo.controls['" + obj.object.controlID + "'])",obj.TransitionDuration * 1000));
	}
	
	this.objects = [];
};

animator.prototype.complete = function (target)
{
	var obj = target.object();
	if (obj) {
		var style = obj.style;
		style[this.jsPrefix + 'TransitionProperty'] = 'none';
	}
	if (target.animationComplete) {
		target.animationComplete();
	}
};

animator.prototype.move = function (target, left, top, durationInSeconds, easing)
{
	if (this.cssTransitions) {
		var idx = this.findIndexForObject(target);
		var p;
		if ((left !== null) && (top !== null)) {
			p = 'left, top';
		} else if (left !== null) {
			p = 'left';
		} else if (top !== null) {
			p = 'top';
		} else {
			return;
		}
		this.objects[idx].addValues(p,durationInSeconds,easing);
		if (left !== null) {
			this.objects[idx].left = left;
		} else {
			this.objects[idx].left = null;
		}
		if (top !== null) {
			this.objects[idx].top = top;
		} else {
			this.objects[idx].top = null;
		}
	} else {
		var i;
		for (i = 0; i < this.moveActions.length; i++) {
			clearTimeout(this.moveActions[i]);
		}
		this.moveActions = [];
		
		var obj = target.object();
        var leftSuffix, leftDiff, leftStart;
        var topSuffix, topDiff, topStart;
		if (left !== null) {
            leftStart = obj.style.left;
            if (left.substring(left.length - 1) == '%') {
                leftSuffix = '%';
                if (leftStart === '') {
                    leftStart = (obj.offsetLeft + (obj.offsetWidth / 2)) / obj.offsetParent.offsetWidth;
                    leftStart = Math.floor(leftStart * 100);
                } else {
                    leftStart = parseInt(leftStart.substring(0,leftStart.length - 1),10);
                }
                left = parseInt(left.substring(0,left.length - 1),10);
            } else {
                leftSuffix = 'px';
                if (leftStart === '') {
                    leftStart = obj.offsetLeft;
                } else {
                    leftStart = parseInt(leftStart.substring(0,leftStart.length - 2),10);
                }
                left = parseInt(left.substring(0,left.length - 2),10);
            }
            leftDiff = left - leftStart;
		}
		if (top !== null) {
			topStart = obj.style.top;
            if (topStart.substring(topStart.length - 1) == '%') {
                topSuffix = '%';
                if (topStart === '') {
                    topStart = (obj.offsetTop + (obj.offsetHeight / 2)) / obj.offsetParent.offsetHeight;
                    topStart = Math.floor(topStart * 100);
                } else {
                    topStart = parseInt(topStart.substring(0,topStart.length - 1),10);
                }
                top = parseInt(top.substring(0,top.length - 1),10);
            } else {
                topSuffix = 'px';
                if (topStart === '') {
                    topStart = obj.offsetTop;
                } else {
                    topStart = parseInt(topStart.substring(0,topStart.length - 2),10);
                }
                top = parseInt(top.substring(0,top.length - 2),10);
            }
            topDiff = top - topStart;
		}
		
		var delay = 1/24;
		var tickDelay = delay * 1000;
		var frames = Math.ceil(durationInSeconds * (1 / delay));
		var frame, period, cmd, leftPos, topPos;
		
		for (frame = 0; frame <= frames; frame++) {
			period = Math.floor(frame * tickDelay);
			if (frame == frames) {
				period = durationInSeconds * 1000;
			}
			if (left !== null) {
				leftPos = "'" + (Math.floor(this.getEaseValue(easing,period / 1000,leftStart,leftDiff,durationInSeconds) * 100) / 100) + leftSuffix + "'";
			} else {
				leftPos = "null";
			}
			if (top !== null) {
				topPos = "'" + (Math.floor(this.getEaseValue(easing,period / 1000,topStart,topDiff,durationInSeconds) * 100) / 100) + topSuffix + "'";
			} else {
				topPos = "null";
			}
			cmd = "Xojo.controls['" + this.controlID + "'].moveStep(Xojo.controls['" + target.controlID + "']," + leftPos + "," + topPos + ")";
			this.moveActions.push(setTimeout(cmd,period));
		}
		if (target.animationComplete) {
			this.moveActions.push(setTimeout("Xojo.controls['" + target.controlID + "'].animationComplete()",durationInSeconds * 1000));
		}
	}
};

animator.prototype.moveStep = function (target, left, top)
{
	var obj = target.object();
	var style = obj.style;
	if (left !== null) {
		if (style.left != left) {
			style.left = left;
		}
	}
	if (top !== null) {
		if (style.top != top) {
			style.top = top;
		}
	}
};

animator.prototype.resize = function (target, width, height, durationInSeconds, easing, widthCenter, heightCenter)
{
	if (widthCenter === null) {
        widthCenter = false;
    }
    if (heightCenter === null) {
        heightCenter = false;
    }
    var obj = target.object();
    if (this.cssTransitions) {
		var idx = this.findIndexForObject(target);
        var values = 'width, height';
        if (widthCenter === true) {
            values = values + ', marginLeft';
        }
        if (heightCenter === true) {
            values = values + ', marginTop';
        }
		this.objects[idx].addValues(values,durationInSeconds,easing);
		this.objects[idx].width = width + 'px';
		this.objects[idx].height = height + 'px';
        if (widthCenter === true) {
            this.objects[idx].marginLeft = '-' + Math.floor(width / 2) + 'px';
        }
        if (heightCenter === true) {
            this.objects[idx].marginTop = '-' + Math.floor(height / 2) + 'px';
        }
	} else {
		var i;
		for (i = 0; i < this.resizeActions.length; i++) {
			clearTimeout(this.resizeActions[i]);
		}
		this.resizeActions = [];
		
		var startWidth = obj.clientWidth;
		var startHeight = obj.clientHeight;
		var widthDiff = width - startWidth;
		var heightDiff = height - startHeight;
		
		var delay = 1/24;
		var tickDelay = delay * 1000;
		var frames = Math.ceil(durationInSeconds * (1 / delay));
		var frame, period, cmd, widthValue, heightValue;
		
		for (frame = 0; frame <= frames; frame++) {
			period = Math.floor(frame * tickDelay);
			if (frame == frames) {
				period = durationInSeconds * 1000;
			}
			widthValue = this.getEaseValue(easing,period / 1000,startWidth,widthDiff,durationInSeconds);
			heightValue = this.getEaseValue(easing,period / 1000,startHeight,heightDiff,durationInSeconds);
			cmd = "Xojo.controls['" + this.controlID + "'].resizeStep(Xojo.controls['" + target.controlID + "']," + widthValue + "," + heightValue + "," + widthCenter + "," + heightCenter + ")";
			this.resizeActions.push(setTimeout(cmd,period));
		}
		if (target.animationComplete) {
			this.resizeActions.push(setTimeout("Xojo.controls['" + target.controlID + "'].animationComplete()",durationInSeconds * 1000));
		}
	}
};

animator.prototype.resizeStep = function (target, width, height, widthCenter, heightCenter)
{
	var obj = target.object();
	var style = obj.style;
	if (style.width != width + 'px') {
		style.width = width + 'px';
        if (widthCenter === true) {
            style.marginLeft = '-' + Math.floor(width / 2) + 'px';
        }
	}
	if (style.height != height + 'px') {
		style.height = height + 'px';
        if (heightCenter === true) {
            style.marginTop = '-' + Math.floor(height / 2) + 'px';
        }
	}
};

animator.prototype.setopacity = function (target, opacity, durationInSeconds, easing)
{
	if (this.cssTransitions) {		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues('opacity',durationInSeconds,easing);
		this.objects[idx].opacity = opacity;
	} else {
		var i;
		for (i = 0; i < this.opacityActions.length; i++) {
			clearTimeout(this.opacityActions[i]);
		}
		this.opacityActions = [];
		
		if (durationInSeconds > 0) {
			var startOpacity = target.opacity();
			var opacityDiff = opacity - startOpacity;
			
			var delay = 1/24;
			var tickDelay = delay * 1000;
			var frames = Math.ceil(durationInSeconds * (1 / delay));
			var frame, period, cmd, opacityValue;
			
			for (frame = 0; frame <= frames; frame++) {
				period = Math.floor(frame * tickDelay);
				if (frame == frames) {
					period = durationInSeconds * 1000;
				}
				opacityValue = this.getEaseValue(easing,period / 1000,startOpacity,opacityDiff,durationInSeconds);
				cmd = "Xojo.controls['" + this.controlID + "'].opacityStep(Xojo.controls['" + target.controlID + "']," + opacityValue + ")";
				this.opacityActions.push(setTimeout(cmd,period));
			}
		} else {
			Xojo.controls[this.controlID].opacityStep(Xojo.controls[target.controlID],opacity);
		}
		if (target.animationComplete) {
			this.opacityActions.push(setTimeout("Xojo.controls['" + target.controlID + "'].animationComplete()",durationInSeconds * 1000));
		}
	}
};

animator.prototype.opacityStep = function (target, opacity)
{
	target.setOpacity(opacity);
};

animator.prototype.setcolor = function (target, red, green, blue, opacity, durationInSeconds, easing)
{
	if (this.cssTransitions) {
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues('background-color',durationInSeconds,easing);
		this.objects[idx].color = 'rgba(' + red + ',' + green + ',' + blue + ',' + opacity + ')';
	} else {
		var i;
		for (i = 0; i < this.colorActions.length; i++) {
			clearTimeout(this.colorActions[i]);
		}
		this.colorActions = [];
		
		var obj = target.object();
		var style = obj.style;
		
		var colorValue = style.backgroundColor;
		if (colorValue == 'transparent') {
			colorValue = 'rgba(0,0,0,0)';
		}
		colorValue = colorValue.substring(colorValue.indexOf('(') + 1);
		colorValue = colorValue.substring(0,colorValue.indexOf(')'));
		var parts = colorValue.split(',');
		var startRed = parseInt(parts[0],10);
		var startGreen = parseInt(parts[1],10);
		var startBlue = parseInt(parts[2],10);
		var startOpacity = parseFloat(parts[3]);
		var redDiff = red - startRed;
		var greenDiff = green - startGreen;
		var blueDiff = blue - startBlue;
		var opacityDiff = opacity - startOpacity;
		
		var delay = 1/24;
		var tickDelay = delay * 1000;
		var frames = Math.ceil(durationInSeconds * (1 / delay));
		var frame, period, cmd, redValue, blueValue, greenValue, opacityValue;
		
		for (frame = 0; frame <= frames; frame++) {
			period = Math.floor(frame * tickDelay);
			if (frame == frames) {
				period = durationInSeconds * 1000;
			}
			redValue = this.getEaseValue(easing,period / 1000,startRed,redDiff,durationInSeconds);
			greenValue = this.getEaseValue(easing,period / 1000,startGreen,greenDiff,durationInSeconds);
			blueValue = this.getEaseValue(easing,period / 1000,startBlue,blueDiff,durationInSeconds);
			opacityValue = this.getEaseValue(easing,period / 1000,startOpacity,opacityDiff,durationInSeconds);
			cmd = "Xojo.controls['" + this.controlID + "'].colorStep(Xojo.controls['" + target.controlID + "']," + redValue + "," + greenValue + "," + blueValue + "," + opacityValue + ")";
			this.colorActions.push(setTimeout(cmd,period));
		}
		if (target.animationComplete) {
			this.colorActions.push(setTimeout("Xojo.controls['" + target.controlID + "'].animationComplete()",durationInSeconds * 1000));
		}
	}
};

animator.prototype.colorStep = function (target, red, green, blue, opacity)
{
	var obj = target.object();
	var style = obj.style;
	var s = "rgba(" + red + "," + green + "," + blue + "," + opacity + ")";
	if (style.backgroundColor != s) {
		style.backgroundColor = s;
	}
};

animator.prototype.scale = function (target, widthfactor, heightfactor, durationInSeconds, easing)
{
	if ((this.css2D) && (this.cssTransitions)) {
		var js = 'scale(' + widthfactor + ',' + heightfactor + ')';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.rotatex = function (target, degrees,  durationInSeconds, easing)
{
	if ((this.css3D) && (this.cssTransitions)) {
		var js = 'rotatex(' + degrees + 'deg)';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.rotatey = function (target, degrees,  durationInSeconds, easing)
{
	if ((this.css3D) && (this.cssTransitions)) {
		var js = 'rotatey(' + degrees + 'deg)';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.rotatez = function (target, degrees,  durationInSeconds, easing)
{
	if ((this.css2D) && (this.cssTransitions)) {
		var js = 'rotatez(' + degrees + 'deg)';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.skewx = function (target, degrees,  durationInSeconds, easing)
{
	if ((this.css2D) && (this.cssTransitions)) {
		var js = 'skewx(' + degrees + 'deg)';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.skewy = function (target, degrees,  durationInSeconds, easing)
{
	if ((this.css2D) && (this.cssTransitions)) {
		var js = 'skewy(' + degrees + 'deg)';
		
		var idx = this.findIndexForObject(target);
		this.objects[idx].addValues(this.cssPrefix + 'transform',durationInSeconds,easing,js);
	}
};

animator.prototype.getEaseValue = function (timingFunction, elapsed, startValue, changeInValue, duration)
{
	switch (timingFunction) {
		case 'linear':
			return ((changeInValue * elapsed) / duration) + startValue;
		case 'ease-in':
			elapsed = (elapsed / duration);
			return (((changeInValue * ((elapsed))) * elapsed) * elapsed) + startValue;
		case 'ease-out':
			elapsed = (elapsed / duration) - 1;
			return (changeInValue * (((((elapsed)) * elapsed) * elapsed) + 1)) + startValue;
		case 'ease-in-out':
			elapsed = elapsed / (duration / 2);
			if (elapsed < 1) {
				return ((((changeInValue / 2) * elapsed) * elapsed) * elapsed) + startValue;
			}
			elapsed = elapsed - 2;
			return ((changeInValue / 2) * (((elapsed * elapsed) * elapsed) + 2)) + startValue;
		default:
			return 0;
	}
};

/* END: ANIMATOR */

/* BEGIN: DIALOG */

// Dialog/framework.js
//
// Utility class for working with dialogs
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function dialog (target,events)
{
	dialog.baseConstructor.call(this,target,events);
	
	this.mImagePrefix = 'palette';

	cacheImage('/framework/palette_resize.png');

	var resizeHandle = document.createElement('img');
	resizeHandle.id = this.controlID + '_resize';
	resizeHandle.className = 'resize';
	resizeHandle.src = '/framework/palette_resize.png';
	resizeHandle.width = 15;
	resizeHandle.height = 15;
	resizeHandle.alt = '';
	document.getElementById(this.controlID + '_body').appendChild(resizeHandle);
	Xojo.input.install(resizeHandle);
	
	var anim = document.createElement('div');
	anim.id = this.controlID + '_anim';
	anim.setAttribute('style','display: none;');
	anim.innerHTML = '&nbsp;';
	this.object().appendChild(anim);
	this.animator = new animator(this.controlID + '_anim',[]);
	
	switch (this.object().className) {
		case 'palette':
			cacheImage('/framework/palette_close_normal.png');
			cacheImage('/framework/palette_close_hover.png');
			cacheImage('/framework/palette_close_pressed.png');
			Xojo.input.install(document.getElementById(this.controlID + '_titlebar'));
			break;
	}
	this.mBaseStyle = this.object().className;
	this.body = new frameworkObject(this.controlID + '_body');
	this.minWidth = 0;
	this.minHeight = 0;
}

frameworkSubclass(dialog, frameworkObject);
dialog.prototype.mInsideRect = 0;
dialog.prototype.mImagePrefix = 'palette';
dialog.prototype.animator = null;
dialog.prototype.body = null;
dialog.prototype.mOpen = false;
dialog.prototype.minWidth = 0;
dialog.prototype.minHeight = 0;
dialog.prototype.anchorLeft = null;
dialog.prototype.anchorTop = null;

dialog.prototype.didFinishLoading = function ()
{
	this.object().style.display = 'none';
	this.object().style.visibility = 'visible';
};

dialog.prototype.present = function ()
{
	this.object().style.zIndex = Xojo.DOM.getMaxZ() + 10;
	this.object().style.filter = '';
	this.mOpen = true;
	switch (this.object().className) {
	case 'sheet':
		this.object().style.visibility = 'hidden';
		this.object().style.display = 'block';
		Xojo.view.preventScrolling();
		
		var body = document.getElementById(this.controlID + '_body');
		var bodyHeight = body.offsetHeight;
		
		body.style.top = '-' + bodyHeight + 'px';
		if (Xojo.opacitySupported === true) {
			this.setOpacity(0);
		}
		this.object().style.visibility = 'visible';
		
		this.animator.move(this.body,null,'0px',0.2,'ease-out');
		if (Xojo.opacitySupported === true) {
			this.animator.setopacity(this,1,0.2,'ease-out');
		}
		break;
	case 'modal':
	case 'palette':
		this.object().style.display = 'block';
		if (Xojo.opacitySupported === true) {
			this.setOpacity(0);
			this.animator.setopacity(this,1,0.2,'ease-out');
		} else {
			this.object().style.display = 'block';
		}
        var style;
        if (this.object().className == 'modal') {
			Xojo.view.preventScrolling();
            style = document.getElementById(this.controlID + '_body').style;
        } else {
            style = this.object().style;
        }
        if ((typeof style[this.animator.jsPrefix + 'AnimationName']) !== 'undefined') {
            style[this.animator.jsPrefix + 'AnimationName'] = 'pop';
            style[this.animator.jsPrefix + 'AnimationDuration'] = '0.2s';
            style[this.animator.jsPrefix + 'AnimationDirection'] = 'normal';
            style[this.animator.jsPrefix + 'AnimationIterationCount'] = '1';
        }
		break;
	}
	
	// fire the Shown event
	Xojo.comm.triggerEvent(this.controlID,'DialogShown');
	
	//Set focus on the first field
	var els = Xojo.DOM.getElementsByClassName("tabWrapFirstField","input",this.controlID);
	if(els.length > 0) {
		els[els.length-1].focus();
	}
	
	// Tell all of the children to refresh
	var desc = this.object("_body").getElementsByTagName("div");
	var i;
	for(i=0;i<desc.length;i++) {
		try {
			if(desc[i].id != undefined) {
				var ctl = Xojo.controls[desc[i].id];
				if(ctl !== undefined) {
					ctl.refresh();
				}
			}
		} catch(ex) {
		}
	}
};


dialog.prototype.dismiss = function (isClosing)
{
	// fire the Dismissed event
	Xojo.comm.triggerEvent(this.controlID,'DialogDismissed',[isClosing]);
	
	this.mOpen = false;
	switch (this.object().className) {
	case 'sheet':
		var body = document.getElementById(this.controlID + '_body');
		var bodyHeight = body.offsetHeight;
		
		this.animator.move(this.body,null,(bodyHeight * -1) + 'px',0.2,'ease-out');
		this.animator.setopacity(this,0,0.2,'ease-out');
		break;
	case 'modal':
	case 'palette':
		if (Xojo.opacitySupported === true) {
			this.animator.setopacity(this,0,0.2,'ease-out');
		} else {
			this.object().style.display = 'none';
		}
		break;
	}
	var that = this;
	setTimeout(function () { that.hide(); },300);
	Xojo.view.allowScrolling();
};

dialog.prototype.hide = function() {
	var el = document.getElementById(this.controlID);
	if(el) {
		try {
			el.style.display = 'none';
		} catch(err) { 	}
	}
};

dialog.prototype.animationComplete = function ()
{
	if (this.object() === null) {
		return;
	}
	
	if (this.mOpen === true) {
		return;
	}
	
	switch (this.object().className) {
	case 'sheet':
		this.object().style.visibility = 'hidden';
		this.object().style.display = 'none';
		break;
	case 'modal':
	case 'palette':
		this.object().style.display = 'none';
		break;
	}
};

dialog.prototype.touchBegin = function (event, coordinates)
{
	var x = coordinates[0].x || 0;
	var y = coordinates[0].y || 0;
	
	var widget = document.getElementById(this.controlID + '_close');
	var widgetTop = 0, widgetHeight = 0, widgetLeft = 0, widgetWidth = 0;
	if (widget) {
		widgetTop = widget.parentNode.offsetTop;
		widgetHeight = widget.parentNode.offsetHeight;
		widgetLeft = widget.parentNode.offsetLeft;
		widgetWidth = widget.parentNode.offsetWidth;
	}
	
	var resize = document.getElementById(this.controlID + '_resize');
	var resizeTop = resize.offsetTop;
	var resizeHeight = resize.offsetHeight;
	var resizeLeft = resize.offsetLeft;
	var resizeWidth = resize.offsetWidth;
	
	var titlebar = document.getElementById(this.controlID + '_titlebar');
	if (titlebar) {
		resizeTop = resizeTop + titlebar.offsetHeight;
		this.object().style.zIndex = Xojo.DOM.getMaxZ() + 10;
	}
	
	switch (this.object().className) {
	case 'sheet':
	case 'modal':
		var target = document.getElementById(this.controlID + '_body');
		resizeTop = resizeTop + target.offsetTop;
		resizeLeft = resizeLeft + target.offsetLeft;
		break;
	}
	
	if ((widget) && (x >= widgetLeft) && (x <= widgetLeft + widgetWidth) && (y >= widgetTop) && (y <= widgetTop + widgetHeight)) {
		this.mInsideRect = 1;
		widget.src = '/framework/' + this.type() + '_close_pressed.png';
	} else if ((x >= resizeLeft) && (x <= resizeLeft + resizeWidth) && (y >= resizeTop) && (y <= resizeTop + resizeHeight)) {
		this.mInsideRect = 2;
	} else {
		this.mInsideRect = 0;
	}
	
	this.mMouseOffsetX = x;
	this.mMouseOffsetY = y;
	this.mMouseDown = true;
	
	return true;
};

dialog.prototype.touchMove = function (event, coordinates)
{
	var x = coordinates[0].x;
	var y = coordinates[0].y;
	var thisClassName = this.object().className;
	
	var target;
	switch (thisClassName) {
	case 'sheet':
	case 'modal':
		target = document.getElementById(this.controlID + '_body');
		break;
	default:
		target = this.object();
	}
	
	switch (this.mInsideRect) {
	case 1:
		break;
	case 2:
		var diffWidth = x - this.mMouseOffsetX;
		var diffHeight = y - this.mMouseOffsetY;
		switch (thisClassName) {
		case 'palette':
			diffWidth = diffWidth - 2;
			diffHeight = diffHeight - 2;
		}
		var targetWidth = Math.max(target.offsetWidth + diffWidth,this.minWidth);
		var targetHeight = Math.max(target.offsetHeight + diffHeight,this.minHeight);
		
		switch (thisClassName) {
		case 'modal':
			targetHeight = Math.max(targetHeight + diffHeight,this.minHeight);
			target.style.marginTop = '-' + Math.floor(targetHeight / 2) + 'px';
			// don't break here
		case 'sheet':
			targetWidth = Math.max(targetWidth + diffWidth,this.minWidth);
			target.style.marginLeft = '-' + Math.floor(targetWidth / 2) + 'px';
			break;
		}
		
		target.style.width = targetWidth + 'px';
		target.style.height = targetHeight + 'px';
		
		this.mMouseOffsetX = x;
		this.mMouseOffsetY = y;
		
		Xojo.view.sizing.resized();
		
		break;
	default:
		switch (thisClassName) {
		case 'palette':
			var titlebar = document.getElementById(this.controlID + '_titlebar');
			var styleY = (coordinates[0].pageY - (this.mMouseOffsetY || 0));
			var win = this.getWindowSize();
			styleY = Math.max(0,styleY);
			styleY = Math.min(styleY,win.height-titlebar.offsetHeight) + 'px';
			if (target.style.top != styleY) {
				target.style.top = styleY;
			}
			var styleX = (coordinates[0].pageX - (this.mMouseOffsetX || 0));
			styleX = Math.max(0,styleX);
			styleX = Math.min(styleX,win.width-target.offsetWidth) + 'px';
			if (target.style.left != styleX) {
				target.style.left = styleX;
			}
			break;
			
		default:
			break;
		}
		break;
	}
	
	return true;
};

dialog.prototype.touchEnd = function (event, coordinates)
{
	var x = coordinates[0].x;
	var y = coordinates[0].y;
	
	switch (this.mInsideRect) {
	case 1:
		var widget = document.getElementById(this.controlID + '_close');
		if (widget) {
			var widgetTop = widget.parentNode.offsetTop;
			var widgetHeight = widget.parentNode.offsetHeight;
			var widgetLeft = widget.parentNode.offsetLeft;
			var widgetWidth = widget.parentNode.offsetWidth;
			
			if ((x >= widgetLeft) && (x <= widgetLeft + widgetWidth) && (y >= widgetTop) && (y <= widgetTop + widgetHeight)) {
				widget.src = '/framework/' + this.type() + '_close_hover.png';
				Xojo.comm.triggerEvent(this.controlID,'Closed');
			} else {
				widget.src = '/framework/' + this.type() + '_close_normal.png';
			}
		}
		break;
	case 2:
		Xojo.comm.triggerEvent(this.controlID,'DialogResized',[this.object().offsetWidth,this.object().offsetHeight]);
		break;
	default:
		if (this.implementedEvents.indexOf('Moved') > -1) {
			Xojo.comm.triggerEvent(this.controlID,'Moved',[this.object().offsetLeft,this.object().offsetTop]);
		}
		break;
	}
	
	this.mMouseDown = false;
	return true;
};

dialog.prototype.closeMouseEnter = function ()
{
	var widget = document.getElementById(this.controlID + '_close');
	if ((widget) && (this.mMouseDown)) {
		widget.src = '/framework/' + this.type() + '_close_pressed.png';
	} else {
		widget.src = '/framework/' + this.type() + '_close_hover.png';
	}
};

dialog.prototype.closeMouseExit = function ()
{
	var widget = document.getElementById(this.controlID + '_close');
	if (widget) {
		widget.src = '/framework/' + this.type() + '_close_normal.png';
	}
};

dialog.prototype.setTitle = function (value)
{
	var title = document.getElementById(this.controlID + '_titlecell');
	if (!title) {
		return;
	}
	title.innerHTML = value;
};

dialog.prototype.setResizable = function (showResize)
{
	var resize = document.getElementById(this.controlID + '_resize');
	if (!resize) {
		return;
	}
	if (showResize) {
		resize.style.display = 'block';
	} else {
		resize.style.display = 'none';
	}
};

dialog.prototype.type = function ()
{
	return this.object().className;
};

dialog.prototype.setEnabled = function (value)
{
	this.mEnabled = value;
};

dialog.prototype.destroy = function () {
	try {
		var parent = document.getElementById('XojoDialogs');
		if (parent) {
			parent.removeChild(this.object());
		}
		Xojo.controls.splice(Xojo.controls.indexOf(this.controlID),3);
	} catch(e) { }
};

dialog.prototype.getWindowSize = function() {
	var w = window;
	
	//All but IE<9
	if(w.innerWidth != null) { return {width: w.innerWidth, height: innerHeight}; }
	
	//IE<9
	var d = w.document;
	if(document.compatMode == "CSS1Compat") {
		return {width: d.documentElement.clientWidth, height: d.documentElement.clientHeight };
	}

	//Browsers in Quirks mode
	return {width: d.body.clientWidth, height: d.body.clientHeight };
};

/* END: DIALOG */

/* BEGIN: SEPARATOR */

// ScrollBar/framework.js
//
// Minor wrapper for separators
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function separator (target, events)
{
	separator.baseConstructor.call(this,target,events);
}

frameworkSubclass(separator, frameworkObject);

/* END: SEPARATOR */

/* BEGIN: SCROLLBAR */

// ScrollBar/framework.js
//
// Utility class for working with scrollbars
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function scrollbar (target, events)
{
	scrollbar.baseConstructor.call(this,target,events);
	
    var obj = this.object();
	obj.innerHTML = '<div class="decrease"></div><div class="increase"></div><div class="track"><div class="thumb"></div></div>';
	
	switch (this.object().className) {
	case 'scrollbar vertical':
		this.mVertical = true;
		this.baseStyle = this.object().className;
		break;
	case 'scrollbar horizontal':
		this.mVertical = false;
		this.baseStyle = this.object().className;
		break;
	default:
		this.mVertical = this.object().offsetHeight > this.object().offsetWidth;
		if (this.mVertical) {
			this.baseStyle = 'scrollbar vertical';
		} else {
			this.baseStyle = 'scrollbar horizontal';
		}
		break;
	}
    
	Xojo.input.install(this.object());
	Xojo.view.sizing.targets.push(this);
	addWheelTarget(this);
}

frameworkSubclass(scrollbar, frameworkObject);
scrollbar.prototype.mMinimum = 0;
scrollbar.prototype.mMaximum = 0;
scrollbar.prototype.mValue = 0;
scrollbar.prototype.mPageStep = 20;
scrollbar.prototype.mLineStep = 1;
scrollbar.prototype.mMouseDownX = 0;
scrollbar.prototype.mMouseDownY = 0;
scrollbar.prototype.mVertical = true;
scrollbar.prototype.mInsideThumb = false;
scrollbar.prototype.mInsideTrack = false;
scrollbar.prototype.mHoldWidget = null;
scrollbar.prototype.mAnimator = 0;
scrollbar.prototype.valueChanged = '';
scrollbar.prototype.animator = 0;
scrollbar.prototype.animationElapsed = 0;
scrollbar.prototype.animationPeriod = Math.floor(1000 / 60);
scrollbar.prototype.animationStartValue = 0;
scrollbar.prototype.animationEndValue = 0;
scrollbar.prototype.animationDuration = 500;

scrollbar.prototype.touchBegin = function (event, coordinates)
{
	if(this.mEnabled) {
		if (this.mMaximum <= this.mMinimum) {
			return true;
		}
		var x = coordinates[0].x;
		var y = coordinates[0].y;
		
		var track = findChildrenByClass(this.object(),'track');
		track = track[0];
		var thumb = findChildrenByClass(track,'thumb');
		thumb = thumb[0];
		
		if (this.mAnimator !== 0) {
			clearInterval(this.mAnimator);
			this.mAnimator = 0;
		}
		
		if (this.mVertical) {
			this.mInsideTrack = ((y >= track.offsetTop) && (y <= track.offsetTop + track.offsetHeight));
			if (this.mInsideTrack) {
				y = y - track.offsetTop;
				this.mInsideThumb = ((y >= thumb.offsetTop) && (y <= thumb.offsetTop + thumb.offsetHeight));
				if (this.mInsideThumb) {
					y = y - thumb.offsetTop;
				}
			} else {
				this.mInsideThumb = false;
			}
		} else {
			this.mInsideTrack = ((x >= track.offsetLeft) && (x <= track.offsetLeft + track.offsetWidth));
			if (this.mInsideTrack) {
				x = x - track.offsetLeft;
				this.mInsideThumb = ((x >= thumb.offsetLeft) && (x <= thumb.offsetLeft + thumb.offsetWidth));
				if (this.mInsideThumb) {
					x = x - thumb.offsetLeft;
				}
			} else {
				this.mInsideThumb = false;
			}
		}
		
		this.mMouseDownX = x;
		this.mMouseDownY = y;
		if ((this.mInsideTrack) && (!this.mInsideThumb)) {
			this.jumpTo(x,y);
		} else {
			return this.touchMove(event, coordinates);
		}
		
		return true;
	}
};

scrollbar.prototype.touchMove = function (event, coordinates)
{
	if(this.mEnabled) {
		if (this.mMaximum <= this.mMinimum) {
			return true;
		}
		var x = coordinates[0].x;
		var y = coordinates[0].y;
		
		var track = findChildrenByClass(this.object(),'track');
		track = track[0];
		var thumb = findChildrenByClass(track,'thumb');
		thumb = thumb[0];
		
		var pixelRange;
		if (this.mVertical) {
			pixelRange = track.offsetHeight - thumb.offsetHeight;
		} else {
			pixelRange = track.offsetWidth - thumb.offsetWidth;
		}
		
		var valueRange = this.maximum() - this.minimum();
		var pct;
		
		if (this.mInsideThumb === true) {
			var deltaX = x - this.mMouseDownX;
			var deltaY = y - this.mMouseDownY;
			
			if ((deltaX === 0) && (deltaY === 0)) {
				return true;
			}
					
			if (this.mVertical) {
				deltaY = deltaY - track.offsetTop;
			} else {
				deltaX = deltaX - track.offsetLeft;
			}
			
			if (this.mVertical) {
				pct = deltaY / pixelRange;
			} else {
				pct = deltaX / pixelRange;
			}
			this.setValue(pct * valueRange);
		} else if (this.mInsideTrack === true) {
			return true;
		} else {
			var decrease = findChildrenByClass(this.object(),'decrease');
	        if (decrease.length === 0) {
	            decrease = findChildrenByClass(this.object(),'decrease pressed');
	        }
	        var increase = findChildrenByClass(this.object(),'increase');
	        if (increase.length === 0) {
	            increase = findChildrenByClass(this.object(),'increase pressed');
	        }
	        decrease = decrease[0];
	        increase = increase[0];
	        
	        if ((x >= decrease.offsetLeft) && (x <= decrease.offsetLeft + decrease.offsetWidth) && (y >= decrease.offsetTop) && (y <= decrease.offsetTop + decrease.offsetHeight)) {
	            this.setValue(this.value() - this.lineStep());
	            this.mHoldWidget = decrease;
	            if (decrease.className == 'decrease') {
	                decrease.className = 'decrease pressed';
	            }
	        } else if ((x >= increase.offsetLeft) && (x <= increase.offsetLeft + increase.offsetWidth) && (y >= increase.offsetTop) && (y <= increase.offsetTop + increase.offsetHeight)) {
	            this.setValue(this.value() + this.lineStep());
	            this.mHoldWidget = increase;
	            if (increase.className == 'increase') {
	                increase.className = 'increase pressed';
	            }
	        } else {
	            return true;
	        }
		}
		
		return true;
	}
};

scrollbar.prototype.touchEnd = function (event, coordinates)
{
	if(this.mEnabled) {
		if (this.mMaximum <= this.mMinimum) {
			return true;
		}
		
		if (this.mHoldWidget) {
			if (this.mHoldWidget.className == 'decrease pressed') {
				this.mHoldWidget.className = 'decrease';
			} else if (this.mHoldWidget.className == 'increase pressed') {
				this.mHoldWidget.className = 'increase';
			}
			this.mHoldWidget = null;
		}
		
		return true;
	}
};

scrollbar.prototype.mouseWheel = function (deltaX, deltaY)
{
	if(this.mEnabled) {
		if (this.mVertical) {
	        this.setValue(this.value() + (deltaY * this.lineStep()));
	    } else {
	        this.setValue(this.value() + (deltaX * this.lineStep()));
	    }
		return true;
	}
};

scrollbar.prototype.jumpTo = function (x,y)
{
	var track = findChildrenByClass(this.object(),'track');
	track = track[0];
	var thumb = findChildrenByClass(track,'thumb');
	thumb = thumb[0];
	
	var thumbSize, trackSize, pixelMin, pixelMax;
	if (this.mVertical) {
		thumbSize = thumb.offsetHeight;
		trackSize = track.offsetHeight;
	} else {
		thumbSize = thumb.offsetWidth;
		trackSize = track.offsetWidth;
	}
	pixelMin = thumbSize / 2;
	pixelMax = trackSize - (thumbSize / 2);
	
	var amt;
	if (this.mVertical) {
		amt = (y - pixelMin) / (pixelMax - pixelMin);
	} else {
		amt = (x - pixelMin) / (pixelMax - pixelMin);
	}
	amt = Math.min(Math.max(amt,0),1);
	
	var valueRange = this.maximum() - this.minimum();
	
	this.setValueAnimated(amt * valueRange);
};

scrollbar.prototype.setValue = function (newValue)
{
	newValue = parseInt(newValue,10);
    var v = Math.max(Math.min(newValue,this.maximum()),this.minimum());
	if (this.mValue == v) {
		return;
	}
	this.mValue = v;
	this.refresh();
	markControlChanged(this);
	if (this.valueChanged !== '') {
		eval(this.valueChanged);
	} else {
		Xojo.comm.triggerEvent(this.controlID,'ValueChanged');
	}
};

scrollbar.prototype.setValueAnimated = function (newValue)
{
	newValue = parseInt(newValue,10);
    if (this.animator > 0) {
        clearInterval(this.animator);
    }
    this.animationElapsed = 0;
    this.animationStartValue = this.value();
    this.animationEndValue = newValue;
    this.animator = setInterval("Xojo.controls['" + this.controlID + "'].animationStep();",this.animationPeriod);
};

scrollbar.prototype.animationStep = function ()
{
    this.animationElapsed = Math.min(this.animationElapsed + this.animationPeriod,this.animationDuration);
    var duration = (this.animationDuration / 1000);
    var elapsed = ((this.animationElapsed / 1000) / duration) - 1;
    var diff = this.animationEndValue - this.animationStartValue;
    var value = (diff * (((((elapsed)) * elapsed) * elapsed) + 1)) + this.animationStartValue;
    
    this.setValue(value);
    
    if (this.animationElapsed >= this.animationDuration) {
        clearInterval(this.animator);
        this.animator = 0;
        if (this.value() != this.animationEndValue) {
            this.setValue(this.animationEndValue);
        }
    }
};

scrollbar.prototype.value = function ()
{
	return parseInt(this.mValue,10);
};

scrollbar.prototype.setMinimum = function (newValue)
{
	this.mMinimum = parseInt(newValue,10);
	if (this.mValue < this.mMinimum) {
		this.setValue(this.mMinimum);
	} else {
		this.refresh();
	}
};

scrollbar.prototype.minimum = function ()
{
	return parseInt(this.mMinimum,10);
};

scrollbar.prototype.setMaximum = function (newValue)
{
	this.mMaximum = parseInt(newValue,10);
	if (this.mValue > this.mMaximum) {
		this.setValue(this.mMaximum);
	} else {
		this.refresh();
	}
};

scrollbar.prototype.maximum = function ()
{
	return parseInt(this.mMaximum,10);
};

scrollbar.prototype.setPageStep = function (newValue)
{
	this.mPageStep = parseInt(newValue,10);
	this.refresh();
};

scrollbar.prototype.pageStep = function ()
{
	return parseInt(this.mPageStep,10);
};

scrollbar.prototype.setLineStep = function (newValue)
{
	this.mLineStep = parseInt(newValue,10);
	this.refresh();
};

scrollbar.prototype.lineStep = function ()
{
	return parseInt(this.mLineStep,10);
};

scrollbar.prototype.willRefresh = function ()
{
	var track = findChildrenByClass(this.object(),'track');
	track = track[0];
	var thumb = findChildrenByClass(track,'thumb');
	thumb = thumb[0];
	
	if (this.mMaximum <= this.mMinimum) {
		// neutral
		thumb.style.display = 'none';
		if (this.mVertical) {
			track.style.top = '-15px';
			track.style.bottom = '-15px';
		} else {
			track.style.left = '-15px';
			track.style.right = '-15px';
		}
		return;
	} else if (thumb.style.display == 'none') {
		thumb.style.display = 'block';
		if (this.mVertical) {
			track.style.top = '15px';
			track.style.bottom = '15px';
		} else {
			track.style.left = '15px';
			track.style.right = '15px';
		}
	}
	
	var valueRange = this.maximum() - this.minimum();
	var valuePercent = (this.value() - this.minimum()) / (this.maximum() - this.minimum());
	var pixelRange;
	if (this.mVertical) {
		pixelRange = track.offsetHeight;
	} else {
		pixelRange = track.offsetWidth;
	}
	var thumbSize = Math.max(pixelRange * this.pageStep() / (valueRange + this.pageStep()),16);
	if(isNaN(thumbSize)) { thumbSize = 16; }
	pixelRange = pixelRange - thumbSize;
	
	var low, high;
	if (this.mVertical) {
		low = Math.floor(Math.max(valuePercent * pixelRange,0));
		high = Math.floor(Math.max(track.offsetHeight - (low + thumbSize),0));
		thumb.style.top = low + 'px';
		thumb.style.bottom = high + 'px';
		thumb.style.height = '';
	} else {
		low = Math.floor(Math.max(valuePercent * pixelRange,0));
		high = Math.floor(Math.max(track.offsetWidth - (low + thumbSize),0));
		thumb.style.left = low + 'px';
		thumb.style.right = high + 'px';
		thumb.style.width = '';
	}
};

scrollbar.prototype.resize = function ()
{
	this.refresh();
};

scrollbar.prototype.setEnabled = function(value) {
	this.mEnabled = value;
	var obj = this.object();
	for(var i = 0;i<obj.children.length;i++) {
		if(value) {
			Xojo.DOM.removeClass(obj.children[i],"disabled");
		} else {
			Xojo.DOM.addClass(obj.children[i],"disabled");
		}
	}
};

/* END: SCROLLBAR */

/* BEGIN: LISTBOX */

// ListBox/framework.js
//
// Utility class for working with listboxes
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function listbox (target, events)
{
	listbox.baseConstructor.call(this,target,events);
	
	var myName = this.controlID;
	this.mSelectedRows = [];
	this.mColumnWidths = [];
	this.mColumnStyles = [];
	this.styleCache = [];
	this.colBaseWidth = [];
	this.mBaseStyle = 'listbox';
	
	var headers = document.getElementById(myName + "_headers");
	var content = document.getElementById(myName + "_content");
	content.style.top = headers.offsetHeight + 'px';
	
	var element;
	element = document.createElement('div');
	element.id = myName + '_vScroll';
	element.className = "scrollbar vertical";
	this.object().appendChild(element);
	element = document.createElement('div');
	element.id = myName + '_hScroll';
	element.className = "scrollbar horizontal";
	this.object().appendChild(element);
	element = document.createElement('div');
	element.id = myName + '_scrollCorner';
	element.className = "corner";
	element.innerHTML = '&nbsp;';
	this.object().appendChild(element);
	
	this.verticalScroller = new scrollbar(myName + "_vScroll",[]);
	this.verticalScroller.setEnabled(true);
	this.verticalScroller.valueChanged = "Xojo.controls['" + myName + "'].scrollVertical()";
	this.horizontalScroller = new scrollbar(myName + "_hScroll",[]);
	this.horizontalScroller.setEnabled(true);
	this.horizontalScroller.valueChanged = "Xojo.controls['" + myName + "'].scrollHorizontal()";
	Xojo.input.install(this.object());
	Xojo.view.sizing.targets.push(this);
	addWheelTarget(this);
	this.refresh();
	
	//Add some listeners to make the touch experience a little better
	try {
		content.addEventListener('touchstart',this, false);
		content.addEventListener('touchmove',this,false);
		content.addEventListener('touchend',this,false);
		content.addEventListener('touchcancel',this,false);
	} catch(err) {
		//If it can't handle addEventListener, it's probably not iOS or Android
	}
}

frameworkSubclass(listbox, frameworkObject);
listbox.prototype.verticalScroller = null;
listbox.prototype.horizontalScroller = null;
listbox.prototype.mSelectedRows = null;
listbox.prototype.mColumnWidths = null;
listbox.prototype.mColumnStyles = null;
listbox.prototype.mMinimumRowHeight = -1;
listbox.prototype.mPrimaryRowColor = '#FFFFFF';
listbox.prototype.mAlternateRowColor = '#EDF3FE';
listbox.SCROLLBARWIDTH = 15;
listbox.SCROLLBARHEIGHT = 15;

listbox.prototype.scrollVertical = function ()
{
	var rows = document.getElementById(this.controlID + "_rows");
	if (rows) {
		rows.style.top = Math.floor(this.verticalScroller.value() * -1) + 'px';
	}
};

listbox.prototype.scrollHorizontal = function ()
{
	var rows = document.getElementById(this.controlID + "_rows");
	var headers = document.getElementById(this.controlID + "_headers");
	if ((rows) && (headers)) {
		rows.style.left = Math.floor(this.horizontalScroller.value() * -1) + 'px';
		headers.style.left = rows.style.left;
	}
};

listbox.prototype.willRefresh = function ()
{
	var headers = document.getElementById(this.controlID + "_headers");
	var content = document.getElementById(this.controlID + "_content");
	var rows = document.getElementById(this.controlID + "_userrows");
	
	var i, headerChildren, min;
	headerChildren = headers.children;
	headerChildren = headerChildren.item(0).rows;
	headerChildren = headerChildren.item(0).cells;
	
	for (i = this.mColumnWidths.length; i < headerChildren.length -1; i++) {
		this.mColumnWidths[i] = '*';
	}
	min = Math.min(this.mColumnWidths.length,headerChildren.length - 1);
	
	var thisObject = this.object();
	var headersHeight = 22;
	var viewportWidth = thisObject.offsetWidth;
	var viewportHeight = thisObject.offsetHeight - headersHeight;
	var columnWidths;
	var contentWidth = 0;
	var contentHeight = 0;
	var needsHorizontalScroll = false;
	var needsVerticalScroll = false;
	var last = {'viewportWidth':0,'viewportHeight':0};
	var padderWidth, w, style;
	var finish = false;
	var cssWidths, padder;
	var row, r, cell, c;

	//See if we'll be needing scrollbars today, and adjust the viewportWidth and height
	headersHeight = headers.offsetHeight;
	contentHeight = rows.offsetHeight;
	if ((contentHeight > viewportHeight) && (needsVerticalScroll === false)) {
		needsVerticalScroll = true;
		viewportWidth = viewportWidth - listbox.SCROLLBARWIDTH;
	}
	if ((contentWidth > viewportWidth) && (needsHorizontalScroll === false)) {
		needsHorizontalScroll = true;
		viewportHeight = viewportHeight - listbox.SCROLLBARHEIGHT;
	}
	
	while (finish !== true) {
		//If the viewport width has changed, we need to do it again
		if ((viewportWidth != last.viewportWidth) || (viewportHeight != last.viewportHeight)) {	
			//Calculate the individual column widths
			columnWidths = this.actualColumnWidths(viewportWidth);
			//Figure out how wide all of the columns are together
			contentWidth = 0;
			for (i = 0; i < columnWidths.length; i++) {
				contentWidth = contentWidth + columnWidths[i];
			}
			//Update the stored viewport size (so we'll know if they changed again)
			last.viewportWidth = viewportWidth;
			last.viewportHeight = viewportHeight;
			
			//padderWidth is the width of the column that needs to be created to make up any space to the right of the defined columns
			padderWidth = thisObject.offsetWidth;
			
			//Calculate how wide each section should be in CSS units
			cssWidths = [];
			for (i = 0; i < columnWidths.length; i++) {
				w = columnWidths[i];
				if(w>0) {
					w = Math.max(w - 6,0); // Allow for padding
					padderWidth = padderWidth - columnWidths[i]; //subtract each column width
				}
				cssWidths[i] = w;
			}
			
			//For each of the headers, set the min, max and current widths
			for (i = 0; i < Math.min(headerChildren.length,cssWidths.length); i++) {
				style = headerChildren[i].style;
				if ((style) && (style.width != cssWidths[i])) {
					style.display = '';
					style.width = cssWidths[i]+'px';
					style.minWidth = cssWidths[i]+'px';
					style.maxWidth = cssWidths[i]+'px';
				}
				
				if (cssWidths[i]==0) {
					style.width = "0px";
					style.minWidth = "0px";
					style.maxWidth = "0px";
					style.paddingLeft = "0px";
					style.paddingRight = "0px";
				}
				
				//Do the same for all of the following rows for HTML5 compliant browsers
				if(Xojo.ua.ie==0 || Xojo.ua.ie > 8) {
					for(r=0;r<rows.rows.length;r++) {
						var widthOffset = 0;
						if(i>0) {
							widthOffset = 0;
						}
						style = rows.rows[r].cells[i].style;
						style.display = '';
						var cachename = r+"_"+i;
						if(r == 0 || this.styleCache.indexOf(r+"_"+i)>-1) {
							//Figure out the widths of the left/right borders
							var borderLeft = parseInt(Xojo.DOM.getAppliedStyle(rows.rows[r].cells[i],"border-left-width"));
							if(isNaN(borderLeft)) borderLeft = 0;
							var borderRight = parseInt(Xojo.DOM.getAppliedStyle(rows.rows[r].cells[i],"border-right-width"));
							if(isNaN(borderRight)) borderRight = 0;
							var borderWidth = borderLeft + borderRight;
							if(r==0 && this.colBaseWidth.length < i) {
								this.colBaseWidth[i] = borderWidth;
							}
							this.styleCache[cachename] = borderWidth + "px";
						} else {
							borderWidth = parseInt(this.styleCache["0_"+i]);
						}
						
						var newWidth = (cssWidths[i]-widthOffset-borderWidth);
						if(newWidth > 0) {
							var w = (newWidth+1)+'px';
							style.width = w;
							style.minWidth = w;
							style.maxWidth = w;
						} else {
							style.width = "0px";
							style.minWidth = "0px";
							style.maxWidth = "0px";
							style.paddingLeft = "0px";
							style.paddingRight = "0px";
							style.display = "none";
						}
					}
				}
			}
			
			//After the columns are inserted, calculate the width of the padder
			if (!padder) {
				padder = this.padder();
				padder.style.borderRight = 'none';
			}
			
			padderWidth = Math.max(padderWidth,0); //Make sure padderWidth is at least 0
			if (padderWidth > 0) {
				padderWidth = Math.max(listbox.SCROLLBARWIDTH,padderWidth); //If it's greater than zero, make it at least the width of the scrollbar
			}
			
			if((Xojo.ua.ie > 6 && Xojo.ua.ie < 9)) {
				for (r = 0; r < rows.rows.length; r++) {
					row = rows.rows[r];
					for (c = 0; c < row.cells.length; c++) {
						cell = row.cells[c];
						style = cell.style;
						if (style) {
							if(r == 0 || this.styleCache.indexOf(r+"_"+c)>-1) {
								//Figure out the widths of the left/right borders
								var borderLeft = Xojo.DOM.getAppliedStyle(cell,"border-left-width");
								borderLeft = (borderLeft=="thin")?"0":(borderLeft=="medium")?"0":(borderLeft=="thick")?"0":borderLeft;
								var borderRight = Xojo.DOM.getAppliedStyle(cell,"border-right-width");
								borderRight = (borderRight=="thin")?"0":(borderRight=="medium")?"0":(borderRight=="thick")?"0":borderRight;
								var borderWidth = parseInt(borderLeft) + parseInt(borderRight);
							} else {
								borderWidth = 0;
							}
							if (c < cssWidths.length) {
								w = cssWidths[c];
							} else {
								if(Xojo.ua.ie == 8) {
									w = Math.max(0,viewportWidth);
								} else {
									w = Math.max(0,padderWidth);
								}
							}
							if(Xojo.ua.ie==8) {
								w = w - (borderWidth)+1 + 'px';
							}
							style.width = w;
							style.minWidth = w;
							style.maxWidth = w;
						}
					}
				}
			} else {				
				if (padder.style.width != padderWidth + 'px') {
					padder.style.minWidth = padderWidth + 'px';
					padder.style.maxWidth = padderWidth + 'px';
					padder.style.width = padderWidth + 'px';
				}
			}
			headersHeight = headers.offsetHeight;
			contentHeight = rows.offsetHeight;
			content.style.top = headersHeight + 'px';
			viewportHeight = content.offsetHeight;
			if (needsHorizontalScroll === true) {
				viewportHeight = viewportHeight - listbox.SCROLLBARHEIGHT;
			}
		}
		if ((contentHeight > viewportHeight) && (needsVerticalScroll === false)) {
			needsVerticalScroll = true;
			viewportWidth = viewportWidth - listbox.SCROLLBARWIDTH;
			continue;
		}
		if ((contentWidth > viewportWidth) && (needsHorizontalScroll === false)) {
			needsHorizontalScroll = true;
			viewportHeight = viewportHeight - listbox.SCROLLBARHEIGHT;
			continue;
		}
		finish = true;
	}
	
	if (needsVerticalScroll === true) {
		this.verticalScroller.setLineStep(this.minimumRowHeight());
		this.verticalScroller.setPageStep(viewportHeight);
		this.verticalScroller.setMaximum(contentHeight - viewportHeight);
		this.verticalScroller.object().style.display = 'block';
		if (needsHorizontalScroll === true) {
			this.verticalScroller.object().style.bottom = '14px';
		} else {
			this.verticalScroller.object().style.bottom = '-1px';
		}
		this.verticalScroller.object().style.top = headersHeight + 'px';
	} else {
		this.verticalScroller.object().style.display = 'none';
		if (this.verticalScroller.value() !== 0) {
			this.verticalScroller.setValue(0);
		}
		if (this.verticalScroller.maximum() !== 0) {
			this.verticalScroller.setMaximum(0);
		}
	}
	
	if (needsHorizontalScroll === true) {
		this.horizontalScroller.setLineStep(10);
		this.horizontalScroller.setPageStep(viewportWidth);
		this.horizontalScroller.setMaximum(contentWidth - viewportWidth);
		this.horizontalScroller.object().style.display = 'block';
		if (needsVerticalScroll === true) {
			this.horizontalScroller.object().style.right = '14px';
		} else {
			this.horizontalScroller.object().style.right = '-1px';
		}
	} else {
		this.horizontalScroller.object().style.display = 'none';
		if (this.horizontalScroller.value() !== 0) {
			this.horizontalScroller.setValue(0);
		}
		if (this.horizontalScroller.maximum() !== 0) {
			this.horizontalScroller.setMaximum(0);
		}
	}
	
	w = Math.max(contentWidth,this.object().offsetWidth) + 'px';
	var rowsTable = document.getElementById(this.controlID + "_rows");
	rowsTable.style.width = w;
	rowsTable.style.minWidth = w;
	rowsTable.style.maxWidth = w;
	// Fix for overflowing row text on IE9
	if (Xojo.ua.ie == 9) {
		rowsTable.style.tableLayout = "fixed";
	}
	headers.style.width = w;
	headers.style.minWidth = w;
	headers.style.maxWidth = w;
	
	var corner = document.getElementById(this.controlID + "_scrollCorner");
	var cornerDisplay = 'none';
	if ((needsHorizontalScroll === true) && (needsVerticalScroll === true)) {
		cornerDisplay = 'block';
	}
	if (corner.style.display != cornerDisplay) {
		corner.style.display = cornerDisplay;
	}
	
	if (viewportHeight > contentHeight) {
		var autoContent = document.getElementById(this.controlID + "_autocontent");
		var excess = viewportHeight - contentHeight;
		var requiredRows = Math.ceil(excess / this.mMinimumRowHeight);
		
		var autoRows = autoContent.rows;
		for (i = autoRows.length + rows.rows.length; i < requiredRows + rows.rows.length; i++) {
			row = autoContent.insertRow(-1);
			cell = row.insertCell(-1);
			cell.setAttribute("colSpan","99");
			cell.innerHTML = "&nbsp;";
			cell.style.minHeight = this.mMinimumRowHeight + 'px';
			cell.style.height = cell.style.minHeight;
			cell.style.width = w;
			cell.style.minWidth = w;
			cell.style.maxWidth = w;
			row.style.backgroundColor = (i % 2 === 0) ? this.mPrimaryRowColor : this.mAlternateRowColor;
			c++;
		}
	}
	
	this.verticalScroller.refresh();
	this.horizontalScroller.refresh();
};

listbox.prototype.finishedLoading = function ()
{
	this.recolorRows();
};

listbox.prototype.actualColumnWidths = function (width)
{
	var widths = [];
	var remaining = width;
	var i, numFractions, exp, last, body, w, fillPercent;
	var n = this.columnCount();
	
	numFractions = 0;
	fillPercent = 0;
	for (i = 0; i < n; i++) {
		exp = this.mColumnWidths[i];
		last = exp.substring(exp.length - 1);
		body = exp.substring(0,exp.length - 1);
		
		if (last == '%') {
			w = Math.floor(width * (parseFloat(body) / 100));
			fillPercent = fillPercent + parseFloat(body);
		} else if (last == '*') {
			if (body === '') {
				body = '1';
			}
			numFractions = numFractions + parseFloat(body);
			widths[i] = body + '*';
			continue;
		} else {
			w = parseFloat(exp);
		}
		
		remaining = remaining - w;
		widths[i] = w;
	}
	for (i = 0; i < n; i++) {
		exp = widths[i].toString();
		last = exp.substring(exp.length - 1);
		body = exp.substring(0,exp.length - 1);
		
		if (last == '*') {
			w = Math.floor(remaining * (parseFloat(body) / numFractions));
			fillPercent = fillPercent + Math.round((w / width) * 100);
		} else {
			continue;
		}
		
		numFractions = numFractions - 1;
		remaining = remaining - w;
		widths[i] = Math.max(w,0);
	}
	return widths;
};

listbox.prototype.totalColumnWidths = function (width) {
	var cols = this.actualColumnWidths(width);
	var total = 0;
	var i = 0;
	for(i=0;i<cols.length;i++) {
		total += cols[i];
	}
	return total;
};

listbox.prototype.padder = function ()
{
	return document.getElementById(this.controlID + "_padder");
};

listbox.prototype.touchBegin = function (event, coordinates)
{
	if(this.mEnabled) {
		var x = coordinates[0].x;
		var y = coordinates[0].y;
		
		var vScrollWidth = this.verticalScroller.object().offsetWidth;
		var vScrollHeight = this.verticalScroller.object().offsetHeight;
		var vScrollLeft = this.verticalScroller.object().offsetLeft;
		var vScrollTop = this.verticalScroller.object().offsetTop;
		
		var hScrollWidth = this.horizontalScroller.object().offsetWidth;
		var hScrollHeight = this.horizontalScroller.object().offsetHeight;
		var hScrollLeft = this.horizontalScroller.object().offsetLeft;
		var hScrollTop = this.horizontalScroller.object().offsetTop;
		
		if ((x >= vScrollLeft) && (y >= vScrollTop) && (x <= vScrollLeft + vScrollWidth) && (y <= vScrollTop + vScrollHeight) && (this.verticalScroller.object().style.display != 'none')) {
			return Xojo.input.forwardEvent(event,this.verticalScroller);
		} else if ((x >= hScrollLeft) && (y >= hScrollTop) && (x <= hScrollLeft + hScrollWidth) && (y <= hScrollTop + hScrollHeight) && (this.horizontalScroller.object().style.display != 'none')) {
			return Xojo.input.forwardEvent(event,this.horizontalScroller);
		}
		
		return true;
	}
};

listbox.prototype.touchEnd = function (event, coordinates)
{
	if(this.mEnabled) {
		var x,y;
		x = coordinates[0].x;
		y = coordinates[0].y;
		
		var headers = document.getElementById(this.controlID + "_headers");
		var headersHeight = headers.offsetHeight;
		
		if (y <= headersHeight) {
			return;
		} else {
			y = y - headersHeight;
		}
		
		var rows = document.getElementById(this.controlID + "_rows");
		var userRows = document.getElementById(this.controlID + "_userrows");
		y = y - rows.offsetTop;
		x = x - (parseInt(rows.style.left) || 0);
		
		if (y > userRows.offsetHeight) {
			this.setSelectedRows(null);
			return;
		}
		var selectType;
		if (event.shiftKey === true) {
			selectType = 2;
		} else {
			if (Xojo.platform == 1) {
				if (event.metaKey === true) {
					selectType = 3;
				} else {
					selectType = 1;
				}
			} else {
				if (event.ctrlKey === true) {
					selectType = 3;
				} else {
					selectType = 1;
				}
			}
		}
		
		var children = userRows.children;
		var i, s, row;
		for (i = 0; i < children.length; i++) {
			if ((y >= children[i].offsetTop) && (y <= children[i].offsetTop + children[i].offsetHeight)) {
				row = i;
				break;
			}
		}
		
		if (this.implementedEvents.indexOf('CellClick') > -1) {
			var column = -1, cx = 0;
			if (headersHeight > 0) {
				children = headers.rows[0].cells;
			} else {
				children = children[row].cells;
			}
			for (i = 0; i < (children.length - 1); i++) {
				if ((x >= cx) && (x <= cx + children[i].offsetWidth)) {
					column = i;
					break;
				} else {
					cx = cx + children[i].offsetWidth;
				}
			}
			if (column > -1) {
				Xojo.comm.triggerEvent(this.name(),'CellClick',[row,column],event);
			}
		}
		
		s = row.toString();
		var selRows;
		if (selectType == 1) {
			this.setSelectedRows([s],true);
		} else if (selectType == 2) {
			selRows = this.mSelectedRows;
			var low = row, high = row;
			for (i = 0; i < selRows.length; i++) {
				low = Math.min(selRows[i],low);
				high = Math.max(selRows[i],high);
			}
			selRows = [];
			for (i = low; i <= high; i++) {
				selRows.push(String(i));
			}
			this.setSelectedRows(selRows,false);
		} else if (selectType == 3) {
			selRows = this.mSelectedRows;
			if (selRows.indexOf(s) == -1) {
				selRows.push(s);
			} else {
				selRows.splice(selRows.indexOf(s),1);
			}
			this.setSelectedRows(selRows,false);
		}
		return true;
	}
};

listbox.prototype.mouseWheel = function (deltaX, deltaY)
{
	if(this.mEnabled) {
		this.verticalScroller.setValue(this.verticalScroller.value() + (deltaY * this.verticalScroller.lineStep()));
		this.horizontalScroller.setValue(this.horizontalScroller.value() + (deltaX * this.horizontalScroller.lineStep()));
		return true;
	}
};

listbox.prototype.columnCount = function ()
{
	var headers = document.getElementById(this.controlID + "_headers");
	var tbody = headers.children.item(0);
	var columns = tbody.children.item(0).children;
	return columns.length - 1;
};

listbox.prototype.appendRow = function (columnData)
{
	this.insertRow(-1,columnData);
};

listbox.prototype.insertRow = function (rowIndex, columnData)
{
	var userContents = document.getElementById(this.controlID + "_userrows");
	var i;
	var selRows = this.mSelectedRows;
	if ((selRows) && (rowIndex >= 0)) {
		for (i = 0; i < selRows.length; i++) {
			if (selRows[i] >= rowIndex) {
				selRows[i] = parseInt(selRows[i],10) + 1;
				selRows[i] = selRows[i].toString();
			}
		}
		this.mSelectedRows = selRows;
	}
    
    var rows = userContents.rows;
    var row, cell;
    if ((rowIndex < 0) || (rowIndex >= rows.length)) {
        rowIndex = -1;
    }
    row = userContents.insertRow(rowIndex);
	var c = this.columnCount();
	var s = '';
    for (i = 0; i < c; i++) {
        cell = row.insertCell(-1);
		cell.style.minHeight = this.mMinimumRowHeight + 'px';
		cell.style.height = cell.style.minHeight;
		s = this.columnStyle(i);
		if (s !== '') {
			s = s + ' ';
		}
        cell.className = s + "column" + (i + 1);
        if (i < columnData.length) {
            cell.innerHTML = columnData[i];
        } else {
            cell.innerHTML = "&nbsp;";
        }
    }
    cell = row.insertCell(-1);
    cell.className = "padder";
    cell.innerHTML = "&nbsp;";
    
	this.delayedRefresh();
};

listbox.prototype.delayedRefresh = function() {
	if(this.wrapupTimeout != undefined) {
		clearTimeout(this.wrapupTimeout);
	}
	var that = this;
	this.wrapupTimeout = setTimeout(function() { that.recolorRows(); that.refresh(); },0);
};

listbox.prototype.removeRow = function (rowIndex)
{
	var userContents = document.getElementById(this.controlID + "_userrows");
    userContents.deleteRow(rowIndex);
	
	var selRows = this.mSelectedRows;
	var idx = selRows.indexOf(String(rowIndex));
	if (idx != -1) {
		selRows.splice(idx,1);
		this.setSelectedRows(selRows,false,false);
	}
	
	this.delayedRefresh();
};

listbox.prototype.deleteAllRows = function ()
{
	var userContents = document.getElementById(this.controlID + "_userrows");
	var c = userContents.rows.length;
    var i;
    for (i = 0; i < c; i++) {
        userContents.deleteRow(0);
    }
    
	this.mSelectedRows = [];
	this.setSelectedRows(this.mSelectedRows,false,false);
	//Don't use the delayed refresh
	this.recolorRows(); 
	this.refresh();
};

listbox.prototype.recolorRows = function ()
{
	var rowColor;
	var rows = this.object("_rows").rows;
	var r,style;
	
	for (r = 0; r < rows.length; r++) {
		rowColor = (r % 2 == 0) ? this.mPrimaryRowColor : this.mAlternateRowColor;
		style = rows[r].style;
		if (style) {
			style.backgroundColor = rowColor;
		}
		if (this.isRowSelected(r) === true) {
			if (rows[r].className != 'selected') {
				rows[r].className = 'selected';
			}
		} else {
			if (rows[r].className !== null) {
				rows[r].className = null;
			}
		}
	}
};

listbox.prototype.setSelectedRows = function (rows, scroll, triggerEvent)
{
	if (rows === null) {
        rows = [];
    }
	if ((this.mSelectedRows.length === 0) && (rows.length === 0)) {
		return;
	}
	if(triggerEvent === undefined) { triggerEvent = true; }
	this.mSelectedRows = rows;
	this.recolorRows();
    
    if ((scroll === null) || (scroll === true)) {
        if (rows.length > 0) {
            this.scrollToRow(rows[0]);
        }
    }
    
	markControlChanged(this);
	if (triggerEvent) {
		this.delayedSelectionChange();
	}
};

listbox.prototype.delayedSelectionChange = function() {
	if(this.selChangeTimer!==undefined) {
		clearTimeout(this.selChangeTimer);
	}
	var that = this;
	this.selChangeDelayed = function() { 
	Xojo.comm.triggerEvent(that.name(),'SelectionChanged');
	};
	
	this.selChangeTimer = setTimeout(this.selChangeDelayed,10);
};

listbox.prototype.scrollToRow = function (rowIndex)
{
    var body = document.getElementById(this.controlID + "_rows");
    var rows = body.rows;
    if ((rowIndex < 0) || (rowIndex >= rows.length)) {
        return;
    }
    var row = rows[rowIndex];
    var viewportHeight = document.getElementById(this.controlID + "_content").offsetHeight;
    var position = body.offsetTop;
    var top = row.offsetTop + position;
    var bottom = top + row.offsetHeight;
    
    if (top < 0) {
        this.verticalScroller.setValueAnimated(top - position);
    } else if (bottom > viewportHeight) {
        var diff = bottom - viewportHeight;
        this.verticalScroller.setValueAnimated(this.verticalScroller.value() + diff);
    }
};

listbox.prototype.isRowSelected = function (rowIndex)
{
	
	if (this.mSelectedRows.indexOf(rowIndex) == -1 && this.mSelectedRows.indexOf(String(rowIndex)) == -1) {
		return false;
	} else {
		return true;
	}
};

listbox.prototype.value = function ()
{
	return this.mSelectedRows.join(",");
};

listbox.prototype.setValue = function (input)
{
	var rows = input.split(",");
	this.setSelectedRows(rows);
};

listbox.prototype.setColumnWidths = function (widthString)
{
	var widths = widthString.split(",");
	for(i=0;i<widths.length;i++) {
		widths[i] = widths[i].trim();
	}
	this.mColumnWidths = widths;
	this.refresh();
};

listbox.prototype.setColumnHeading = function (column, heading)
{
	if ((column < 0) || (column >= this.columnCount())) {
		return;
	}
	var headers = document.getElementById(this.controlID + "_headers");
	var tbody = headers.children.item(0);
	var columns = tbody.children.item(0).children;
	
	columns[column].innerHTML = heading;
	this.refresh();
};

listbox.prototype.setColumnCount = function (count)
{
	var currentCount = this.columnCount();
	
	if (currentCount == count) {
		return;
	}
	
	var headers = document.getElementById(this.controlID + "_headers");
	var headers_body = headers.children.item(0);
	var user_rows = document.getElementById(this.controlID + "_userrows");
	var auto_rows = document.getElementById(this.controlID + "_autocontent");
	
	this.alterColumns(headers_body, count);
	this.alterColumns(user_rows, count);
	this.alterColumns(auto_rows, count);
	this.delayedRefresh();
};

listbox.prototype.alterColumns = function (tbody, columnCount)
{
	var rows = tbody.rows;
	var cells,currentCount;
	if (rows.length > 0) {
		cells = rows.item(0).cells;
		currentCount = cells.length - 1;
	} else {
		currentCount = this.columnCount();
	}
    if (currentCount == columnCount) {
        return;
    }
    
	var r,c,cell;
	for (r = 0; r < rows.length; r++) {
		cells = rows.item(r).children;
        if (columnCount > currentCount) {
            // add cells
            try {
				for (c = currentCount; c < columnCount; c++) {
					cell = rows.item(r).insertCell(c);
					cell.className = "column" + (c + 1);
					cell.setAttribute('onclick',"Xojo.comm.triggerEvent('" + this.controlID + "','HeaderPressed',[" + c + "]);return false;");
					cell.innerHTML = "&nbsp;";
				}
			} catch (err) {
				for (c = cells.length; c < columnCount; c++) {
					cell = rows.item(r).insertCell(c-1);
					cell.className = "column" + (c + 1);
					cell.setAttribute('onclick',"Xojo.comm.triggerEvent('" + this.controlID + "','HeaderPressed',[" + c + "]);return false;");
					cell.innerHTML = "&nbsp;";
				}
            }
		} else {
            // remove cells
            for (c = columnCount; c < currentCount; c++) {
                rows.item(r).deleteCell(columnCount);
            }
        }
	}
};

listbox.prototype.setMinimumRowHeight = function (value)
{
	value = Math.max(1,parseInt(value,10));
	if (value != this.mMinimumRowHeight) {
		var rows = document.getElementById(this.controlID + "_rows").rows;
		var row,r,c,style;
		for (r = 0; r < rows.length; r++) {
			row = rows[r];
			for (c = 0; c < row.cells.length; c++) {
				style = row.cells[c].style;
				if ((style) && (style.minHeight != value + 'px')) {
					style.minHeight = value + 'px';
					style.height = style.minHeight;
				}
			}
		}
		this.mMinimumRowHeight = value;
		this.refresh();
	}
};

listbox.prototype.minimumRowHeight = function ()
{
	return this.mMinimumRowHeight;
};

listbox.prototype.setPrimaryRowColor = function (value)
{
	if (value != this.mPrimaryRowColor) {
		this.mPrimaryRowColor = value;
		this.recolorRows();
	}
};

listbox.prototype.setAlternateRowColor = function (value)
{
	if (value != this.mAlternateRowColor) {
		this.mAlternateRowColor = value;
		this.recolorRows();
	}
};

listbox.prototype.setCellContents = function (row, column, contents)
{
	var tbody = document.getElementById(this.controlID + "_userrows");
	var cell = tbody.children.item(row).children.item(column);
	if (cell) {
		cell.innerHTML = contents;
	}
	this.delayedRefresh();
};

listbox.prototype.setEnabled = function (value)
{
	this.setDisabledAppearance(!value);
	this.mEnabled = value;
	this.refresh();
};

listbox.prototype.setHeadingVisible = function (value)
{
	var headers = document.getElementById(this.controlID + "_headers");
	if (value === true) {
		headers.style.display = "block";
	} else {
		headers.style.display = "none";
	}
	this.refresh();
};

listbox.prototype.columnStyle = function (column) {
	if ((column < 0) || (column >= this.columnCount()) || (column >= this.mColumnStyles.length)) {
		return '';
	} else {
		return this.mColumnStyles[column];
	}
};

listbox.prototype.setColumnStyle = function (column, className)
{
	var c = this.columnCount();
	if ((column < 0) || (column >= c)) {
		return;
	}
	
	while (this.mColumnStyles.length < c) {
		this.mColumnStyles.push('');
	}
	
	var oldStyle = this.mColumnStyles[column];
	if (className) {
		this.mColumnStyles[column] = className;
	} else {
		this.mColumnStyles[column] = '';
	}
	var newStyle = this.mColumnStyles[column];
	
	var rows = document.getElementById(this.controlID + '_userrows').rows;
	var i, className;
	for (i = 0; i < rows.length; i++) {
		className = rows[i].cells[column].className;
		if (oldStyle !== '') {
			if (className.indexOf(oldStyle) > -1) {
				className = className.replace(oldStyle,newStyle);
			} else {
				className = newStyle;
			}
		} else {
			className = newStyle;
		}
		rows[i].cells[column].className = newStyle;
	}
};

listbox.prototype.setCellStyle = function (row, column, className)
{
	var rows = document.getElementById(this.controlID + "_userrows").rows;
	if ((row >= 0) && (row < rows.length) && (column >= 0) && (column <= this.columnCount())) {
		var cell = rows[row].cells[column];
		var cn = this.columnStyle(column);
		if (cn !== '') {
			cn = cn + ' ';
		}
		cn = cn + 'column' + (column + 1) + '';
		var rowCol = row + "_" + column;
		if (className) {
			cn = cn + ' ' + className;
			if(this.styleCache.indexOf(rowCol) == -1) {
				this.styleCache.push(rowCol)
			}
		} else {
			var p = this.styleCache.indexOf(rowCol);
			if(p == -1) {
				this.styleCache.splice(p,1);
			}
		}
		cell.className = cn;
		this.refresh();
	}
};

//methods for iOS and Android
listbox.prototype.handleEvent = function(event) {
	if(typeof(this[event.type]) === "function") {
		return this[event.type](event);
	}
}

listbox.prototype.touchstart = function(event) {
	if(this.mEnabled) {
		//Record the start point
		var rows = document.getElementById(this.controlID + "_rows");
		var fingerOne = event.targetTouches[0];
		this.touchStartX = fingerOne.pageX;
		this.touchStartY = fingerOne.pageY;
		this.startPosX = rows.style.left || "0px";
		this.startPosY = rows.style.top || "0px";
	
		var rr = /px/g;
		this.startPosX = parseInt(this.startPosX.replace(rr,""));
		this.startPosY = parseInt(this.startPosY.replace(rr,""));
		this.touchMoved = false;
	}
};

listbox.prototype.touchmove = function(event) {
	if(this.mEnabled) {
		event.preventDefault();
		//Move the list
		var fingerOne = event.targetTouches[0];
		var curX = fingerOne.pageX;
		var curY = fingerOne.pageY;
		this.dx = curX-this.touchStartX;
		this.dy = curY-this.touchStartY;
		this.touchScrollList(this.startPosX+this.dx,this.startPosY+this.dy);
		this.verticalScroller.setValue(-1*(this.startPosY+this.dy));
		this.horizontalScroller.setValue(-1*(this.startPosX+this.dx));
		this.touchMoved = true;
	}
};

listbox.prototype.touchend = function(event) {
	
};

listbox.prototype.touchcancel = function(event) {
	//Return to the original state
	rows.style.left = this.startPosX;
	rows.style.top = this.startPosY;
};

listbox.prototype.touchScrollList = function(xpos,ypos) {

	var rows = document.getElementById(this.controlID + "_rows");
	var userrows = document.getElementById(this.controlID + "_userrows");
	var headers = document.getElementById(this.controlID + "_headers");
	var content = document.getElementById(this.controlID + "_content");
	var vscroll = document.getElementById(this.controlID + "_vScroll");
	var hscroll = document.getElementById(this.controlID + "_hScroll");
	
	var maxNegX = -1 * (Math.max(parseInt(userrows.offsetWidth) - parseInt(content.offsetWidth)),0);
	var maxNegY = -1 * ((parseInt(userrows.offsetHeight) - parseInt(content.offsetHeight)));
	if (vscroll.style.display == "none") {
		maxNegX += vscroll.offsetWidth;
		maxNegY = 0;
	}
	if (hscroll.style.display == "none") {
		maxNegY += hscroll.offsetHeight;
		maxNegX = 0;
	}
	
	var x = Math.max(maxNegX,Math.min(0,Math.floor(xpos)));
	var y = Math.max(maxNegY,Math.min(0,Math.floor(ypos)));
	
	rows.style.left = x + 'px';
	rows.style.top = y + 'px';
	
	if(headers) {
		headers.style.left = rows.style.left;
	}
}

listbox.prototype.setCursor = function(name) {
	this.object().style.cursor = name;
	this.object("_headers").style.cursor = name;
	this.object("_rows").style.cursor = name;
};

listbox.prototype.setAppearance = function(value)
{
	var outerEl = document.getElementById(this.controlID);
	if(outerEl) {
		for(i=0;i<4;i++) {
			var subEl = outerEl.children.item(i);
			if(subEl) {
				if(value) {
					Xojo.DOM.removeClass(subEl,"disabled")
				} else {
					Xojo.DOM.addClass(subEl,"disabled")
				}
			}
		}
	}
};

/* END: LISTBOX */

/* BEGIN: TEXTCONTROL */

// TextControl/framework.js
//
// Utility class for working with input-based TextControls
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function textcontrol (target,events)
{
	textcontrol.baseConstructor.call(this,target,events);
	this.mBaseStyle = "textcontrol";
	var cid = this.controlID;
	Xojo.events.addListener(this.field(),"focus",function () { Xojo.input.setFocusControl(Xojo.controls[cid]) },false);
	Xojo.events.addListener(this.field(),"blur",function () { Xojo.input.setFocusControl(null) },false);
	Xojo.events.addListener(this.field(),"scroll",this.scroll,false);
	Xojo.events.addListener(this.field(),"change",function() { Xojo.controls[cid].fireAction.call(Xojo.controls[cid]) },false);
	Xojo.events.addListener(this.field(),"paste",function() { Xojo.controls[cid].fireAction.call(Xojo.controls[cid]) },false);
	this.catchMouseEvents();
	this.mLastValue = this.field().value;
}
frameworkSubclass(textcontrol, frameworkObject);
textcontrol.prototype.mDelayTimer = 0;
textcontrol.prototype.mLastValue = "";

textcontrol.prototype.action = function ()
{
	try {
		if(this.value() !== this.mLastValue) {
			this.mLastValue = this.value();
			markControlChanged(this);
			Xojo.comm.triggerEvent(this.controlID,'TextChanged');
		}
	} catch(ex) {}
};

textcontrol.prototype.controlObject = function ()
{
	return document.getElementById(this.controlID).children[0];
};

textcontrol.prototype.field = function ()
{
	return this.object().children[0];
};

textcontrol.prototype.value = function ()
{
	return this.field().value;
};

textcontrol.prototype.setValue = function (input)
{
	this.field().value = input;
	this.mLastValue = input;
};

textcontrol.prototype.setAppearance = function (enabled)
{
	if (enabled === true) {
		this.field().disabled = false;
	} else {
		this.field().disabled = true;
	}
};

textcontrol.prototype.focus = function ()
{
	this.field().focus();
};

textcontrol.prototype.setStyle = function (value)
{
	var className;
	if (value === null) {
		className = "";
	} else {
		className = value;
	}
	if (this.mBaseStyle !== "") {
		if (className !== "") {
			className = this.mBaseStyle + " " + className;
		} else {
			className = this.mBaseStyle;
		}
	}
	this.field().className = className;
};

textcontrol.prototype.insertText = function (position, value)
{
	var text = this.field().value;
	var pretext = text.substring(0,position - 1);
	var posttext = text.substring(position - 1);
	this.field().value = pretext + value + posttext;
};

textcontrol.prototype.keyDown = function (event)
{
	this.fireAction();
	return false;
};

textcontrol.prototype.keyUp = function (event)
{
	this.fireAction();
	return false;
};

textcontrol.prototype.fireAction = function() {
	markControlChanged(this);

	//Password managers trigger this event on the raw control as well as our classes
	if(this.controlID) { 
		if (this.mDelayTimer !== 0) {
			clearTimeout(this.mDelayTimer);
			this.mDelayTimer = 0;
		}
	
		if (Xojo.comm.websockets.connected() === true) {
			this.mDelayTimer = setTimeout("Xojo.controls['" + this.controlID + "'].action()",100);
		} else {
			this.mDelayTimer = setTimeout("Xojo.controls['" + this.controlID + "'].action()",1000);
		}
	}
};

textcontrol.prototype.setType = function(fieldType) {
	try {
		this.field().type = fieldType;
		this.catchMouseEvents();
	} catch(ex) {
		
	}
};

textcontrol.prototype.catchMouseEvents = function() {
	if (this.implementedEvents.indexOf('MouseUp') == -1) {
		var fieldType = this.field().type.toLowerCase();
		var that = this;
		switch(fieldType) {
		case "number":
			Xojo.events.addListener(this.field(),"mouseup",function(event) { textcontrol.prototype.touchEnd.call(that,event); },false);
			break;
		default:
			Xojo.events.removeListener(this.field(),"mouseup",function(event) { textcontrol.prototype.touchEnd.call(that,event); },false);
			break;
		}
	}
}

textcontrol.prototype.touchEnd = function(event)
{
	var fieldType = this.field().type.toLowerCase();
	if(fieldType == "number") {
		this.fireAction();
	}
};

textcontrol.prototype.setCursor = function(name) {
	this.object("_inner").style.cursor = name;
};

textcontrol.prototype.setScroll = function(value) {
	if(value != this.object("_inner").scrollTop) {
		this.object("_inner").scrollTop = value;
	}
};

textcontrol.prototype.scroll = function(ev) {
	if(this.parentNode) {
		Xojo.comm.triggerEvent(this.parentNode.id,'Scrolled',[this.scrollTop]);
	} else {
		Xojo.comm.triggerEvent(ev.srcElement.parentNode.id,'Scrolled',[ev.srcElement.scrollTop]);
	}
};

/* END: TEXTCONTROL */

/* BEGIN: CONTAINER */

// Container/framework.js
//
// Utility class for working with ContainerControls
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function container (target, events)
{
	container.baseConstructor.call(this,target,events);
	
	Xojo.events.addListener(this.object(),"scroll",this.onScroll,false);
}
frameworkSubclass(container, frameworkObject);
container.prototype.scrollEventIndex = 0; //scroll events don't always arrive in the right order on IE8, so we'll serialize them

container.prototype.setVisible = function (value)
{
	if (value) {
		this.object().style.display = 'block';
	} else {
		this.object().style.display = 'none';
	}
};

container.prototype.visible = function ()
{
	if (this.object().style.display == 'none') {
		return false;
	} else {
		return true;
	}
};

container.prototype.onScroll = function(ev) {
  var el = ev.target || ev.srcElement;
  var ctl = Xojo.controls[el.id];
  ctl.scrollEventIndex = ctl.scrollEventIndex + 1;
  var left = el.scrollLeft || 0;
  var top = el.scrollTop || 0;
  Xojo.comm.triggerEvent(el.id,"scrolled",[left,top,ctl.scrollEventIndex]);
};

container.prototype.scrollTo = function(x,y) {
	var el = this.object();
	if(el) {
		el.scrollLeft = x;
		el.scrollTop = y;
	}
};

//methods for iOS and Android
container.prototype.handleEvent = function(event) {
	if(typeof(this[event.type]) === "function") {
		return this[event.type](event);
	}
}

container.prototype.touchstart = function(event) {
	//Record the start point
	var fingerOne = event.targetTouches[0];
	this.touchObj = { touchStartX: fingerOne.pageX,	touchStartY:fingerOne.pageY, startX: this.object().scrollLeft, startY: this.object().scrollTop, touchMoved: false }
};

container.prototype.touchmove = function(event) {
	event.preventDefault();

	//Move the list
	var fingerOne = event.targetTouches[0];
	var curX = fingerOne.pageX;
	var curY = fingerOne.pageY;
	this.touchObj.dx = curX - this.touchObj.touchStartX;
	this.touchObj.dy = curY - this.touchObj.touchStartY;
	this.object().scrollLeft = (this.touchObj.startX - this.touchObj.dx);
	this.object().scrollTop = (this.touchObj.startY - this.touchObj.dy);
	this.touchObj.touchMoved = true;
	this.touchObj.moveStart = (new Date()).getTime();
};

container.prototype.touchend = function(event) {
	if(!this.touchObj.touchMoved) {
		Xojo.input.touchHandler(event);
	}
	//Clean up the variables we created
	delete this.touchObj;
};

container.prototype.setEnabled = function (value) {
	this.mEnabled = value;
};

/* END: CONTAINER */

/* BEGIN: IMAGEVIEW */

// imageview/framework.js
//
// Provides sync abilities to WebImage class
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function imageview (target, events)
{
	events[events.length]='PictureChanged';
	imageview.baseConstructor.call(this,target,events);
	
	this.setSource(this.image().src);
	Xojo.view.sizing.targets.push(this);
}
frameworkSubclass(imageview, frameworkObject);
imageview.prototype.mSource = null;
imageview.prototype.mVerticalAlignment = 0;
imageview.prototype.mHorizontalAlignment = 0;
imageview.prototype.url = null;
imageview.prototype.width = 1;
imageview.prototype.height = 1;

imageview.prototype.setSource = function (url)
{
	this.url = url;
	var img = cacheImage(url,false);
	img.loaded = false;
	if (img) {
		img.targetObject = this;
		img.onload = function () {
			img.targetObject.mSource = img;
			img.targetObject.refresh();
			img.onload = null;
			if (img.targetObject.implementedEvents.indexOf('PictureChanged') > -1) {
				Xojo.comm.triggerEvent(img.targetObject.controlID,'PictureChanged',null);
			}
		};
	}
};

imageview.prototype.scaleFactorChanged = function()
{
	this.setSource(this.url);
};

imageview.prototype.image = function ()
{
	return this.object().children.item(0);
};

imageview.prototype.setSize = function(width, height) {
	this.width = width;
	this.height = height;
	this.refresh();
}

imageview.prototype.willRefresh = function ()
{
	if (!this.mSource) {
		return;
	}
	
	var image = this.image();
	image.src = this.mSource.src;
	var w = this.width || this.mSource.width;
	var h = this.height || this.mSource.height;
	if (!this.url) {
		w = w / Xojo.view.devicePixelRatio;
		h = h / Xojo.view.devicePixelRatio; 
	}
	if(w==1 || h==1) {
		return;
	}
	image.style.width = w + 'px';
	image.style.height = h + 'px';
	
	switch (this.mVerticalAlignment) {
		case 0:
		case 2:
			// middle
			image.style.top = '50%';
			image.style.bottom = '';
			image.style.marginTop = '-' + (h / 2) + 'px';
			break;
		case 1:
			// top
			image.style.top = '0px';
			image.style.bottom = '';
			image.style.marginTop = '0px';
			break;
		case 3:
			// bottom
			image.style.top = '';
			image.style.bottom = '0px';
			image.style.marginTop = '0px';
			break;
	}
	
	switch (this.mHorizontalAlignment) {
		case 0:
		case 2:
			// center
			image.style.left = '50%';
			image.style.right = '';
			image.style.marginLeft = '-' + (w / 2) + 'px';
			break;
		case 1:
			// left
			image.style.left = '0px';
			image.style.right = '';
			image.style.marginLeft = '0px';
			break;
		case 3:
			// right
			image.style.left = '';
			image.style.right = '0px';
			image.style.marginLeft = '0px';
			break;
	}
};

imageview.prototype.setVerticalAlignment = function (value)
{
	this.mVerticalAlignment = value;
	this.refresh();
};

imageview.prototype.setHorizontalAlignment = function (value)
{
	this.mHorizontalAlignment = value;
	this.refresh();
};

imageview.prototype.touchBegin = function (event, coordinates)
{
	return true;
};

imageview.prototype.touchMove = function (event, coordinates)
{
	return true;
};

imageview.prototype.touchEnd = function (event, coordinates)
{
	return true;
};

imageview.prototype.setAppearance = function(value)
{
	var img = this.image();
	if(img) {
		if(value) {
			Xojo.DOM.removeClass(img,"disabled")
		} else {
			Xojo.DOM.addClass(img,"disabled")
		}
	}
};

imageview.prototype.setOpacity = function(value)
{
	this.object().style.setProperty("opacity",value,"important");
};

/* END: IMAGEVIEW */

/* BEGIN: RADIOGROUP */

// RadioGroup/framework.js
//
// Utility class for working with radiogroups
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function radiogroup (target, events)
{
	radiogroup.baseConstructor.call(this,target,events);
}
frameworkSubclass(radiogroup, frameworkObject);
radiogroup.prototype.selectedRow = 0;
radiogroup.prototype.selectedColumn = 0;

radiogroup.prototype.setCell = function (row, column)
{
	this.setValue(row + "," + column);
};

radiogroup.prototype.value = function ()
{
	return this.selectedRow + "," + this.selectedColumn;
};

radiogroup.prototype.setValue = function (input,value)
{
	if(value==undefined) value = true;
	var parts = input.split(",");
	var row = parts[0];
	var column = parts[1];
	var radio = document.getElementById(this.controlID + "_radio_" + row + "_" + column);
	
	if (radio) {
		if (radio.disabled) { return; }
		radio.checked = value;
	}
	
	var fireEvent = false;
	if(value) {
		this.selectedRow = row;
		this.selectedColumn = column;
		fireEvent = true;
	} else {
		radio = document.getElementById(this.controlID + "_radio_" + this.selectedRow + "_" + this.selectedColumn);
		if(radio) {
			radio.checked = false;
		}
		this.selectedRow = -1;
		this.selectedColumn = -1;
		fireEvent = true;
	}
	
	if(fireEvent) {
		markControlChanged(this);
		Xojo.comm.triggerEvent(this.controlID,'SelectionChanged');
	}
};

radiogroup.prototype.setEnabled = function (value)
{
	var radio, caption;
	var rowID,columnID;
	
	this.mEnabled = (value === true);
	
	rowID = 0;
	columnID = 0;
	do {
		radio = document.getElementById(this.controlID + "_radio_" + rowID + "_" + columnID);
		caption = document.getElementById(this.controlID + "_caption_" + rowID + "_" + columnID);
		if ((!radio) || (!caption)) {
			if (columnID === 0) {
				break;
			} else {
				columnID = 0;
				rowID++;
				continue;
			}
		}
		
		if(value) {
			Xojo.DOM.removeClass(caption,'disabled');
		} else {
			Xojo.DOM.addClass(caption,'disabled');
		}
		
		this.drawCellEnabled(rowID,columnID,value);
		
		columnID++;
	} while (true !== false);
};

radiogroup.prototype.setCellCaption = function (row, column, caption)
{
	var captionCell = document.getElementById(this.controlID + "_caption_" + row + "_" + column);
	if (!captionCell) {
		return;
	}
	
	captionCell.innerHTML = caption;
};

radiogroup.prototype.setCellVisible = function (row, column, visible)
{
	var radio = document.getElementById(this.controlID + "_radio_" + row + "_" + column);
	var captionCell = document.getElementById(this.controlID + "_caption_" + row + "_" + column);
	if ((!captionCell) || (!radio)) {
		return;
	}
	
	var display;
	if (visible === true) {
		display = 'visible';
	} else {
		display = 'hidden';
	}
	radio.style.visibility = display;
	captionCell.style.visibility = display;
};

radiogroup.prototype.setCellEnabled = function (row, column, enabled)
{
	var enabledInput = document.getElementById(this.controlID + "_enabled_" + row + "_" + column);
	if (!enabledInput) {
		return;
	}
	
	if (enabled) {
		enabledInput.value = 'true';
	} else {
		enabledInput.value = 'false';
	}
	
	this.drawCellEnabled(row,column,enabled);
};

radiogroup.prototype.drawCellEnabled = function (row, column, enabled)
{
	var radio = document.getElementById(this.controlID + "_radio_" + row + "_" + column);
	var captionCell = document.getElementById(this.controlID + "_caption_" + row + "_" + column);
	var enabledInput = document.getElementById(this.controlID + "_enabled_" + row + "_" + column);
	
	if ((!captionCell) || (!radio) || (!enabledInput)) {
		return;
	}
	
	enabled = (enabled) && (this.mEnabled) && (enabledInput.value == 'true');
	
	if (enabled) {
		radio.disabled = false;
		captionCell.style.opacity = '1.0';
		Xojo.DOM.removeClass(captionCell,'disabled');
	} else {
		radio.disabled = true;
		captionCell.style.opacity = '0.5';
		Xojo.DOM.addClass(captionCell,'disabled');
	}
};

radiogroup.prototype.setCursor = function(name) {
	this.object().style.cursor = name;
	var radio, caption;
	var rowID,columnID;
	
	rowID = 0;
	columnID = 0;
	do {
		radio = document.getElementById(this.controlID + "_radio_" + rowID + "_" + columnID);
		caption = document.getElementById(this.controlID + "_caption_" + rowID + "_" + columnID);
		if ((!radio) || (!caption)) {
			if (columnID === 0) {
				break;
			} else {
				columnID = 0;
				rowID++;
				continue;
			}
		}
		
		radio.style.cursor = name;
		caption.style.cursor = name;
		
		columnID++;
	} while (true !== false);
};

/* END: RADIOGROUP */

/* BEGIN: CHECKBOX */

// CheckBox/framework.js
//
// Utility class for working with checkboxes
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function checkbox (target, events)
{
	checkbox.baseConstructor.call(this,target,events);
	var box = document.getElementById(this.controlID + '_box');
	if (box) {
		if (box.checked) {
			this.mValue = true;
		} else {
			this.mValue = false;
		}
	}
	var cid = this.controlID;
	Xojo.events.addListener(box,"focus",function () { Xojo.input.setFocusControl(Xojo.controls[cid]) },false);
	Xojo.events.addListener(box,"blur",function () { Xojo.input.setFocusControl(null) },false);
	
	if(Xojo.input.isTouchUI()) {
		Xojo.events.addListener(this.object(), "touchstart", this.toggle, false);
		Xojo.events.addListener(box, "touchstart", this.toggle, false);
	}
}
frameworkSubclass(checkbox, frameworkObject);
checkbox.prototype.mValue = false;

checkbox.prototype.toggle = function (ev)
{
	if(ev) { ev.preventDefault(); }
	var controlID = this.controlID || this.id;
	var box = document.getElementById(controlID + '_box');
	if (!box) {
		return;
	}

	if (box.disabled === true) {
		return;
	}
	
	var ctl = Xojo.controls[controlID];
	if(ctl) {
		ctl.setValue(!ctl.value(),false);
	}
	return true;
};

checkbox.prototype.controlObject = function() {
	return document.getElementById(this.controlID + '_box');
}

checkbox.prototype.value = function ()
{
	return this.mValue;
};

checkbox.prototype.setValue = function (input,silent)
{
	if (typeof(silent) == 'undefined') {
		silent = false;
	}
	var box = document.getElementById(this.controlID + '_box');
	if (input === true) {
		box.checked = true;
		box.value = '1';
		this.mValue = true;
	} else {
		box.checked = false;
		box.value = '0';
		this.mValue = false;
	}
	if (silent === false) {
		markControlChanged(this);
		Xojo.comm.triggerEvent(this.controlID,'ValueChanged');
	}
};

checkbox.prototype.caption = function ()
{
	var caption = document.getElementById(this.controlID + '_caption');
	return caption.innerHTML;
};

checkbox.prototype.setCaption = function (input)
{
	var caption = document.getElementById(this.controlID + '_caption');
	caption.innerHTML = input;
};

checkbox.prototype.enabled = function ()
{
	var box = document.getElementById(this.controlID + '_box');
	if (box.disabled === true) {
		return false;
	} else {
		return true;
	}
};

checkbox.prototype.setEnabled = function (value)
{
	var box = document.getElementById(this.controlID + '_box');
	var caption = this.object("_caption");
	if (value === true) {
		box.disabled = false;
	} else {
		box.disabled = true;
	}
	
	if(value) {
		Xojo.DOM.removeClass(caption,'disabled');
	} else {
		Xojo.DOM.addClass(caption,'disabled');
	}
	
	this.setDisabledAppearance(!value);
	this.mEnabled = value;
};

checkbox.prototype.setCursor = function(name) {
	this.object("_caption").style.cursor = name;
	this.object("_box").style.cursor = name;
	this.object().style.cursor = name;
};

/* END: CHECKBOX */

/* BEGIN: SDK */

// SDK/framework.js
//
// Utility class for 3rd-Party web controls
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

// Xojo.loadLibraries - Loads a javascript library and fires a callback method when it has finished loading.
Xojo.loadedLibraries = [];
Xojo.loadLibraries = function(url, callback) {
	if(Xojo.loadedLibraries.indexOf(url)==-1) {
		var script = document.createElement("script");
		script.type = "text/javascript";
		if(script.readyState){ //IE
			script.onreadystatechange = function() {
				if(script.readyState == "loaded" || script.readystate=="complete") {
					script.onreadystatechange = null;
					Xojo.loadedLibraries[Xojo.loadedLibraries.length] = url;
					callback();
				}
			};
		} else { //Everyone Else
			script.onload = function() {
				Xojo.loadedLibraries[Xojo.loadedLibraries.length] = url;
				callback();
			}
		}
		
		script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		callback();
	}
};

// Xojo.createNamespace - Creates a new namespace if one does not already exist
var XojoCustom = XojoCustom || {};
Xojo.createNamespace = function() {
    var a=arguments, o=null, i, j, d;
    for (i=0; i<a.length; i=i+1) {
        d=(""+a[i]).split(".");
        o=XojoCustom;
		
        // XojoCustom is implied, so it is ignored if it is included
        for (j=(d[0] == "XojoCustom") ? 1 : 0; j<d.length; j=j+1) {
            o[d[j]]=o[d[j]] || {};
            o=o[d[j]];
        }
    }
    return o;
};

// Trigger an event on the server
Xojo.triggerServerEvent = function(controlId,eventName,userData) {
	Xojo.comm.triggerEvent(controlId,eventName,userData);
};

// Trigger an event on the browser
Xojo.triggerBrowserEvent = function(controlId,eventName,properties) {
	Xojo.events.fireEvent(controlId,eventName,properties);
};

// Add a cross-platform event listener
Xojo.addListener = function (el, eventName, fn) {
	if(typeof el == "string") { el = document.getElementById(el); }
	Xojo.events.addListener(el,eventName,fn,false);
};

// Remove a cross-platform event listener
Xojo.removeListener = function (el, eventName, fn) {
	if(typeof el == "string") { el = document.getElementById(el); }
	Xojo.events.removeListener(el,eventName,fn,false);
};

// Add a class
Xojo.addClass = function(el,className) {
	if(typeof el == "string") { el = document.getElementById(el); }
	Xojo.DOM.addClass(el,className);
};

// Remove a class
Xojo.removeClass = function(el,className) {
	if(typeof el == "string") { el = document.getElementById(el); }
	Xojo.DOM.removeClass(el,className);
};

// Check existence of a class
Xojo.hasClass = function(el,className) {
	if(typeof el == "string") { el = document.getElementById(el); }
	return Xojo.DOM.hasClass(el,className);
};

Xojo.get = function(controlId) {
	return document.getElementById(controlId);
};

Xojo.apiVersion = function() {
	return Xojo.apiVersionNum;
};

Xojo.addDragZone = function(controlID, mimeType, allowedAction, subControlID) {
	Xojo.dragDrop.addDragZone(controlID, mimeType, allowedAction, subControlID);
};

Xojo.removeDragZone = function(controlID, mimeType, subControlID) {
	Xojo.dragDrop.removeDragZone(controlID, mimeType, subControlID);
};

Xojo.addDropZone = function(controlID, mimeType, allowedAction, subControlID) {
	Xojo.dragDrop.addDropZone(controlID, mimeType, allowedAction, subControlID);
};

Xojo.removeDropZone = function(controlID, mimeType, subControlID) {
	Xojo.dragDrop.removeDropZone(controlID, mimeType, subControlID);
};

//RS Namespace function aliases for controls created in 2012r2
var RSCustom = XojoCustom;
var RS = {};
RS.loadLibraries = Xojo.loadLibraries;
RS.loadScript = Xojo.loadScript;
RS.createNamespace = Xojo.createNamespace;
RS.triggerServerEvent = Xojo.triggerServerEvent;
RS.triggerBrowserEvent = Xojo.triggerBrowserEvent;
RS.addListener = Xojo.addListener;
RS.removeListener = Xojo.removeListener;
RS.addClass = Xojo.addClass;
RS.removeClass = Xojo.removeClass;
RS.hasClass = Xojo.hasClass;
RS.get = Xojo.get;
RS.apiVersion = Xojo.apiVersion;

/* END: SDK */

/* BEGIN: PROGRESS */

// Progress/framework.js
//
// Utility class for working with Progress Indicators
//
// ©2016 Xojo Inc. -- All Rights Reserved
// This code contains patent-pending technology.

function progressbar (target, events)
{
	progressbar.baseConstructor.call(this,target,events);
	
	this.baseStyle = 'progressbar';
	this.object().className = 'progressbar';
	this.object().innerHTML = '<div class="fill">&nbsp;</div><div class="track">&nbsp;</div><div class="indeterminate">&nbsp;</div>';
	
	Xojo.view.sizing.targets.push(this);
	this.refresh();	
}
frameworkSubclass(progressbar, frameworkObject);
progressbar.prototype.mMaximum = 100;
progressbar.prototype.mValue = 0;
progressbar.prototype.mIndeterminate = false;
progressbar.prototype.timer = 0;
progressbar.prototype.mFrameNum = 0;

progressbar.prototype.step = function()
{
	var sign = "";
	try {
		if(this.mIndeterminate) {
			className = ".indeterminate";
			sign = "";
		} else {
			className = ".fill";
			sign = "-";
		}
		this.mFrameNum = (this.mFrameNum + 1) % 74;
		this.object().querySelector(className).style.backgroundPosition = sign + this.mFrameNum + "px 0px";
	} catch(err) {
		clearInterval(this.timer);
	}
};

progressbar.prototype.willRefresh = function ()
{
	var valuePercentage = 0;
	if (this.mMaximum > 0) {
		valuePercentage = (Math.min(this.mValue,this.mMaximum) / this.mMaximum);
	}
	
	var contentWidth = this.object().offsetWidth - 2;
	var fillWidth = Math.round(contentWidth * valuePercentage);
	var indeterminateWidth = 0;
	if (this.mIndeterminate) {
		indeterminateWidth = contentWidth;
		fillWidth = 0;
	}
	var trackWidth = contentWidth - fillWidth;
	
	trackWidth = Math.max(trackWidth,0);
	fillWidth = Math.max(fillWidth,0);
	
	var indeterminate = this.object().querySelector(".indeterminate");
	indeterminate.style.width = indeterminateWidth + "px";
	var fill = this.object().querySelector(".fill");
	fill.style.width = fillWidth + "px";
	var track = this.object().querySelector(".track");
	track.style.width = trackWidth + "px";
};

progressbar.prototype.value = function ()
{
	return this.mValue;
};

progressbar.prototype.setValue = function (value)
{
	var v = Math.min(Math.max(value,0),this.mMaximum);
	if (v != this.mValue) {
	this.mValue = v;
		this.refresh();
	}
};

progressbar.prototype.maximum = function ()
{
	return this.mMaximum;
};

progressbar.prototype.setMaximum = function (value)
{
	this.mMaximum = value;
	this.setValue(this.value());
};

progressbar.prototype.isIndeterminate = function ()
{
	return this.mIndeterminate;
};

progressbar.prototype.setIsIndeterminate = function (value)
{
	this.mIndeterminate = (value === true);
	this.refresh();
};

progressbar.prototype.setEnabled = function(value)
{
	var el = this.object().children[0];
	if(value) {
		Xojo.DOM.removeClass(el,"disabled");
	} else {
		Xojo.DOM.addClass(el,"disabled");
	}
	
	if (value) {
		this.timer = setInterval("Xojo.controls['" + this.controlID + "'].step()",60);
	} else if (this.timer !== 0) {
		clearInterval(this.timer);
	}
	
	this.setDisabledAppearance(! value);
	this.mEnabled = value;
};

/* END: PROGRESS */