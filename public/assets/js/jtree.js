(function($)
{	function openfolder(t,ul,opt)
	{	t.attr("data-state","open"); ul.slideDown(opt.openSpeed);

		if(opt.multiFolder == "siblings")
		{	var sbul = t.parent().parent().find("ul");
			var allpul = t.parents("span+ul");
			var toclose = allpul.parent().parent().find("ul:visible").not(allpul).not(sbul);
			sbulch = sbul.not(ul).children().children("ul:visible");
			toclose = toclose.add(sbulch).add(sbulch.find("ul:visible"));
			toclose.slideUp(opt.closeSpeed).prev().removeAttr("data-state");
		} else if(!opt.multiFolder) t.parent().parent().children("li").children("ul:visible").not(ul).slideUp(opt.closeSpeed).prev().removeAttr("data-state");
	}

	function closeFolder(t,ul,opt)
	{	if(opt.multiFolder == "siblings")
		{	var toclose = ul.add( ul.children().children("ul").find("ul:visible") );
			toclose.slideUp(opt.closeSpeed).prev().removeAttr("data-state");
		} else { ul.slideUp(opt.closeSpeed); t.removeAttr("data-state"); }
	}

	function setIcons(set,opt)
	{	set.each(function()
		{	var t = $(this);
			var ul = t.next();
			if(!ul.length)
			{	var ext = t.html().split(".").pop();
				var icon = opt.icon;
				var type = "none";
				for(l in opt.icons){ l=opt.icons[l]; if( $.inArray(ext, l.ext) > -1 ){ type=l.type; icon=l.icon; break; } }
				t.data('ext',ext).data('type',type).css("background-image","url("+opt.iconFolder+icon+")");
			}
		});
	}

	$.expr[':'].jtreeFiles = function(t){return ($(t).next("ul").length == 0);}
	$.expr[':'].jtreeFolder = function(t){return ($(t).next("ul").length == 1);}
	$.expr[':'].jtreeOpen = function(t){ return $(t).attr("data-state") == "open";}
	$.expr[':'].jtreeClose = function(t){ return $(t).attr("data-state") != "open";}
	$.expr[':'].jtreeIs = function(t,index,meta){return ($.inArray($(t).data("ext"),meta[3].split(','))>-1);}
	$.expr[':'].jtreeOf = function(t,index,meta){return ($.inArray($(t).data("type"),meta[3].split(','))>-1);}
	$.expr[':'].jtreeNot = function(t,index,meta){return ($.inArray($(t).data("ext"),meta[3].split(','))<=-1);}
	$.expr[':'].jtreeNotOf = function(t,index,meta){return ($.inArray($(t).data("type"),meta[3].split(','))<=-1);}
	$.expr[':'].jtreeEmpty = function(t){ return $(t).next("ul:empty").length; }
	$.expr[':'].jtreeFill = function(t){ return $(t).next("ul:parent").length; }
	$.expr[':'].jtreeNameHas = function(t,index,meta){return ($(t).filter(':contains('+meta[3]+')').length == 1)}
	$.expr[':'].jtreeNotNameHas = function(t,index,meta){return ($(t).filter(':contains('+meta[3]+')').length == 0)}
	$.expr[':'].jtreeNameIs = function(t,index,meta){return ($(t).text() == meta[3]);}
	$.expr[':'].jtreeNotNameIs = function(t,index,meta){return ($(t).text() != meta[3]);}
	$.expr[':'].jtreeMNameIs = function(t,index,meta){return ($.inArray($(t).text(),meta[3].split(','))>-1);}
	$.expr[':'].jtreeMNotNameIs = function(t,index,meta){return ($.inArray($(t).text(),meta[3].split(','))<=-1);}

	$.fn.jtree = function(options) // create the tree
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);

		return this.each(function()
		{	var span = $(this).find("span:not([data-style=unclosable])");

			if(opt.autoToggle)
			{	span.click(function()
				{	var t = $(this);
					var ul = t.next();
					if(ul.length == 1)
					{	if(t.attr('data-state') != 'open') openfolder(t,ul,opt); else closeFolder(t,ul,opt);
					}
				});
			}

			setIcons(span,opt);
		});
	};

	$.fn.jtreeFolders = function(filter) // get all folders from current folder
	{	var t = $(this); if(t.is("span")) t = t.next();
		if(typeof(filter) == "string") return t.find('span+ul').prev(filter); return t.find('span+ul').prev();
	};

	$.fn.jtreeFiles = function(filter) // get all files from current folder
	{	var t = $(this); if(t.is("span")) t = t.next();
		var span = t.find('span'); span = span.not(span.next().prev());
		if(typeof(filter) == "string") return span.filter(filter); return span;
	};
	
	$.fn.jtreeAll = function(filter) // get all entry, files and folders, from current folder
	{	if(typeof(filter) == "string") return $(this).find("span"+filter); return $(this).find("span");
	}
	
	$.fn.jtreeChildFolders = function(filter) // get all folders inside current folder
	{	var t = $(this); if(t.is("span")) t = t.next();
		var f = t.children('li').children('span+ul'); if(typeof(filter) == "string") return f.prev(filter); return f.prev();
	};

	$.fn.jtreeChildFiles = function(filter) // get all files inside current folder
	{	var t = $(this); if(t.is("span")) t = t.next(); t = t.children('li');
		var span = t.children('span'); span = span.not(span.next('ul').prev());
		if(typeof(filter) == "string") return span.filter(filter); return span;
	};
	
	$.fn.jtreeChildAll = function(filter) // get all files and folders inside current folder
	{	var t = $(this); if(t.is("span")) t = t.next();
		var li = t.children("li");
		if(typeof(filter) == "string") return li.children('span'+filter); return li.children('span');
	}
	
	$.fn.jtreeOpened = function() // is current folder open ?
	{	return ($(this).attr('data-state') == 'open');
	}
	
	$.fn.jtreeClose = function(options) // close current folder
	{	var t = $(this); if(t.attr('data-style') == 'unclosable') return this;
		var opt = $.extend({}, $.fn.jtree.dopt, options);
		closeFolder(t,t.next(),opt);
		return this;
	}
	
	$.fn.jtreeOpen = function(options) // open current folder
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);
		var t = $(this); var ul = t.next(); if(ul.length != 1) return this;
		openfolder(t,ul,opt); return this;
	}
	
	$.fn.jtreeToggle = function(options) // toggle current folder
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);
		var t = $(this);
		var ul = t.next();
		if(ul.length != 1) return this;
		if(t.attr('data-state') != 'open') openfolder(t,ul,opt);
		else { if(t.is('[data-style=unclosable]')) return this; closeFolder(t,ul,opt); }
		return this;
	}

	$.fn.jtreeRemove = function(options)
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);
		var li = $(this).parent(); var ul = li.parent().prev();
		var hide = li.filter(":hidden"); li = li.not(hide); hide.remove();
		li.addClass('todel').slideUp(opt.removeTime,function(){ $(this).remove(); }); return ul;
	}

	$.fn.jtreeRemoveAllEmpty = function(options)
	{	var t = $(this); var found=0;
		t.find("li:not(.todel)>ul:empty").parent().addClass('todel');		
		do{	found=0; ul = $.unique( t.find('li:not(.todel) > ul > li.todel').parent('ul') );
			ul.each(function(){ $t=$(this); if($t.children('li:not(.todel)').length == 0){ $.unique($t.parent('li')).addClass('todel'); found=1; }});
		} while(found);
		t.find('li.todel').children('span').jtreeRemove(options);
	}

	$.fn.jtreeCreateFile = function(name,options)
	{	return $(this).jtreeInsert('file',name,options);
	}

	$.fn.jtreeCreateFolder = function(name,options)
	{	return $(this).jtreeInsert('folder',name,options);
	}

	$.fn.jtreeMoveTo = function(folder,options,callback)
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);
		var t = $(this);
		if(!t.length || !folder.length || t.is(folder)) return false;
		var ul = t.next();
		var li = t.parent();
		var ftogo = folder.next(); if(!ftogo.length) ftogo=folder.parent().parent();
		if(ul.length) // folder
		{	if(ul.parent().parent().is(ftogo) || ul.find(folder).length) return false;
			var type = "folder";
		} else { // file
			if(li.parent().is(ftogo)) return false;
			var type = "file";
		}
		
		if($.isFunction(callback))
		{	var fname = t.text();
			var oldpath = t.jtreeGetPath();
			var newpath = ftogo.prev().jtreeGetPath();
			if(newpath != '') newpath += '/'; newpath += fname;
			if(false === callback(oldpath,newpath)) return false;
		}

		li.slideUp(opt.removeTime,function(){ li.detach(); folder.jtreeInsert(type,li,options); });
		return ftogo.prev();//li.children("span");
	}

	$.fn.jtreeInsert = function(type,name,options)
	{	var opt = $.extend({}, $.fn.jtree.dopt, options);
		var t = $(this); var ul = t.next();
		if(!ul.length){ ul = t.parent().parent(); t = ul.prev(); }
		var li = ul.children("li"); var out = false;
		var list = li.children("span+ul").prev();
		if(type != "folder")
		{	list = li.children("span").not(list);
			var endhtml = '</span></li>';
		} else	var endhtml = '</span><ul></ul></li>';
		if(typeof(name) == "string")
		{	var d = $('<li><span>'+name+endhtml).jtree(opt);
			var filename = name;
		} else { var d=name; var filename = d.children("span").text(); }
		d.hide();
		if(list.length)
		{	list.each(function()
			{	var $t = $(this); if($t.text() > filename){ out = d.insertBefore($t.parent()); return false; }
			});	if(out === false) out = d.insertAfter(list.last().parent());
		} else	{ if(type != "folder") out = d.appendTo(ul); else out = d.prependTo(ul); }
		return out.slideDown(opt.appendTime).children("span");
	}

	$.fn.jtreeParent = function() // get parent folder
	{	var t = $(this); if(t.is("ul.jtree>li>span")) return this;
		return t.parent().parent().prev();
	}

	$.fn.jtreeParentList = function() // get parent ul
	{	return $(this).parent().parent();
	}

	$.fn.jtreeGetList = function() // get child ul
	{	return $(this).next();
	}

	$.fn.jtreeSelect = function(path)
	{	var t = $(this);
		//t.css("color","blue");
		if(t.is("ul.jtree"))
		{	var root = t.children();
		} else	var root = t.parentsUntil('ul.jtree').last();
		root = root.children("span").next();
		var p = path.split('/'); p = $.grep(p,function(n){ return(n); });
		var nb = p.length;
		var start = 0; if(p[0] == "root") start=1;
		for(var n=start;n<nb-1;n++)
		{	var child = root.children('li').children('span+ul').prev("span:jtreeNameIs("+p[n]+")").last();
			if(child.length) root = child.next(); else return root.prev();
		}
		child = root.children('li').children('span:jtreeNameIs('+p[nb-1]+')').last();
		if(child.length) return child;
		return root.prev();
	}

	$.fn.jtreeReverse = [].reverse;

	$.fn.jtreeOpenTo = function(options)
	{	var t = $(this);
		var opt = $.extend({}, $.fn.jtree.dopt, options);
		var ul = t.next("ul").add(t.parentsUntil('ul.jtree','ul'));
		ul = ul.prev().filter(":jtreeClose").next(); if(!ul.length) ul=t.next("ul");

		ul.each(function(i)
		{	var t = $(this);
			openfolder(t.prev(),t.delay(opt.multiOpenDelay*i),opt);
		});

		return this;
	}

	$.fn.jtreeGoTo = function(path,options)
	{	return $(this).jtreeSelect(path).jtreeOpenTo(options);
	}

	$.fn.jtreeSearchFile = function(name,mode)
	{	var set = $(this).find('span:contains('+name+')'); set = set.not(set.next("ul").prev());
		if(mode == "exact") return set.filter(':jtreeNameIs('+name+')');
		return set;
	}

	$.fn.jtreeSearchFolder = function(name,mode)
	{	var set = $(this).find('span:contains('+name+')').next("ul").prev();
		if(mode == "exact") return set.filter(':jtreeNameIs('+name+')');
		return set;
	}

	$.fn.jtreeSearch = function(name,mode)
	{	var set = $(this).find('span:contains('+name+')');
		if(mode == "exact") return set.filter(':jtreeNameIs('+name+')');
		return set;
	}

	$.fn.jtreeGetPath = function() // get current entry complete path
	{	var t = $(this); if(t.is("ul.jtree>li>span")) return '';
		var p = t.parents("span+ul").not("ul.jtree>li>ul").prev();
		var path = ''; p.each(function(){ path = $(this).html()+'/'+path; });
		return path+t.html();
	}
	
	$.fn.jtree.dopt = {
		autoToggle:true,
		multiFolder:"siblings",
		iconFolder:'/img/icons/',
		icons:	[	{ type:"image", icon:"images.png", ext:[ "jpg","jpeg","gif","png","bmp" ] },
				{ type:"source", icon:"page_code.png", ext:[ "htm","html","php","txt" ] }
			],
		openSpeed:500,
		closeSpeed:500,
		appendTime:1000,
		removeTime:1000,
		multiOpenDelay:500,
		icon:'page_white.png'
	};
})(jQuery);