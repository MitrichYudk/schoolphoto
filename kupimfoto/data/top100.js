(function(window){var f=!0,i=!1,j,k=this;Math.floor(2147483648*Math.random()).toString(36);function l(a,b){this.width=a;this.height=b}l.prototype.toString=function(){return this.width+"x"+this.height};var aa=/^[a-zA-Z0-9\-_.!~*'()]*$/;function m(a){a=""+a;return!aa.test(a)?encodeURIComponent(a):a};function o(){this.e={};this.i=[]}j=o.prototype;j.a=0;j.j=function(){return this.a};j.c=function(a){return Object.prototype.hasOwnProperty.call(this.e,a)};j.set=function(a,b){Object.prototype.hasOwnProperty.call(this.e,a)||(this.a++,this.i.push(a));this.e[a]=b};j.get=function(a,b){return Object.prototype.hasOwnProperty.call(this.e,a)?this.e[a]:b};j.h=function(){return this.i.concat()};j.d=function(){for(var a=[],b=0;b<this.i.length;b++)a.push(this.e[this.i[b]]);return a};var p=Array.prototype;function q(a){return p.concat.apply(p,arguments)};function r(a){this.b=new o;this.q=!!a}j=r.prototype;j.a=0;j.j=function(){return this.a};j.c=function(a){a=s(this,a);return this.b.c(a)};j.h=function(){for(var a=this.b.d(),b=this.b.h(),c=[],e=0;e<b.length;e++)for(var g=a[e],d=0;d<g.length;d++)c.push(b[e]);return c};j.d=function(a){var b=[];if(a)this.c(a)&&(b=q(b,this.b.get(s(this,a))));else for(var a=this.b.d(),c=0;c<a.length;c++)b=q(b,a[c]);return b};
j.set=function(a,b){a=s(this,a);this.c(a)&&(this.a-=this.b.get(a).length);this.b.set(a,[b]);this.a++;return this};j.get=function(a,b){var c=a?this.d(a):[];return 0<c.length?c[0]:b};function s(a,b){var c=""+b;a.q&&(c=c.toLowerCase());return c}j.toString=function(){if(!this.a)return"";for(var a=[],b=this.b.h(),c=0;c<b.length;c++)for(var e=b[c],g=m(e),e=this.d(e),d=0;d<e.length;d++){var h=g;""!==e[d]&&(h+="="+m(e[d]));a.push(h)}return a.join("&")};var t,u,v,w,x;function y(){return k.navigator?k.navigator.userAgent:null}function z(){return k.navigator}x=w=v=u=t=i;var A;if(A=y()){var ba=z();x=0==A.indexOf("Opera");t=!x&&-1!=A.indexOf("MSIE");w=(v=!x&&-1!=A.indexOf("WebKit"))&&-1!=A.indexOf("Mobile");u=!x&&!v&&"Gecko"==ba.product}var ca=t,da=u,B=v,ea=w;var C;if(x&&k.opera){var D=k.opera.version;"function"==typeof D&&D()}else da?C=/rv\:([^\);]+)(\)|;)/:ca?C=/MSIE\s+([^\);]+)(\)|;)/:B&&(C=/WebKit\/(\S+)/),C&&C.exec(y());function fa(a){this.f=a}var E=/\s*;\s*/;j=fa.prototype;j.get=function(a,b){for(var c=a+"=",e=(this.f.cookie||"").split(E),g=0,d;d=e[g];g++)if(0==d.indexOf(c))return d.substr(c.length);return b};
j.set=function(a,b,c,e,g,d){if(/[;=\s]/.test(a))throw Error('Invalid cookie name "'+a+'"');if(/[;\r\n]/.test(b))throw Error('Invalid cookie value "'+b+'"');this.f.cookie=a+"="+b+(g?";domain="+g:"")+(e?";path="+e:"")+(c instanceof Date?";expires="+c.toUTCString():0>c?"":0==c?";expires="+(new Date(1970,1,1)).toUTCString():";expires="+(new Date(+new Date+1E3*c)).toUTCString())+(d?";secure":"")};j.remove=function(a,b,c){var e=this.c(a);this.set(a,"",0,b,c);return e};j.h=function(){return ga(this).keys};
j.d=function(){return ga(this).t};j.j=function(){return!this.f.cookie?0:(this.f.cookie||"").split(E).length};j.c=function(a){return void 0!==this.get(a)};function ga(a){for(var a=(a.f.cookie||"").split(E),b=[],c=[],e,g,d=0;g=a[d];d++)e=g.indexOf("="),-1==e?(b.push(""),c.push(g)):(b.push(g.substring(0,e)),c.push(g.substring(e+1)));return{keys:b,t:c}}j.isEnabled=function(){var a=navigator.cookieEnabled;if(a&&B){var b="COOKIE_TEST_"+ +new Date;ha.set(b,"1");if(!this.get(b))return i;this.remove(b)}return a};
var ha=new fa(document);var F=i,G="";function H(a){a=a.match(/[\d]+/g);a.length=3;return a[0]+"."+a[1]+" r"+a[2]}var I=z();
if(I.plugins&&I.plugins.length){var J=I.plugins["Shockwave Flash"];J&&(F=f,J.description&&(G=H(J.description)));I.plugins["Shockwave Flash 2.0"]&&(F=f,G="2.0.0.11")}else if(I.mimeTypes&&I.mimeTypes.length){var K=I.mimeTypes["application/x-shockwave-flash"];(F=!(!K||!K.enabledPlugin))&&(G=H(K.enabledPlugin.description))}else try{var L=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7"),F=f,G=H(L.GetVariable("$version"))}catch(ia){try{L=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6"),F=f,G="6.0.21",
L.v="always",G=H(L.GetVariable("$version"))}catch(ja){try{L=new ActiveXObject("ShockwaveFlash.ShockwaveFlash"),F=f,G=H(L.GetVariable("$version"))}catch(ka){}}}var la=G;var ma=["application/x-silverlight","application/x-silverlight-2","application/x-silverlight-2-b2","application/x-silverlight-2-b1"],M=i,N="";function O(a){return"1.0.30226.2"==a?"2.0.30226.2":a}var P=z();
if(P.plugins&&P.plugins.length){var Q=P.plugins["Silverlight Plug-In"];Q&&(M=f,Q.description&&(N=O(Q.description)))}else if(P.mimeTypes&&P.mimeTypes.length)for(var R=0;R<ma.length;R++){var S=P.mimeTypes[ma[R]];if(S&&S.enabledPlugin){(M=!!S.enabledPlugin)&&(N=O(S.enabledPlugin.description));break}}else{var T=[0,0,0,0];try{for(var na=new ActiveXObject("AgControl.AgControl"),M=f,R=0;R<T.length;R++){for(var U=0,V=1048575,oa=0;U<V;){var W=T[R]=U+(V-U>>1);na.IsVersionSupported(T.join("."))?(oa=W,U=W+1):
V=W}T[R]=oa}N=O(T.join("."))}catch(pa){}}var qa=N;var X="0.3";function Y(a,b){this.g=b||"";this.o=this.n=this.p=f;this.s=0;this.r=250;this.l=new r}Y.prototype.k=("https:"==document.location.protocol?"https://s":"http://")+"counter.rambler.ru/top100.scn";Y.prototype.u="http://top100.rambler.ru";Y.prototype.m="Rambler's Top100";
function ra(a){var b=Z,c=z(),e=document,g=k.screen,d=new r;d.set("rn",Math.round(2147483647*Math.random()));d.set("v",X);var h;h=window;var n=h.document;if(B&&!ea){void 0!==h.innerHeight||(h=window);var n=h.innerHeight,ua=h.document.documentElement.scrollHeight;h==h.top&&ua<n&&(n-=15);h=new l(h.innerWidth,n)}else h="CSS1Compat"==n.compatMode?n.documentElement:n.body,h=new l(h.clientWidth,h.clientHeight);d.set("bs",h.toString());d.set("ce",ha.isEnabled()?1:0);e&&(d.set("rf",e.referrer||""),d.set("en",
e.characterSet||e.charset||""),b.p&&d.set("pt",e.title.substring(0,b.r)));g&&(d.set("cd",g.colorDepth+"-bit"),d.set("sr",g.width+"x"+g.height));c&&(d.set("la",c.language||c.browserLanguage||""),d.set("ja",c.javaEnabled()?1:0),d.set("acn",c.appCodeName),d.set("an",c.appName),d.set("pl",c.platform));d.set("tz",(new Date).getTimezoneOffset());"string"==typeof a&&d.set("url",a);b.n&&d.set("fv",la);b.o&&d.set("sv",qa);b.l.j()&&d.set("cv",b.l.toString());return d}
function sa(){var a=$;return function(){ta(a)}}function va(){var a=$,b=wa;return function(){k.clearTimeout(b||null);ta(a)}}function ta(a){a.onload=a.onerror=a.onabort=null;delete a};var X=X+"i",Z=new Y(0,"1566467");if(parseInt("1",10)){var xa=ra();xa.set("le",0);document.write('<a href="'+(Z.u+(Z.g?"/home?id="+Z.g:""))+'" target="_blank"><img src="'+(Z.k+"?"+Z.g+"&"+xa.toString())+'" title="'+Z.m+'" alt="'+Z.m+'" border="0" /></a>')}else{var ya=ra(void 0);ya.set("le",1);var za=Z,Aa=Z.k+"?"+Z.g+"&"+ya.toString(),$=new Image(1,1),Ba=za.s,wa=null,Ca=sa();0<Ba&&(wa=k.setTimeout(Ca,Ba));var Da=va();$.onerror=$.onabort=Ca;$.onload=Da;$.src=Aa};})(window)
