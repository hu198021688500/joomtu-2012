/*
	Baidu Map
	author:zs.lin
	date:2001/10/21
*/
var BAIDUMAPCONTENT = "BAIDUMAPCONTENT";
var BAIDUPOINTS=[];


function AddBaiduMap(param){
	this.tId=param.txtId;
	this.pId=param.pageId;
	this.url=param.url;
	this.pt=param.pageType;
	this.markerArr=[];	
}

AddBaiduMap.prototype={
	init:function(){		  
		map.centerAndZoom(new BMap.Point(116.404, 39.915), 12);
		var opts = {type:BMAP_NAVIGATION_CONTROL_ZOOM };//添加缩放控件  
		map.addControl(new BMap.NavigationControl(opts));
		var _this=this;
		_this.loadJson();
		
	},
	//获取数据
	loadJson:function(){
		var _this=this;
		BAIDUPOINTS=[];
		$.getJSON(this.url,function(data){
				var len=data.length;
				if(_this.pt=="showBrand"){   //是否有分页
					len=len-1;
					var _c=data[len]['currentPage']-0; //current
					var _t=data[len]['totalPage']-0;   //total
					var _bt=data[len]['totalsize'];
					var html="";
					//添加分页
					if(_t>1){
						if(_c==1){
							html+="<a href='javascript:initMapAjax("+(_c+1)+");'>下一页</a>";			
						}else if(_c==_t){
							html+="<a href='javascript:initMapAjax("+(_c-1)+");'>上一页</a>";							
						}else if(_c>1 && _c<_t){
							html+="<a href='javascript:initMapAjax("+(_c-1)+");'>上一页</a>|<a href='javascript:initMapAjax("+(_c+1)+");'>下一页</a>";							
						}
					}
					if(html!=""){
							$('#'+_this.pId).show().find('dd').html(html); //添加分页
							$('#brandTotal').html('共'+_bt+'家分店');
					}			
				}
				
				$('#'+_this.tId).empty();
				//填入信息
				for(var i=0;i<len;i++){
					var v=data[i];
					var lng=v['latitude'].split('-')[0]; //纬度
					var lat=v['latitude'].split('-')[1]; //经度
					
					var _pt={
						'name':v['merchantName'],
						'address':v['addr'],
						'tel':v['tel'],
						'point':lng+','+lat	
					}
					
				
					BAIDUPOINTS.push(_pt);
						
					var point = new BMap.Point(lng,lat);
  				_this.addMarker(point,i,v['merchantName']);//添加标注
					
					var ifclass=i==0?'class="dlHover"':'';
					var str='';
					if(len==1){
						if(_this.tId=='mapMsg'){
							str="<li "+ifclass+"><span>"+v['merchantName']+"</span><div id='infoList"+i+"'><p><strong>店铺地址</strong>"+v['addr']+"</p><p><strong>联系电话</strong>"+v['tel']+"</p><p><strong>营业时间</strong>"+v['buinesstime']+"</p></div></li>";
							$('#mapDetail').attr('class','sp_detail_ditu2');
						}else{
							str="<li "+ifclass+"><span>"+v['merchantName']+"</span><div id='infoList"+i+"'><p>店铺地址："+v['addr']+"</p><p>联系电话："+v['tel']+"</p><p>营业时间："+v['buinesstime']+"</p></div></li>";
							$('#shangpuMap').attr('class','onlyone');
						}
					}else{
						if(_this.tId=='mapMsg'){
							str="<li "+ifclass+"><span onclick='bmap.showInfo(this)'>"+v['merchantName']+"</span><div id='infoList"+i+"'>店铺地址："+v['addr']+"<br/>联系电话："+v['tel']+"&nbsp;&nbsp;&nbsp;&nbsp;营业时间："+v['buinesstime']+"</div></li>";
						}else{
							str="<li "+ifclass+"><span onclick='bmap.showInfo(this)'>"+v['merchantName']+"</span><div id='infoList"+i+"'>店铺地址："+v['addr']+"<br/>联系电话："+v['tel']+"<br/>营业时间："+v['buinesstime']+"</div></li>";
						}			
						$('#mapDetail').attr('class','sp_detail_ditu');
						$('#shangpuMap').attr('class','')
					}
					
					$('#'+_this.tId).append(str);
				}
				showBigBMap(BAIDUPOINTS);
		});	
	},
	//添加标注
	addMarker:function(point,num,msg){
		this.markerArr[num] = new BMap.Marker(point);
  		map.addOverlay(this.markerArr[num]);
		if(num==0){
			this.markerArr[num].setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画	
			map.panTo(point); 
		}
		this.markerArr[num].setTitle(msg);
		this.markerArr[num].id='marker_'+num;
		var _this=this;
		this.markerArr[num].addEventListener('click',function(e){
			var num=(this.id.replace(/\D/g,'')).toString();
			_this.showInfo(num);	
		});	
	},
	//信息框展示
	showInfo:function(param){
		var $currentDD,cNum;
		if(typeof(param)=='string'){
			$currentDD=$('#infoList'+param);
			cNum=param;		
		}else{
			$currentDD=$(param).next('div');
			cNum=$currentDD.attr('id').replace(/\D/g,'');
		}
		
		var $dd=$('#'+this.tId+' div');
		var id;
		for(var i=0;i<$dd.length;i++){
			if(!$dd.eq(i).is(':hidden')){
				id=$dd.eq(i).attr('id');
			}	
		}
		var num=id.replace(/\D/g,'');
		
		
		if(id!=$currentDD.attr('id')){
			var $focus=$('#'+id);
			$focus.slideUp(400);
			$focus.parent().attr('class','');
			$currentDD.slideDown(400);
			$currentDD.parent().attr('class','dlHover');					
			
			var _ll=(this.markerArr[cNum]).getPosition();
			//map.setZoom(15);
			map.panTo(new BMap.Point(_ll.lng, _ll.lat)); 
			this.markerArr[num].setAnimation(null);
			this.markerArr[cNum].setAnimation(BMAP_ANIMATION_BOUNCE);
		}
	}	
}; 

/*big map start*/
var Fe = Fe || {
		version: "20080809",
		emptyFn: function() {}
	};
	Fe.G = function() {
		for (var a = [], i = arguments.length - 1; i > -1; i--) {
			var e = arguments[i];
			a[i] = null;
			if (typeof e == "object" && e && e.dom) {
				a[i] = e.dom
			} else {
				if ((typeof e == "object" && e && e.tagName) || e == window || e == document) {
					a[i] = e
				} else {
					if (typeof e == "string" && (e = document.getElementById(e))) {
						a[i] = e
					}
				}
			}
		}
		return a.length < 2 ? a[0] : a
	};
	//绑定事件
	Fe.on = function(el, type, handler) {
		if (! (el = Fe.G(el))) {
			return el
		}
		type = type.replace(/^on/, "").toLowerCase();
		if (el.attachEvent) {
			el[type + handler] = function() {
				handler.call(el, window.event)
			};
			el.attachEvent("on" + type, el[type + handler])
		} else {
			el.addEventListener(type, handler, false)
		}
		return el
	};
	//addClass
	Fe.ac = function(element, className) {
		if (! (element = this.G(element))) {
			return
		}
		className = this.trim(className);
		if (!new RegExp("(^| )" + className.replace(/(\W)/g, "\\$1") + "( |$)").test(element.className)) {
			element.className = element.className.split(/\s+/).concat(className).join(" ")
		}
	};
	Fe.addClassName = Fe.ac;
	//removeClass
	Fe.rc = function(element, className) {
		if (! (element = this.G(element))) {
			return
		}
		className = this.trim(className);
		var c = element.className.replace(new RegExp("(^| +)" + className.replace(/(\W)/g, "\\$1") + "( +|$)", "g"), "$2");
		if (element.className != c) {
			element.className = c
		}
	};
	Fe.removeClassName = Fe.rc;
	Fe.trim = function(str) {
		return str.replace(/(^[\s\t\xa0\u3000]+)|([\u3000\xa0\s\t]+$)/g, "")
	};
var TUANGOU = {
		baseUrl: "http://map.baidu.com/fwmap/upload/r/map/fwmap/tuangou/",
		mapContent: window.BAIDUMAPCONTENT || "",
		_points: window.BAIDUPOINTS || [],
		points: [],
		mapType: "CUSTOMERS",
		mapInfo: {
			center: {
				x: 116.395645,
				y: 39.929986
			},
			zoom: 12
		},
		dom: {},
		id: {
			1 : {
				mapConIdNameBak: "BAIDUMAPTGBAK",
				mapConListIdNameBak: "BAIDUMAPTGBAKLIST",
				mapBoxButtonIdName: "BAIDUMAPBOXBUTTON",
				smapLookBigMap: "smapLookBigMap",
				iw_poi_inter: "iw_poi_inter",
				iw_ssn: "iw_ssn",
				iw_sbus_btn: "iw_sbus_btn",
				iw_ssd_btn: "iw_ssd_btn",
				iw_esn: "iw_esn",
				iw_ebus_btn: "iw_ebus_btn",
				iw_esd_btn: "iw_esd_btn",
				rangekw: "rangekw",
				range: "range",
				nav_tab: "nav_tab",
				iw_cate_list: "iw_cate_list"
			},
			3 : {
				nav_tab: "nav_tab",
				iw_tab: "iw_tab"
			},
			10 : {
				listMarker: "BDMKLIST",
				listContent: "listContent",
				ranget: "ranget"
			}
		},
		domStyle: {
			tgPadding: 18,
			tgBorder: 0,
			listWidth: 255,
			listBorder: 0,
			mapWidth: 818,
			mapHeight: 500,
			mapBorder: 1,
			mapRight: 15,
			boxTitleHei: 32,
			tipHeight: 19,
			tipLineHeight: 25
		},
		style: {},
		style1: {},
		API: {},
		isMapShow: 0,
		temp: {
			_openIndex: null,
			_listShowIndex: null,
			points: [],
			markers: [],
			iws: []
		}
	};

function showBigBMap(arr) {
	var init = function() {
		var t = TUANGOU;
		if (Fe.G(t.mapContent)) {
			testIdAll();
			//loadApi();//跳过
			checkPoints(); //接受坐标数据
			createSMapContent(); //创建小地图？ 好吧，主要是为了绑定事件
		}
	};
	//初始化
	init();
	function checkPoints() {
		var t = TUANGOU;
		t._points=arr;
		var _pts = t._points;
		for (var i = 0; i < _pts.length; i++) {
			if (t.points.length > 9) {
				break
			}
			var _pt = _pts[i];
			if (_pt.name && _pt.address && _pt.point) {
				t.points.push(_pt); //取10个
			}
		}
	}
	function createSMapContent() {
		/*
		var t = TUANGOU,
		td = t.dom,
		tid1 = t.id[1];
		var content = document.createElement("div");
		//var mapcontent = document.createElement("div");
		var navcontent = document.createElement("div");
		content.style.border = "#b6b2b1 solid 1px";
		content.style.width = "198px";
		content.style.height = "226px";
		*/
		//if (t.API.loaded) {
			bindSMEvent()
		//} 
	}
	function createSMap() {
		var t = TUANGOU,
		td = t.dom,
		m = t.mapInfo;
		var map = new BMap.Map(td.smap);
		var point = new BMap.Point(m.center.x, m.center.y);
		map.centerAndZoom(point, m.zoom);
		t.smap = map;
		addSMarker()
	}
	function addSMarker() {
		var t = TUANGOU,
		tps = t.points,
		_pts = [];
		if (tps.length > 0) {
			for (var i = 0; i < tps.length; i++) {
				var p = tps[i].point.split(",");
				var tit = tps[i].name || "";
				if (p[0] && p[1]) {
					var pt = new BMap.Point(p[0], p[1]);
					var mk = new BMap.Marker(pt, {
						icon: new BMap.Icon(t.baseUrl + "img/tg-markers.png", new BMap.Size(23, 25), {
							imageOffset: new BMap.Size(0, -25 * i),
							infoWindowOffset: new BMap.Size(10, 0)
						})
					});
					mk.setTitle(tit);
					t.smap.addOverlay(mk);
					_pts.push(pt)
				}
			}
			t.smap.setViewport(_pts)
		}
	}
	function createCss() {
		var t = TUANGOU,
		tds = t.domStyle;
		var css = {
			tg: {
				width: tds.mapWidth + 2 * tds.mapBorder + "px",
				height: tds.mapHeight + 2 * tds.mapBorder + "px",
				border: "#ccc solid " + tds.tgBorder + "px",
				position: "relative",
				padding: tds.tgPadding + "px",
				paddingRight: tds.tgPadding + tds.listWidth + tds.mapRight + "px"
			},
			list: {
				width: tds.listWidth + "px",
				height: tds.mapHeight + "px",
				border: "#f00 solid " + tds.tgBorder + "px",
				position: "absolute",
				right: tds.tgPadding + "px",
				top: tds.tgPadding + "px"
			},
			map: {
				width: tds.mapWidth + "px",
				height: tds.mapHeight + "px",
				border: "#b7b2b9 solid " + tds.mapBorder + "px"
			},
			tip: {
				color: "#959595",
				height: tds.tipHeight + "px",
				lineHeight: tds.tipLineHeight + "px",
				fontSize: "12px"
			}
		};
		var css1 = {
			mask: {
				background: "#000",
				opacity: 0.4,
				zIndex: 99999
			},
			boxCUSTOMERS: {
				width: tds.listWidth + tds.mapWidth + 2 * tds.mapBorder + tds.mapRight + 2 * tds.tgPadding,
				height: tds.mapHeight + 2 * tds.mapBorder + 2 * tds.tgPadding + tds.boxTitleHei + tds.tipHeight,
				title: "\u67e5\u770b\u5168\u56fe",
				zIndex: 999999
			}
		};
		t.style = css;
		t.style1 = css1
	}
	function showUserMap() {
		var t = TUANGOU,
		td = t.dom,
		tid1 = t.id[1];
		if ( !! t.isMapShow) { //如果不存在则终止
			return
		}
		t.isMapShow = 1;
		if (td.popmask && td.popbox && td.map) {
			td.popmask.style.display = "block";
			td.popbox.style.display = "block";
			td.tg.style.display = "block"
		} else {
			createCss();
			showPopBox();
			createMap();
			createListHtml();
		}
	}
	
	function testIdAll() {
		var t = TUANGOU,
		tid = t.id;
		for (var i in tid) {
			for (var j in tid[i]) {
				tid[i][j] = testId(tid[i][j], i)
			}
		}
	}
	function testId(id, num) {
		var id = id;
		if (num) {
			var go = true;
			while (go) {
				var stop = true;
				for (var i = 0; i < num; i++) {
					if (Fe.G(id + "_" + i)) {
						id += "_";
						stop = false;
						break
					}
				}
				if (stop) {
					go = false
				}
			}
		} else {
			while (Fe.G(id)) {
				id += "_"
			}
		}
		return id
	}
	function createMapContent() {
		var t = TUANGOU,
		d = t.dom,
		tds = t.domStyle,
		tid1 = t.id[1],
		s = t.style,
		dom;
		if (d.tg && d.list && d.map) {
			dom = {
				tg: d.tg,
				list: d.list,
				map: d.map
			}
		} else {
			var tg = document.createElement("div");
			var list = document.createElement("div");
			var map = document.createElement("div");
			map.id="qusibabaidu";
			var tip = document.createElement("div");
			tip.innerHTML = "\u63d0\u9192\uff1a\u5730\u56fe\u6807\u6ce8\u4f4d\u7f6e\u4ec5\u4f9b\u53c2\u8003\uff0c\u5177\u4f53\u60c5\u51b5\u4ee5\u5b9e\u9645\u9053\u8def\u6807\u8bc6\u4fe1\u606f\u4e3a\u51c6";
			tg.appendChild(list);
			tg.appendChild(map);
			tg.appendChild(tip);
			tg.id = tid1.mapConIdNameBak;
			list.id = tid1.mapConListIdNameBak;
			list.style.overflow="auto";
			if (d.popbox) {
				d.popbox.appendChild(tg)
			} else {
				document.getElementById('bMapContainer').appendChild(tg)
			}
			var domObj = {
				tg: tg,
				list: list,
				map: map,
				tip: tip
			};
			for (var i in s) {
				for (var j in s[i]) {
					domObj[i].style[j] = s[i][j]
				}
			}
			d.tg = tg;
			d.list = list;
			d.map = map;
			d.tip = tip;
			dom = {
				tg: tg,
				list: list,
				map: map
			}
		}
		return dom
	}
	function createListHtml() {
		var str = "<ul>",
		t = TUANGOU,
		tpts = t.points,
		d = t.dom,
		tid1 = t.id[1],
		tid10 = t.id[10],
		tgeo = t.geoCoder,
		temp = t.temp;
		if (tpts.length < 1) {
			str += "<li>\u6ca1\u6709\u6570\u636e</li>"; //没有数据
		} else {
			for (var i = 0; i < tpts.length; i++) {
				var tit = getStr(tpts[i].name) || "";
				var add = getStr(tpts[i].address) || "";
				var tel = getStr(tpts[i].tel) || "";
				var point = getStr(tpts[i].point) || "";
				var havePoint = false;
				if (tit.length < 1) {
					continue
				}
				if (point) {
					var _p = point.split(",");
					point = new BMap.Point(_p[0], _p[1]);
					havePoint = true
				}
				var _tit = tit;
				if (_tit.length > 11) {
					_tit = tit.substring(0, 8) + "..."
				}
				str += '<li><span id="' + tid10.listMarker + "_" + i + '"></span><div id="' + tid10.listContent + "_" + i + '"><h3>' + _tit + "</h3><p>";
				if ( !! add) {
					str += "<em><b>\u5730\u5740\uff1a</b>" + add + "</em>"
				}
				if ( !! tel) {
					str += "<em><b>\u7535\u8bdd\uff1a</b>" + tel + "</em>"
				}
				str += "</p></div></li>"; (function() {
					var ind = i;
					var createAndDo = function(pt) {
						var ptStr = pt.lng + "," + pt.lat;
						var mk = addMarker(pt, ind);
						var iw = createIw({
							tit: tpts[ind].name,
							add: tpts[ind].address,
							tel: tpts[ind].tel,
							point: ptStr
						});
						if (!havePoint) {
							tpts[ind].point = ptStr
						}
						temp.points.push(pt);
						temp.markers.push(mk);
						temp.iws.push(iw)
					};
					if (add && havePoint) {
						createAndDo(point)
					}
				})()
			}
		}
		str += "</ul>";
		d.list.innerHTML = str;
		if (tpts.length > 0) {
			t.map.setViewport(temp.points);
			bindEvent()
		}
	}
	function bindEvent() {
		var t = TUANGOU,
		tid1 = t.id[1],
		tid3 = t.id[3],
		tid10 = t.id[10],
		tpts = t.points,
		temp = t.temp;
		var clickFun = function(mk, iw, ind) {
			if (temp._openIndex == ind) {
				return
			}
			if (typeof temp._openIndex != "undefined" && Fe.G(tid10.listContent + "_" + temp._openIndex) && Fe.G(tid10.listMarker + "_" + temp._openIndex)) {
				var _ind = temp._openIndex;
				var mk1 = temp.markers[_ind];
				outFun(mk1, null, _ind, "click")
			}
			temp._openIndex = ind;
			var icon = mk.getIcon();
			Fe.G(tid10.listContent + "_" + ind).className = "on";
			icon.setImageOffset(new BMap.Size(0, -250 - ind * 25));
			mk.setIcon(icon);
			mk.openInfoWindow(iw)
		};
		var overFun = function(mk, iw, ind) {
			if (temp._openIndex == ind) {
				return
			}
			var icon = mk.getIcon();
			icon.setImageOffset(new BMap.Size(0, -250 - ind * 25));
			mk.setIcon(icon);
			mk.setTop(true, 1000100);
			Fe.G(tid10.listContent + "_" + ind).className = "hover";
			Fe.G(tid10.listMarker + "_" + ind).style.backgroundPosition = "-23px " + ( - 250 - ind * 25) + "px"
		};
		var outFun = function(mk, iw, ind, from) {
			if (from != "click" && temp._openIndex == ind) {
				mk.setTop(true);
				return
			}
			var icon = mk.getIcon();
			icon.setImageOffset(new BMap.Size(0, -ind * 25));
			mk.setIcon(icon);
			mk.setTop(false);
			Fe.G(tid10.listContent + "_" + ind).className = "";
			Fe.G(tid10.listMarker + "_" + ind).style.backgroundPosition = "-23px " + ( - ind * 25) + "px"
		};
		var iwOpen = function(mk, iw, ind) {
			var pt = tpts[ind].point.split(",");
			//var _pt = t.map.lngLatToPoint(pt[0], pt[1]);
			var projection = new BMap.MercatorProjection();
			var _points = projection.lngLatToPoint(new BMap.Point(pt[0], pt[1]));
			var _pt=[_points.x,_points.y]
			var json = {
				name: tpts[ind].name,
				x:_pt[0],
				y:_pt[1]
				//citycode: tpts[ind].citycode
			};
			for (var i = 0; i < 3; i++) { (function() {
					var ind = i;
					Fe.on(tid3.nav_tab + "_" + ind, "click",
					function() {
						switchNavTab(ind)
					})
				})()
			}
			Fe.on(Fe.G(tid1.iw_sbus_btn), "click",
			function() {
				getRoute("bus", "en", tid1.iw_ssn, json)
			});
			Fe.on(Fe.G(tid1.iw_ssd_btn), "click",
			function() {
				getRoute("nav", "en", tid1.iw_ssn, json)
			});
			Fe.on(Fe.G(tid1.iw_ebus_btn), "click",
			function() {
				getRoute("bus", "sn", tid1.iw_esn, json)
			});
			Fe.on(Fe.G(tid1.iw_esd_btn), "click",
			function() {
				getRoute("nav", "sn", tid1.iw_esn, json)
			});
			for (var i = 0; i < 5; i++) { (function() {
					var ind = i;
					Fe.on(Fe.G(tid10.ranget + "_" + ind), "click",
					function() {
						getCircle(this.innerHTML, json)
					})
				})()
			}
			Fe.on(Fe.G(tid1.range), "click",
			function() {
				getCircle(Fe.G(tid1.rangekw).value, json)
			})
		};
		for (var i = 0; i < temp.markers.length; i++) { (function() {
				var ind = i;
				var mk = temp.markers[ind];
				var iw = temp.iws[ind];
				if (mk && iw) {
					mk.addEventListener("click",
					function() {
						clickFun(mk, iw, ind)
					});
					mk.addEventListener("mouseover",
					function() {
						overFun(mk, iw, ind)
					});
					mk.addEventListener("mouseout",
					function() {
						outFun(mk, iw, ind)
					});
					iw.addEventListener("open",
					function() {
						temp._openIndex = ind;
						iwOpen(mk, iw, ind)
					});
					iw.addEventListener("close",
					function() {
						temp._openIndex = null;
						outFun(mk, iw, ind)
					});
					Fe.on(tid10.listMarker + "_" + ind, "click",
					function() {
						clickFun(mk, iw, ind)
					});
					Fe.on(tid10.listMarker + "_" + ind, "mouseover",
					function() {
						overFun(mk, iw, ind)
					});
					Fe.on(tid10.listMarker + "_" + ind, "mouseout",
					function() {
						outFun(mk, iw, ind)
					});
					Fe.on(tid10.listContent + "_" + ind, "mouseover",
					function() {
						overFun(mk, iw, ind)
					});
					Fe.on(tid10.listContent + "_" + ind, "mouseout",
					function() {
						outFun(mk, iw, ind)
					});
					Fe.on(tid10.listContent + "_" + ind, "click",
					function() {
						clickFun(mk, iw, ind)
					})
				}
			})()
		}
	}
	function bindSMEvent() {
		var t = TUANGOU,
		tid1 = t.id[1];
		if (Fe.G(tid1.smapLookBigMap)) {
			/*
			Fe.on(tid1.smapLookBigMap, "click",
			function() {
				showUserMap();
			})
			*/
			var obj='#'+tid1.smapLookBigMap;
			$(obj).unbind('click').bind('click',function(){
				showUserMap();
			});
		}
	}
	function createMap() {
		var t = TUANGOU;
		var m = t.mapInfo;
		var mapDom = createMapContent().map;
		mapDom.style.display = "block";
		//alert(mapDom.id);
		var map = new BMap.Map(mapDom);
		var point = new BMap.Point(m.center.x, m.center.y);
		map.centerAndZoom(point, m.zoom);
		var opts = {type:BMAP_NAVIGATION_CONTROL_LARGE};//添加缩放控件  
		map.addControl(new BMap.NavigationControl(opts));
		t.map = map
	}
	function addMarker(pt, ind) {
		var t = TUANGOU,
		mk = new BMap.Marker(pt, {
			icon: new BMap.Icon(t.baseUrl + "img/tg-markers.png", new BMap.Size(23, 25), {
				imageOffset: new BMap.Size(0, -25 * ind),
				infoWindowOffset: new BMap.Size(10, 0)
			})
		});
		t.map.addOverlay(mk);
		return mk
	}
	function createIw(o) {
		var t = TUANGOU,
		tid1 = t.id[1],
		tid3 = t.id[3],
		tid10 = t.id[10],
		tit = o.tit;
		if (tit.length > 13) {
			tit = o.tit.substring(0, 11) + "..."
		}
		var title = '<span style="color:#CC5522;font-size:14px;font-weight:700" title="' + o.tit + '">' + tit + "</span>";
		var content = '<p style="font-size:12px;color:#323232;margin:5px 0 0 0;line-height:18px;padding-left:40px;">';
		if (o.add) {
			content += '<em style="font-style:normal;margin-left:-40px">\u5730\u5740\uff1a</em>' + o.add + "<br />";
			if (o.tel) {
				content += '<em style="font-style:normal;margin-left:-40px">\u7535\u8bdd\uff1a</em>' + o.tel + "<br />"
			}
			content += "</p>"
		}
		content += '<div id="' + tid1.iw_poi_inter + '" class="iw_poi_inter"><div id="' + tid1.nav_tab + '"><span class="first hover" id="' + tid3.nav_tab + '_0">\u5230\u8fd9\u91cc\u53bb</span><span class="second " id="' + tid3.nav_tab + '_1">\u4ece\u8fd9\u91cc\u51fa\u53d1</span><span class="third " id="' + tid3.nav_tab + '_2">\u5728\u9644\u8fd1\u627e</span></div><div style="display: block;" class="nav_tab_content" id="' + tid3.iw_tab + '_0"><div><span class="lef">\u8d77\u70b9\uff1a</span><input type="text" autocomplete="off" maxlength="100" size="22" id="' + tid1.iw_ssn + '"><input type="submit" class="bt" value="\u516c\u4ea4"  id="' + tid1.iw_sbus_btn + '"><input type="button" value="\u9a7e\u8f66" class="bt" id="' + tid1.iw_ssd_btn + '"></div></div><div style="display: none;" class="nav_tab_content" id="' + tid3.iw_tab + '_1"><div><span class="lef">\u7ec8\u70b9\uff1a</span><input type="text" autocomplete="off" maxlength="100" size="22" id="' + tid1.iw_esn + '"><input type="submit" class="bt" value="\u516c\u4ea4" id="' + tid1.iw_ebus_btn + '"><input type="button" class="bt" value="\u9a7e\u8f66" id="' + tid1.iw_esd_btn + '"></div></div><div style="display: none;" class="nav_tab_content" id="' + tid3.iw_tab + '_2"><div id="' + tid1.iw_cate_list + '"><a class="first" id="' + tid10.ranget + '_0" href="javascript:void(0)">ATM</a><a id="' + tid10.ranget + '_1" href="javascript:void(0)">\u94f6\u884c</a><a id="' + tid10.ranget + '_2" href="javascript:void(0)">\u5bbe\u9986</a><a id="' + tid10.ranget + '_3" href="javascript:void(0)">\u9910\u9986</a><a id="' + tid10.ranget + '_4" href="javascript:void(0)">\u516c\u4ea4\u7ad9</a></div><div>\u5176\u4ed6\uff1a<input type="text" autocomplete="off" maxlength="100" size="19" id="' + tid1.rangekw + '"> <input type="submit" class="bt" value="\u641c\u7d22" id="' + tid1.range + '"></div></div></div>';
		var iw = new BMap.InfoWindow(content, {
			title: title,
			width: 304
		});
		return iw
	}
	function switchNavTab(no) {
		var t = TUANGOU,
		tid3 = t.id[3];
		for (var i = 0; i < 3; i++) {
			Fe.removeClassName(Fe.G(tid3.nav_tab + "_" + i), "hover");
			Fe.G(tid3.iw_tab + "_" + i).style.display = "none"
		}
		Fe.addClassName(Fe.G(tid3.nav_tab + "_" + no), "hover");
		Fe.G(tid3.iw_tab + "_" + no).style.display = ""
	}
	function getRoute(type, uidType, txtObj, json) {
		var name = json.name,
		x = json.x,
		y = json.y,
		citycode = json.citycode;
		if (Fe.G(txtObj).value == "") {
			return
		}
		var qStr = [],
		qString = "";
		type == "bus" ? qStr.push("bse") : qStr.push("nse");
		qStr.push("&c=" + citycode);
		qStr.push("&wd=" + Fe.G(txtObj).value);
		qStr.push("&isSingle=true");
		uidType == "en" ? qStr.push("&t=0") : qStr.push("&t=1");
		qStr.push("&name=" + name);
		qStr.push("&uid=1");
		qStr.push("&ptx=" + x);
		qStr.push("&pty=" + y);
		qStr.push("&poiType=0");
		qStr.push("&" + uidType + "=1$$1$$" + x + "," + y + "$$" + name + "$$$$$$");
		qString = qStr.join("");
		qStr = [];
		qStr.push(qString);
		qStr.push("&req=" + encodeURIComponent(qString));
		qString = "http://map.baidu.com/?newmap=1&s=";
		qString += encodeURIComponent(qStr.join(""));
		window.open(qString)
	}
	function getCircle(va, json) {
		if (va == "") {
			return
		}
		if (va == "\u67e5\u627e\u5176\u4ed6\u5173\u952e\u5b57") {
			Fe.G("circleText").focus();
			return
		}
		var t = TUANGOU,
		x = json.x,
		y = json.y,
		citycode = json.citycode,
		qStr = "http://map.baidu.com/?";
		var l, b, _b;
		if (!x || !y) {
			x = map.getCenter().lng;
			y = map.getCenter().lat
		}
		l = 15;
		b = t.map.getBounds();
		_b = b.minX + "," + b.minY + ";" + b.maxX + "," + b.maxY;
		qStr += "l=" + l + "&c=" + x + "," + y + "&i=-1|-1|-1&s=" + encodeURIComponent("tpl:PoiSearch?nb&ar=(" + _b + ")&wd=" + va + "&c=" + citycode + "&bdtp=0&nb_x=" + x + "&nb_y=" + y + "&l=" + l + "&r=2000") + "&sc=0";
		window.open(qStr)
	}
	function setMaskResize() {
		var t = TUANGOU,
		d = t.dom;
		if (d.popmask) {
			var ps = d.popmask;
			var b = getBrowserSize();
			ps.style.width = b.width + "px";
			ps.style.height = b.height + "px"
		}
	}
	function setMaskScroll() {
		var t = TUANGOU,
		d = t.dom;
		if (d.popmask) {
			var ps = d.popmask;
			ps.style.setExpression("top", "documentElement.scrollTop");
			ps.style.setExpression("left", "documentElement.scrollLeft")
		}
	}
	function setBoxPosition() {
		var t = TUANGOU,
		d = t.dom;
		if (d.popbox) {
			var dom = d.popbox;
			dom.style.top = getBoxPosition().top + "px";
			dom.style.left = getBoxPosition().left + "px"
		}
	}
	function getBoxPosition() {
		var left, top, t = TUANGOU,
		wh = getBrowserSize(),
		_sb = t.style1["box" + t.mapType];
		left = (Math.round((wh.width - _sb.width) / 2) > 0 ? Math.round((wh.width - _sb.width) / 2) : 0) + wh.left;
		top = (Math.round((wh.height - _sb.height) / 2) > 0 ? Math.round((wh.height - _sb.height) / 2) : 0) + wh.top;
		return {
			left: left,
			top: top
		}
	}
	function showPopBox() {
		var t = TUANGOU,
		d = t.dom,
		tid1 = t.id[1],
		tds = t.domStyle,
		pm,pb,
		_sm = t.style1.mask,
		_sb = t.style1["box" + t.mapType];
		if (d.popmask && d.popbox) {
			pm = d.popmask;
			pb = d.popbox
		} else {
			pm = document.createElement("div");
			pb = document.createElement("div");
			var s = '<div style="position:relative;height:' + tds.boxTitleHei + "px;background:#e0e8f5;color:#5382ca;font-size:14px;font-weight:700;line-height:" + tds.boxTitleHei + 'px;padding-left:10px;" title=' + _sb.title + '><span style="position:absolute;right:9px;top:8px;width:16px;height:16px;background:url(' + t.baseUrl + 'img/tg-box-close.png) 0 0 no-repeat;cursor:pointer;" id="' + tid1.mapBoxButtonIdName + '"></span>' + _sb.title + "</div>";
			pb.innerHTML = s;
			var sm = pm.style,
			sb = pb.style,
			wh = getBrowserSize();
			if (browser().ie && browser().ie <= 6 || !browser().isStrict) {
				sm.position = "absolute";
				sm.width = wh.width;
				sm.height = wh.height;
				sm.top = wh.top;
				sm.left = wh.left
			} else {
				sm.position = "fixed";
				sm.width = "100%";
				sm.height = "100%";
				sm.top = "0";
				sm.left = "0"
			}
			sb.position = "absolute";
			sb.top = getBoxPosition().top + "px";
			sb.left = getBoxPosition().left + "px";
			sb.width = _sb.width + "px";
			sb.height = _sb.height + "px";
			sb.background = "#fff";
			sb.zIndex = _sb.zIndex;
			sb.overflow = "hidden";
			sm.background = _sm.background;
			sm.opacity = _sm.opacity;
			sm.zIndex = _sm.zIndex;
			if (browser().ie) {
				sm.filter = "alpha(opacity=" + _sm.opacity * 100 + ")"
			}
			document.getElementById('bMapContainer').appendChild(pm);
			document.getElementById('bMapContainer').appendChild(pb);
			d.popmask = pm;
			d.popbox = pb
		}
		pm.style.display = "block";
		if (browser().ie && browser().ie <= 6 || !browser().isStrict) {
			var body = document.body;
			body.style.backgroundAttachment = "fixed";
			if (body.currentStyle && body.currentStyle.backgroundImage == "none") {
				body.style.backgroundImage = (document.domain.indexOf("https:") == 0) ? "url(https:///)": "url(about:blank)"
			}
			Fe.on(window, "resize", setMaskResize);
			Fe.on(window, "scroll", setMaskScroll)
		}
		Fe.on(window, "resize", setBoxPosition);
		Fe.on(tid1.mapBoxButtonIdName, "click", hidePopBox)
	}
	function hidePopBox() {
		var t = TUANGOU,
		temp = t.temp,
		d = t.dom;
		if (d.popmask) {
			d.popmask.style.display = "none"
		}
		if (d.popbox) {
			d.popbox.style.display = "none"
		}
		t.isMapShow = 0
	}
	function getBrowserSize() {
		var width, height, top, left;
		var D = document,
		A = D.compatMode == "BackCompat" ? D.body: D.documentElement;
		width = A.clientWidth;
		height = A.clientHeight;
		top = D.documentElement.scrollTop || D.body.scrollTop;
		left = D.documentElement.scrollLeft || D.body.scrollLeft;
		return {
			width: width,
			height: height,
			top: top,
			left: left
		}
	}
	function browser() {
		var isStrict = document.compatMode == "CSS1Compat";
		if (/msie (\d+\.\d)/i.test(navigator.userAgent)) {
			var ie = document.documentMode || parseFloat(RegExp["\x241"])
		}
		return {
			isStrict: isStrict,
			ie: ie
		}
	}
	function filterSpace(str) {
		return str.replace(/^\s*|\s*$/, "")
	}
	function filterTags(str) {
		return str.replace(/<.*?>/, "")
	}
	function getStr(str) {
		var str = str || "";
		return filterTags(filterSpace(str))
	}
}