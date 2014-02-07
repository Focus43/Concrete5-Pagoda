/**
 * jscolor, JavaScript Color Picker
 *
 * @version 1.4.2
 * @license GNU Lesser General Public License, http://www.gnu.org/copyleft/lesser.html
 * @author  Jan Odvarko, http://odvarko.cz
 * @created 2008-06-15
 * @updated 2013-11-25
 * @link    http://jscolor.com
 */
var jscolor={dir:"/packages/flexry/images/jscolor/",bindClass:"color-choose",binding:true,preloading:true,install:function(){jscolor.addEvent(window,"load",jscolor.init)},init:function(){if(jscolor.binding){jscolor.bind()}if(jscolor.preloading){jscolor.preload()}},getDir:function(){if(!jscolor.dir){var e=jscolor.detectDir();jscolor.dir=e!==false?e:"jscolor/"}return jscolor.dir},detectDir:function(){var e=location.href;var t=document.getElementsByTagName("base");for(var n=0;n<t.length;n+=1){if(t[n].href){e=t[n].href}}var t=document.getElementsByTagName("script");for(var n=0;n<t.length;n+=1){if(t[n].src&&/(^|\/)jscolor\.js([?#].*)?$/i.test(t[n].src)){var r=new jscolor.URI(t[n].src);var i=r.toAbsolute(e);i.path=i.path.replace(/[^\/]+$/,"");i.query=null;i.fragment=null;return i.toString()}}return false},bind:function(){var e=new RegExp("(^|\\s)("+jscolor.bindClass+")\\s*(\\{[^}]*\\})?","i");var t=document.getElementsByTagName("input");for(var n=0;n<t.length;n+=1){var r;if(!t[n].color&&t[n].className&&(r=t[n].className.match(e))){var i={};if(r[3]){try{i=(new Function("return ("+r[3]+")"))()}catch(s){}}t[n].color=new jscolor.color(t[n],i)}}},preload:function(){for(var e in jscolor.imgRequire){if(jscolor.imgRequire.hasOwnProperty(e)){jscolor.loadImage(e)}}},images:{pad:[181,101],sld:[16,101],cross:[15,15],arrow:[7,11]},imgRequire:{},imgLoaded:{},requireImage:function(e){jscolor.imgRequire[e]=true},loadImage:function(e){if(!jscolor.imgLoaded[e]){jscolor.imgLoaded[e]=new Image;jscolor.imgLoaded[e].src=jscolor.getDir()+e}},fetchElement:function(e){return typeof e==="string"?document.getElementById(e):e},addEvent:function(e,t,n){if(e.addEventListener){e.addEventListener(t,n,false)}else if(e.attachEvent){e.attachEvent("on"+t,n)}},fireEvent:function(e,t){if(!e){return}if(document.createEvent){var n=document.createEvent("HTMLEvents");n.initEvent(t,true,true);e.dispatchEvent(n)}else if(document.createEventObject){var n=document.createEventObject();e.fireEvent("on"+t,n)}else if(e["on"+t]){e["on"+t]()}},getElementPos:function(e){var t=e,n=e;var r=0,i=0;if(t.offsetParent){do{r+=t.offsetLeft;i+=t.offsetTop}while(t=t.offsetParent)}while((n=n.parentNode)&&n.nodeName.toUpperCase()!=="BODY"){r-=n.scrollLeft;i-=n.scrollTop}return[r,i]},getElementSize:function(e){return[e.offsetWidth,e.offsetHeight]},getRelMousePos:function(e){var t=0,n=0;if(!e){e=window.event}if(typeof e.offsetX==="number"){t=e.offsetX;n=e.offsetY}else if(typeof e.layerX==="number"){t=e.layerX;n=e.layerY}return{x:t,y:n}},getViewPos:function(){if(typeof window.pageYOffset==="number"){return[window.pageXOffset,window.pageYOffset]}else if(document.body&&(document.body.scrollLeft||document.body.scrollTop)){return[document.body.scrollLeft,document.body.scrollTop]}else if(document.documentElement&&(document.documentElement.scrollLeft||document.documentElement.scrollTop)){return[document.documentElement.scrollLeft,document.documentElement.scrollTop]}else{return[0,0]}},getViewSize:function(){if(typeof window.innerWidth==="number"){return[window.innerWidth,window.innerHeight]}else if(document.body&&(document.body.clientWidth||document.body.clientHeight)){return[document.body.clientWidth,document.body.clientHeight]}else if(document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight)){return[document.documentElement.clientWidth,document.documentElement.clientHeight]}else{return[0,0]}},URI:function(e){function t(e){var t="";while(e){if(e.substr(0,3)==="../"||e.substr(0,2)==="./"){e=e.replace(/^\.+/,"").substr(1)}else if(e.substr(0,3)==="/./"||e==="/."){e="/"+e.substr(3)}else if(e.substr(0,4)==="/../"||e==="/.."){e="/"+e.substr(4);t=t.replace(/\/?[^\/]*$/,"")}else if(e==="."||e===".."){e=""}else{var n=e.match(/^\/?[^\/]*/)[0];e=e.substr(n.length);t=t+n}}return t}this.scheme=null;this.authority=null;this.path="";this.query=null;this.fragment=null;this.parse=function(e){var t=e.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^\/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);this.scheme=t[3]?t[2]:null;this.authority=t[5]?t[6]:null;this.path=t[7];this.query=t[9]?t[10]:null;this.fragment=t[12]?t[13]:null;return this};this.toString=function(){var e="";if(this.scheme!==null){e=e+this.scheme+":"}if(this.authority!==null){e=e+"//"+this.authority}if(this.path!==null){e=e+this.path}if(this.query!==null){e=e+"?"+this.query}if(this.fragment!==null){e=e+"#"+this.fragment}return e};this.toAbsolute=function(e){var e=new jscolor.URI(e);var n=this;var r=new jscolor.URI;if(e.scheme===null){return false}if(n.scheme!==null&&n.scheme.toLowerCase()===e.scheme.toLowerCase()){n.scheme=null}if(n.scheme!==null){r.scheme=n.scheme;r.authority=n.authority;r.path=t(n.path);r.query=n.query}else{if(n.authority!==null){r.authority=n.authority;r.path=t(n.path);r.query=n.query}else{if(n.path===""){r.path=e.path;if(n.query!==null){r.query=n.query}else{r.query=e.query}}else{if(n.path.substr(0,1)==="/"){r.path=t(n.path)}else{if(e.authority!==null&&e.path===""){r.path="/"+n.path}else{r.path=e.path.replace(/[^\/]+$/,"")+n.path}r.path=t(r.path)}r.query=n.query}r.authority=e.authority}r.scheme=e.scheme}r.fragment=n.fragment;return r};if(e){this.parse(e)}},color:function(e,t){function r(e,t,n){var r=Math.min(Math.min(e,t),n);var i=Math.max(Math.max(e,t),n);var s=i-r;if(s===0){return[null,0,i]}var o=e===r?3+(n-t)/s:t===r?5+(e-n)/s:1+(t-e)/s;return[o===6?0:o,s/i,i]}function i(e,t,n){if(e===null){return[n,n,n]}var r=Math.floor(e);var i=r%2?e-r:1-(e-r);var s=n*(1-t);var o=n*(1-t*i);switch(r){case 6:case 0:return[n,o,s];case 1:return[o,n,s];case 2:return[s,n,o];case 3:return[s,o,n];case 4:return[o,s,n];case 5:return[n,s,o]}}function s(){delete jscolor.picker.owner;document.getElementsByTagName("body")[0].removeChild(jscolor.picker.boxB)}function o(t,n){function w(){var e=m.pickerInsetColor.split(/\s+/);var t=e.length<2?e[0]:e[1]+" "+e[0]+" "+e[0]+" "+e[1];o.btn.style.borderColor=t}if(!jscolor.picker){jscolor.picker={box:document.createElement("div"),boxB:document.createElement("div"),pad:document.createElement("div"),padB:document.createElement("div"),padM:document.createElement("div"),sld:document.createElement("div"),sldB:document.createElement("div"),sldM:document.createElement("div"),btn:document.createElement("div"),btnS:document.createElement("span"),btnT:document.createTextNode(m.pickerCloseText)};for(var r=0,i=4;r<jscolor.images.sld[1];r+=i){var s=document.createElement("div");s.style.height=i+"px";s.style.fontSize="1px";s.style.lineHeight="0";jscolor.picker.sld.appendChild(s)}jscolor.picker.sldB.appendChild(jscolor.picker.sld);jscolor.picker.box.appendChild(jscolor.picker.sldB);jscolor.picker.box.appendChild(jscolor.picker.sldM);jscolor.picker.padB.appendChild(jscolor.picker.pad);jscolor.picker.box.appendChild(jscolor.picker.padB);jscolor.picker.box.appendChild(jscolor.picker.padM);jscolor.picker.btnS.appendChild(jscolor.picker.btnT);jscolor.picker.btn.appendChild(jscolor.picker.btnS);jscolor.picker.box.appendChild(jscolor.picker.btn);jscolor.picker.boxB.appendChild(jscolor.picker.box)}var o=jscolor.picker;o.box.onmouseup=o.box.onmouseout=function(){e.focus()};o.box.onmousedown=function(){y=true};o.box.onmousemove=function(e){if(E||S){E&&p(e);S&&d(e);if(document.selection){document.selection.empty()}else if(window.getSelection){window.getSelection().removeAllRanges()}v()}};if("ontouchstart"in window){var l=function(e){var t={offsetX:e.touches[0].pageX-x.X,offsetY:e.touches[0].pageY-x.Y};if(E||S){E&&p(t);S&&d(t);v()}e.stopPropagation();e.preventDefault()};o.box.removeEventListener("touchmove",l,false);o.box.addEventListener("touchmove",l,false)}o.padM.onmouseup=o.padM.onmouseout=function(){if(E){E=false;jscolor.fireEvent(b,"change")}};o.padM.onmousedown=function(e){switch(g){case 0:if(m.hsv[2]===0){m.fromHSV(null,null,1)}break;case 1:if(m.hsv[1]===0){m.fromHSV(null,1,null)}break}S=false;E=true;p(e);v()};if("ontouchstart"in window){o.padM.addEventListener("touchstart",function(e){x={X:e.target.offsetParent.offsetLeft,Y:e.target.offsetParent.offsetTop};this.onmousedown({offsetX:e.touches[0].pageX-x.X,offsetY:e.touches[0].pageY-x.Y})})}o.sldM.onmouseup=o.sldM.onmouseout=function(){if(S){S=false;jscolor.fireEvent(b,"change")}};o.sldM.onmousedown=function(e){E=false;S=true;d(e);v()};if("ontouchstart"in window){o.sldM.addEventListener("touchstart",function(e){x={X:e.target.offsetParent.offsetLeft,Y:e.target.offsetParent.offsetTop};this.onmousedown({offsetX:e.touches[0].pageX-x.X,offsetY:e.touches[0].pageY-x.Y})})}var c=u(m);o.box.style.width=c[0]+"px";o.box.style.height=c[1]+"px";o.boxB.style.position="absolute";o.boxB.style.clear="both";o.boxB.style.left=t+"px";o.boxB.style.top=n+"px";o.boxB.style.zIndex=m.pickerZIndex;o.boxB.style.border=m.pickerBorder+"px solid";o.boxB.style.borderColor=m.pickerBorderColor;o.boxB.style.background=m.pickerFaceColor;o.pad.style.width=jscolor.images.pad[0]+"px";o.pad.style.height=jscolor.images.pad[1]+"px";o.padB.style.position="absolute";o.padB.style.left=m.pickerFace+"px";o.padB.style.top=m.pickerFace+"px";o.padB.style.border=m.pickerInset+"px solid";o.padB.style.borderColor=m.pickerInsetColor;o.padM.style.position="absolute";o.padM.style.left="0";o.padM.style.top="0";o.padM.style.width=m.pickerFace+2*m.pickerInset+jscolor.images.pad[0]+jscolor.images.arrow[0]+"px";o.padM.style.height=o.box.style.height;o.padM.style.cursor="crosshair";o.sld.style.overflow="hidden";o.sld.style.width=jscolor.images.sld[0]+"px";o.sld.style.height=jscolor.images.sld[1]+"px";o.sldB.style.display=m.slider?"block":"none";o.sldB.style.position="absolute";o.sldB.style.right=m.pickerFace+"px";o.sldB.style.top=m.pickerFace+"px";o.sldB.style.border=m.pickerInset+"px solid";o.sldB.style.borderColor=m.pickerInsetColor;o.sldM.style.display=m.slider?"block":"none";o.sldM.style.position="absolute";o.sldM.style.right="0";o.sldM.style.top="0";o.sldM.style.width=jscolor.images.sld[0]+jscolor.images.arrow[0]+m.pickerFace+2*m.pickerInset+"px";o.sldM.style.height=o.box.style.height;try{o.sldM.style.cursor="pointer"}catch(h){o.sldM.style.cursor="hand"}o.btn.style.display=m.pickerClosable?"block":"none";o.btn.style.position="absolute";o.btn.style.left=m.pickerFace+"px";o.btn.style.bottom=m.pickerFace+"px";o.btn.style.padding="0 15px";o.btn.style.height="18px";o.btn.style.border=m.pickerInset+"px solid";w();o.btn.style.color=m.pickerButtonColor;o.btn.style.font="12px sans-serif";o.btn.style.textAlign="center";try{o.btn.style.cursor="pointer"}catch(h){o.btn.style.cursor="hand"}o.btn.onmousedown=function(){m.hidePicker()};o.btnS.style.lineHeight=o.btn.style.height;switch(g){case 0:var T="hs.png";break;case 1:var T="hv.png";break}o.padM.style.backgroundImage="url('"+jscolor.getDir()+"cross.gif')";o.padM.style.backgroundRepeat="no-repeat";o.sldM.style.backgroundImage="url('"+jscolor.getDir()+"arrow.gif')";o.sldM.style.backgroundRepeat="no-repeat";o.pad.style.backgroundImage="url('"+jscolor.getDir()+T+"')";o.pad.style.backgroundRepeat="no-repeat";o.pad.style.backgroundPosition="0 0";a();f();jscolor.picker.owner=m;document.getElementsByTagName("body")[0].appendChild(o.boxB)}function u(e){var t=[2*e.pickerInset+2*e.pickerFace+jscolor.images.pad[0]+(e.slider?2*e.pickerInset+2*jscolor.images.arrow[0]+jscolor.images.sld[0]:0),e.pickerClosable?4*e.pickerInset+3*e.pickerFace+jscolor.images.pad[1]+e.pickerButtonHeight:2*e.pickerInset+2*e.pickerFace+jscolor.images.pad[1]];return t}function a(){switch(g){case 0:var e=1;break;case 1:var e=2;break}var t=Math.round(m.hsv[0]/6*(jscolor.images.pad[0]-1));var n=Math.round((1-m.hsv[e])*(jscolor.images.pad[1]-1));jscolor.picker.padM.style.backgroundPosition=m.pickerFace+m.pickerInset+t-Math.floor(jscolor.images.cross[0]/2)+"px "+(m.pickerFace+m.pickerInset+n-Math.floor(jscolor.images.cross[1]/2))+"px";var r=jscolor.picker.sld.childNodes;switch(g){case 0:var s=i(m.hsv[0],m.hsv[1],1);for(var o=0;o<r.length;o+=1){r[o].style.backgroundColor="rgb("+s[0]*(1-o/r.length)*100+"%,"+s[1]*(1-o/r.length)*100+"%,"+s[2]*(1-o/r.length)*100+"%)"}break;case 1:var s,u,a=[m.hsv[2],0,0];var o=Math.floor(m.hsv[0]);var f=o%2?m.hsv[0]-o:1-(m.hsv[0]-o);switch(o){case 6:case 0:s=[0,1,2];break;case 1:s=[1,0,2];break;case 2:s=[2,0,1];break;case 3:s=[2,1,0];break;case 4:s=[1,2,0];break;case 5:s=[0,2,1];break}for(var o=0;o<r.length;o+=1){u=1-1/(r.length-1)*o;a[1]=a[0]*(1-u*f);a[2]=a[0]*(1-u);r[o].style.backgroundColor="rgb("+a[s[0]]*100+"%,"+a[s[1]]*100+"%,"+a[s[2]]*100+"%)"}break}}function f(){switch(g){case 0:var e=2;break;case 1:var e=1;break}var t=Math.round((1-m.hsv[e])*(jscolor.images.sld[1]-1));jscolor.picker.sldM.style.backgroundPosition="0 "+(m.pickerFace+m.pickerInset+t-Math.floor(jscolor.images.arrow[1]/2))+"px"}function l(){return jscolor.picker&&jscolor.picker.owner===m}function c(){if(b===e){m.importColor()}if(m.pickerOnfocus){m.hidePicker()}}function h(){if(b!==e){m.importColor()}}function p(e){var t=jscolor.getRelMousePos(e);var n=t.x-m.pickerFace-m.pickerInset;var r=t.y-m.pickerFace-m.pickerInset;switch(g){case 0:m.fromHSV(n*(6/(jscolor.images.pad[0]-1)),1-r/(jscolor.images.pad[1]-1),null,k);break;case 1:m.fromHSV(n*(6/(jscolor.images.pad[0]-1)),null,1-r/(jscolor.images.pad[1]-1),k);break}}function d(e){var t=jscolor.getRelMousePos(e);var n=t.y-m.pickerFace-m.pickerInset;switch(g){case 0:m.fromHSV(null,null,1-n/(jscolor.images.sld[1]-1),C);break;case 1:m.fromHSV(null,1-n/(jscolor.images.sld[1]-1),null,C);break}}function v(){if(m.onImmediateChange){var e;if(typeof m.onImmediateChange==="string"){e=new Function(m.onImmediateChange)}else{e=m.onImmediateChange}e.call(m)}}this.required=true;this.adjust=true;this.hash=false;this.caps=true;this.slider=true;this.valueElement=e;this.styleElement=e;this.onImmediateChange=null;this.hsv=[0,0,1];this.rgb=[1,1,1];this.minH=0;this.maxH=6;this.minS=0;this.maxS=1;this.minV=0;this.maxV=1;this.pickerOnfocus=true;this.pickerMode="HSV";this.pickerPosition="bottom";this.pickerSmartPosition=true;this.pickerButtonHeight=20;this.pickerClosable=false;this.pickerCloseText="Close";this.pickerButtonColor="ButtonText";this.pickerFace=10;this.pickerFaceColor="ThreeDFace";this.pickerBorder=1;this.pickerBorderColor="ThreeDHighlight ThreeDShadow ThreeDShadow ThreeDHighlight";this.pickerInset=1;this.pickerInsetColor="ThreeDShadow ThreeDHighlight ThreeDHighlight ThreeDShadow";this.pickerZIndex=1e4;for(var n in t){if(t.hasOwnProperty(n)){this[n]=t[n]}}this.hidePicker=function(){if(l()){s()}};this.showPicker=function(){if(!l()){var t=jscolor.getElementPos(e);var n=jscolor.getElementSize(e);var r=jscolor.getViewPos();var i=jscolor.getViewSize();var s=u(this);var a,f,c;switch(this.pickerPosition.toLowerCase()){case"left":a=1;f=0;c=-1;break;case"right":a=1;f=0;c=1;break;case"top":a=0;f=1;c=-1;break;default:a=0;f=1;c=1;break}var h=(n[f]+s[f])/2;if(!this.pickerSmartPosition){var p=[t[a],t[f]+n[f]-h+h*c]}else{var p=[-r[a]+t[a]+s[a]>i[a]?-r[a]+t[a]+n[a]/2>i[a]/2&&t[a]+n[a]-s[a]>=0?t[a]+n[a]-s[a]:t[a]:t[a],-r[f]+t[f]+n[f]+s[f]-h+h*c>i[f]?-r[f]+t[f]+n[f]/2>i[f]/2&&t[f]+n[f]-h-h*c>=0?t[f]+n[f]-h-h*c:t[f]+n[f]-h+h*c:t[f]+n[f]-h+h*c>=0?t[f]+n[f]-h+h*c:t[f]+n[f]-h-h*c]}o(p[a],p[f])}};this.importColor=function(){if(!b){this.exportColor()}else{if(!this.adjust){if(!this.fromString(b.value,T)){w.style.backgroundImage=w.jscStyle.backgroundImage;w.style.backgroundColor=w.jscStyle.backgroundColor;w.style.color=w.jscStyle.color;this.exportColor(T|N)}}else if(!this.required&&/^\s*$/.test(b.value)){b.value="";w.style.backgroundImage=w.jscStyle.backgroundImage;w.style.backgroundColor=w.jscStyle.backgroundColor;w.style.color=w.jscStyle.color;this.exportColor(T|N)}else if(this.fromString(b.value)){}else{this.exportColor()}}};this.exportColor=function(e){if(!(e&T)&&b){var t=this.toString();if(this.caps){t=t.toUpperCase()}if(this.hash){t="#"+t}b.value=t}if(!(e&N)&&w){w.style.backgroundImage="none";w.style.backgroundColor="#"+this.toString();w.style.color=.213*this.rgb[0]+.715*this.rgb[1]+.072*this.rgb[2]<.5?"#FFF":"#000"}if(!(e&C)&&l()){a()}if(!(e&k)&&l()){f()}};this.fromHSV=function(e,t,n,r){if(e!==null){e=Math.max(0,this.minH,Math.min(6,this.maxH,e))}if(t!==null){t=Math.max(0,this.minS,Math.min(1,this.maxS,t))}if(n!==null){n=Math.max(0,this.minV,Math.min(1,this.maxV,n))}this.rgb=i(e===null?this.hsv[0]:this.hsv[0]=e,t===null?this.hsv[1]:this.hsv[1]=t,n===null?this.hsv[2]:this.hsv[2]=n);this.exportColor(r)};this.fromRGB=function(e,t,n,s){if(e!==null){e=Math.max(0,Math.min(1,e))}if(t!==null){t=Math.max(0,Math.min(1,t))}if(n!==null){n=Math.max(0,Math.min(1,n))}var o=r(e===null?this.rgb[0]:e,t===null?this.rgb[1]:t,n===null?this.rgb[2]:n);if(o[0]!==null){this.hsv[0]=Math.max(0,this.minH,Math.min(6,this.maxH,o[0]))}if(o[2]!==0){this.hsv[1]=o[1]===null?null:Math.max(0,this.minS,Math.min(1,this.maxS,o[1]))}this.hsv[2]=o[2]===null?null:Math.max(0,this.minV,Math.min(1,this.maxV,o[2]));var u=i(this.hsv[0],this.hsv[1],this.hsv[2]);this.rgb[0]=u[0];this.rgb[1]=u[1];this.rgb[2]=u[2];this.exportColor(s)};this.fromString=function(e,t){var n=e.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);if(!n){return false}else{if(n[1].length===6){this.fromRGB(parseInt(n[1].substr(0,2),16)/255,parseInt(n[1].substr(2,2),16)/255,parseInt(n[1].substr(4,2),16)/255,t)}else{this.fromRGB(parseInt(n[1].charAt(0)+n[1].charAt(0),16)/255,parseInt(n[1].charAt(1)+n[1].charAt(1),16)/255,parseInt(n[1].charAt(2)+n[1].charAt(2),16)/255,t)}return true}};this.toString=function(){return(256|Math.round(255*this.rgb[0])).toString(16).substr(1)+(256|Math.round(255*this.rgb[1])).toString(16).substr(1)+(256|Math.round(255*this.rgb[2])).toString(16).substr(1)};var m=this;var g=this.pickerMode.toLowerCase()==="hvs"?1:0;var y=false;var b=jscolor.fetchElement(this.valueElement),w=jscolor.fetchElement(this.styleElement);var E=false,S=false,x={};var T=1<<0,N=1<<1,C=1<<2,k=1<<3;jscolor.addEvent(e,"focus",function(){if(m.pickerOnfocus){m.showPicker()}});jscolor.addEvent(e,"blur",function(){if(!y){window.setTimeout(function(){y||c();y=false},0)}else{y=false}});if(b){var L=function(){m.fromString(b.value,T);v()};jscolor.addEvent(b,"keyup",L);jscolor.addEvent(b,"input",L);jscolor.addEvent(b,"blur",h);b.setAttribute("autocomplete","off")}if(w){w.jscStyle={backgroundImage:w.style.backgroundImage,backgroundColor:w.style.backgroundColor,color:w.style.color}}switch(g){case 0:jscolor.requireImage("hs.png");break;case 1:jscolor.requireImage("hv.png");break}jscolor.requireImage("cross.gif");jscolor.requireImage("arrow.gif");this.importColor()}};jscolor.install();

$(function(){
    /**
     * Make items sortable. Should be called after new image are added to ensure always
     * initialized.
     * @return void
     */
    function initInteractions(){
        $('.inner', '#imageSelections').sortable({handle:'.icon-move',items: '.item', cursor: 'move', containment: 'parent', opacity: .65, tolerance: 'pointer'});
    }


    /**
     * Run after new images are added; remove if any duplicates occurred and show the alert
     * message.
     * @return void
     */
    function scanAndPurgeDuplicates(){
        // variables; _duplicates defaults to false
        var _list = [], _duplicates = false;
        $('input[type="hidden"]', '#imageSelections').each(function(idx, input){
            var $input = $(input), _fileID = $input.val();
            if( _list.indexOf(_fileID) === -1 ){
                _list.push(_fileID);
            }else{
                _duplicates = true;
                $input.parent('.item').remove();
            }
        });
        // toggle class
        $('#tabPaneImages').toggleClass('dups', _duplicates);
    }


    /**
     * Launch the asset manager and select one or more files.
     */
    $('#chooseImg').on('click', function(){
        var _stack = [], _timeOut;
        // Handler function, called once for each image added via custom selection. The setTimeout
        // is used to make sure the function are run only once after this func is called a bunch of times.
        ccm_chooseAsset = function(obj){
            _stack.push(obj);
            clearTimeout(_timeOut);
            _timeOut = setTimeout(function(){
                $.each(_stack, function(idx, obj){
                    var $newItem = $('<div class="item" />');
                    $newItem.append('<i class="icon-minus-sign"></i><i class="icon-move"></i><input type="hidden" name="fileIDs[]" value="'+obj.fID+'" />');
                    $newItem.css('background-image', 'url('+obj.thumbnailLevel2+')');
                    $('.inner', '#imageSelections').append($newItem);
                });
                initInteractions();
                scanAndPurgeDuplicates();
            }, 250);
        }

        // Launch the file picker.
        ccm_alLaunchSelectorFileManager();
    });


    /**
     * Tooltips
     */
    $('#chooseImg, .show-tooltip').tooltip({animation:false, placement:'bottom'});


    /**
     * Popovers
     */
    $('.show-popover').popover({animation:false,placement:'top'});


    /**
     * Edit the image properties, or remove it?
     */
    $('#imageSelections').on('click', '.item', function( _clickEv ){
        var $this = $(this);
        if( $(_clickEv.target).hasClass('icon-minus-sign') ){
            $this.remove();
        }else{
            $.fn.dialog.open({
                width:  650,
                height: 450,
                title:  'File Properties',
                href:   '/tools/required/files/properties?fID=' + $('input',$this).val()
            });
        }
    });


    /**
     * Hide the duplicates warning message.
     */
    $('.close', '.dups-warning').on('click', function(){
        $('#tabPaneImages').removeClass('dups');
    });


    /**
     * Clear all images in custom gallery.
     */
    $('#flexryClearAll').on('click', function( _ev ){
        _ev.preventDefault();
        if( confirm('This will remove all images and reset your selections. Continue?') ){
            $('.item', '#imageSelections').remove();
        }
    });


    /**
     * Tabs
     */
    $('a', '.nav-tabs').on('click', function( _clickEv ){
        var $this = $(this), $targ = $( $this.attr('data-tab') );
        $this.parent('li').add($targ).addClass('active').siblings().removeClass('active');
        // show the Add Images button?
        $('#flexryOptionsRight').toggle( $this.attr('data-tab') === '#tabPaneImages' );
    });


    /**
     * Use original image? (settings area)
     */
    $('#fullUseOriginal').on('change', function(){
        if( $(this).is(':checked') ){
            $('#fullWidth, #fullHeight, #fullCrop').attr('disabled', 'disabled');
        }else{
            $('#fullWidth, #fullHeight, #fullCrop').removeAttr('disabled');
        }
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * Enable lightbox? (settings area)
     */
    $('.enableLightboxCheckbox').on('change', function(){
        $('.flexry-lightbox-settings').toggle( $(this).is(':checked') );
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * File Source dropdown switcher
     */
    $('#fileSourceMethod').on('change', function(){
        var _val = $(this).val(), $btn  = $('#chooseImg');
        $('.fileSourceMethod', '#flexryGallery').removeClass('active').filter('[data-method='+_val+']').addClass('active');
        $btn.toggle( +(_val) === +($btn.attr('data-method')) );
        initHandler();
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * Template forms selector
     */
    $('#flexryTemplateHandle').on('change', function(){
        var _val = $(this).val();
        $('.template-form', '#tabPaneTemplates').removeClass('active').filter('[data-tmpl="'+_val+'"]').addClass('active');
    });


    /**
     * On init, determine what to run.
     */
    function initHandler(){
        switch( +($('.fileSourceMethod.active').attr('data-method')) ){
            case 0: initInteractions(); break; // custom gallery
            case 1: $('#fileSetPicker').chosen(); break; // sets
        }
        jscolor.bind();
    }


    initHandler();

});
