!function(a){function b(b,c){function d(b){return j[b]||(j[b]=a(h[b]),j[b]._currentIndex=0,j[b]._childItems=a(".flexry-rtl-item",j[b]),j[b]._childLength=j[b]._childItems.length),j[b]}function e(){clearTimeout(l),k=!0}function f(){k&&(k=!1,g())}function g(){!function b(c){l=setTimeout(function(){var e=d(c),f=(e._currentIndex+1)%e._childLength,g=e._childItems.eq(f);e.height(a("img",g)[0].clientHeight+2*m.itemPadding),e._childItems.removeClass("current"),g.addClass("current"),e._currentIndex=f,b((c+1)%i)},m.rotateTime)}(0)}var h=a(".flexry-rtl-group",b),i=h.length,j={},k=!1,l=null,m=a.extend(!0,{},{itemPadding:5,rotateTime:1500,animateTime:750,randomize:!1},c);return b.on("flexry_lightbox_open",function(){e()}).on("flexry_lightbox_close",function(){f()}),g(),{config:m,pause:e,loop:f}}a.fn.flexryRtl=function(c){return this.each(function(d,e){var f=a(e),g=new b(f,c);f.data("flexryRtl",g)})}}(jQuery);