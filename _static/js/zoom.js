$.fn.extend({flyout : function(options) {
		var shown=false;
		var animating=false;
		var $holder;
		var $thumb;
		var tloc;
		var th;
		var tw;
		var bigimg = new Image();
		var subType = 'img';
		var offset;
		this.click(function() {
			if (animating == true) { return false; }
	
			if (shown) { putAway(this); }
			else { flyOut(this); }
	
			return false;
		});
		var o = jQuery.extend({
			outSpeed : 1000,
			inSpeed : 500,
			outEase : 'swing',
			inEase : 'swing',
			loadingSrc: null,
			loader: 'loader',
			loaderZIndex: 500,
			widthMargin: 40,
			heightMargin: 40,
			loadingText : "加载中...",
			closeTip : "点击关闭",
			destPadding: 20,
			startOffsetX: 0,
			startOffsetY: 0,
			startHeight: 0,
			startWidth: 0,
			flyOutStart: function() {},
			flyOutFinish: function() {},
			putAwayStart: function() {},
			putAwayFinish: function() {},
			shownClass: 'shown'
		}, options);
		function flyOut(it) {
			animating = true;
			
			$holder = $(it);
			$thumb = $('img',it);
			bigimg = new Image(); 
			sL = $(window).scrollLeft();
			sT = $(window).scrollTop();
			tloc = $thumb.offset();
			tloc.left += o.startOffsetX;
			tloc.top += o.startOffsetY;
			th = (o.startHeight > 0 ? o.startHeight : $thumb.height());
			tw = (o.startWidth > 0 ? o.startWidth : $thumb.width());
			$('<div></div>').attr('id',o.loader)
							.appendTo('body')
							.css({'position':'absolute',
								'top':tloc.top,
								'left':tloc.left,
								'height':th,
								'width':tw,
								'opacity':.5,
								'display':'block',
								'z-index':o.loaderZIndex});
			if (o.loadingSrc) {
				$('#'+o.loader).append($('<img/>')
								.load(function() {
										$(this)
											.css({'position':'relative',
												 'top':th/2-(this.height/2),
												 'left':tw/2-(this.width/2)})
											.attr('alt',o.loadingText);
										})
									.attr('src',o.loadingSrc)
								);
			}
			else {
				$('#'+o.loader).css('background-color','#000')
								.append($('<span></span>')
										  	.text(o.loadingText)
											.css({'position':'relative',
												 'top':'2px',
												 'left':'2px',
												 'color':'#FFF',
												 'font-size':'9px'})
									 	);
			}
			$(bigimg).load(function() {
				imgtag = $('<img/>').attr('src',$holder.attr('href')).attr('title',$thumb.attr('title')+o.closeTip).attr('alt',$thumb.attr('alt')+o.closeTip).height(th).width(tw);
				o.flyOutStart.call(it);
				if (o.destElement) {
					var $dest = $(o.destElement);
					max_x = $dest.innerWidth() - (o.destPadding*2);
					max_y = $dest.innerHeight() - (o.destPadding*2);
				}
				else {
					max_x = $(window).width()-o.widthMargin;
					if ($.browser.opera) 
						wh = document.getElementsByTagName('html')[0].clientHeight;
					else 
						wh = $(window).height();
					max_y = wh-o.heightMargin;
				}
				width = bigimg.width;
				height = bigimg.height;
				x_dim = max_x / width;
				y_dim = max_y / height;
				if (x_dim <=y_dim) {
					y_dim = x_dim;
				} else {
					x_dim = y_dim;
				}
				dw = Math.round(width  * x_dim);
				dh = Math.round(height * y_dim);
				if (dw>width) {dw = width}
				if (dh>height) {dh = height}
				if (o.destElement) {
					dPos = $dest.offset();
					dl = Math.round(($dest.outerWidth()/2)-(dw/2)+dPos.left);
					dt = Math.round(($dest.outerHeight()/2)-(dh/2)+dPos.top);
				}
				else {
					dl = Math.round(($(window).width()/2)-(dw/2)+sL);
					if ($.browser.opera) 
						wh = document.getElementsByTagName('html')[0].clientHeight;
					else 
						wh = $(window).height();
					dt = Math.round((wh/2)-(dh/2)+sT);
				}
				$('#'+o.loader).empty().css('opacity',1).append(imgtag).width('auto').height('auto').animate({top:dt, left:dl},{duration:o.outSpeed, queue:false, easing:o.outEase});
				$('#'+o.loader+' '+subType).animate({height:dh, width:dw}, o.outSpeed, o.outEase,
				function() {
					o.flyOutFinish.call(it);
					shown = it;
					$holder.addClass(o.shownClass);
					animating=false;
					$('#'+o.loader+' '+subType).click(function(){putAway(null)})
				});
			});
			bigimg.src = $holder.attr('href');
		}
		function putAway(next) {
			if (animating == true || shown == false) {return false;}
			o.putAwayStart.call(shown);
			animating = true;
			tloc = $thumb.offset();
			tloc.left += o.startOffsetX;
			tloc.top += o.startOffsetY;
			$('#'+o.loader).animate({top:tloc.top, left:tloc.left},{duration:o.inSpeed, queue:false, easing:o.inEase});
			$('#'+o.loader+' '+subType).animate({height:th, width:tw}, 
				o.inSpeed, o.inEase, 
				function() {
					$('#'+o.loader).css('display','none').remove(); 
					o.putAwayFinish.call(shown);
					animating=false;
					bigimg=null;			
					if (next && next != shown) {
						shown = false;
						flyOut(next);
					}
					shown = false;
					$holder.removeClass(o.shownClass);
				});
		}
		return this;
	}
});
$(document).ready(function(){ $('.zoom').flyout({loadingSrc:'_static/images/loading.gif'}); });