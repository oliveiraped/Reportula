var jQueryLoaderOptions=null;
(function(a){a.loader=function(d){switch(d){case"close":if(jQueryLoaderOptions){if(a("#"+jQueryLoaderOptions.id)){a("#"+jQueryLoaderOptions.id+", #"+jQueryLoaderOptions.background.id).remove()
}}return;
case"setContent":if(jQueryLoaderOptions){if(a("#"+jQueryLoaderOptions.id)){if(arguments.length==2){a("#"+jQueryLoaderOptions.id).html(arguments[1])
}else{if(console){console.error("setContent method must have 2 arguments $.loader('setContent', 'new content');")
}else{alert("setContent method must have 2 arguments $.loader('setContent', 'new content');")
}}}}return;
default:var b=a.extend({content:"<div>Loading ...</div>",className:"loader",id:"jquery-loader",height:60,width:200,zIndex:30000,background:{opacity:0.4,id:"jquery-loader-background"}},d)
}jQueryLoaderOptions=b;
var c=a(document).height();
var e=a(window).width();
var g=a('<div id="'+b.background.id+'"/>');
g.css({zIndex:b.zIndex,position:"absolute",top:"0px",left:"0px",width:e,height:c,opacity:b.background.opacity});
g.appendTo("body");
if(jQuery.bgiframe){g.bgiframe()
}var f=a('<div id="'+b.id+'" class="'+b.className+'"></div>');
f.css({zIndex:b.zIndex+1,width:b.width,height:b.height});
f.appendTo("body");
f.center();
a(b.content).appendTo(f)
};
a.fn.center=function(){this.css("position","absolute");
this.css("top",(a(window).height()-this.outerHeight())/2+a(window).scrollTop()+"px");
this.css("left",(a(window).width()-this.outerWidth())/2+a(window).scrollLeft()+"px");
return this
}
})(jQuery);