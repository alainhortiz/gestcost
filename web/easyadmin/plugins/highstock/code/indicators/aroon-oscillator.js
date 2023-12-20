/*
  Highcharts JS v7.0.3 (2019-02-06)

 Indicator series type for Highstock

 (c) 2010-2019 Wojciech Chmiel

 License: www.highcharts.com/license
*/
(function(b){"object"===typeof module&&module.exports?(b["default"]=b,module.exports=b):"function"===typeof define&&define.amd?define(function(){return b}):b("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(b){var p=function(f){var h=f.each,b=f.merge,m=f.error,e=f.defined,c=f.seriesTypes.sma;return{pointArrayMap:["top","bottom"],pointValKey:"top",linesApiNames:["bottomLine"],getTranslatedLinesNames:function(a){var c=[];h(this.pointArrayMap,function(d){d!==a&&c.push("plot"+d.charAt(0).toUpperCase()+
d.slice(1))});return c},toYData:function(a){var c=[];h(this.pointArrayMap,function(d){c.push(a[d])});return c},translate:function(){var a=this,e=a.pointArrayMap,d=[],g,d=a.getTranslatedLinesNames();c.prototype.translate.apply(a,arguments);h(a.points,function(c){h(e,function(e,b){g=c[e];null!==g&&(c[d[b]]=a.yAxis.toPixels(g,!0))})})},drawGraph:function(){var a=this,f=a.linesApiNames,d=a.points,g=d.length,k=a.options,p=a.graph,q={options:{gapSize:k.gapSize}},n=[],r=a.getTranslatedLinesNames(a.pointValKey),
l;h(r,function(a,c){for(n[c]=[];g--;)l=d[g],n[c].push({x:l.x,plotX:l.plotX,plotY:l[a],isNull:!e(l[a])});g=d.length});h(f,function(e,d){n[d]?(a.points=n[d],k[e]?a.options=b(k[e].styles,q):m('Error: "There is no '+e+' in DOCS options declared. Check if linesApiNames are consistent with your DOCS line names." at mixin/multiple-line.js:34'),a.graph=a["graph"+e],c.prototype.drawGraph.call(a),a["graph"+e]=a.graph):m('Error: "'+e+" doesn't have equivalent in pointArrayMap. To many elements in linesApiNames relative to pointArrayMap.\"")});
a.points=d;a.options=k;a.graph=p;c.prototype.drawGraph.call(a)}}}(b),q=function(b){var f=b.error;return{isParentLoaded:function(b,m,e,c,a){if(b)return c?c(b):!0;f(a||this.generateMessage(e,m));return!1},generateMessage:function(b,f){return'Error: "'+b+'" indicator type requires "'+f+'" indicator loaded before. Please read docs: https://api.highcharts.com/highstock/plotOptions.'+b}}}(b);(function(b,h,k){var f=b.seriesTypes.aroon;b.seriesType("aroonoscillator","aroon",{params:{period:25},tooltip:{pointFormat:'\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e\x3cb\x3e {series.name}\x3c/b\x3e: {point.y}'}},
b.merge(h,{nameBase:"Aroon Oscillator",pointArrayMap:["y"],pointValKey:"y",linesApiNames:[],init:function(){var b=arguments,c=this;k.isParentLoaded(f,"aroon",c.type,function(a){a.prototype.init.apply(c,b)})},getValues:function(b,c){var a=[],e=[],d=[],g,h;b=f.prototype.getValues.call(this,b,c);for(c=0;c<b.yData.length;c++)g=b.yData[c][0],h=b.yData[c][1],g-=h,a.push([b.xData[c],g]),e.push(b.xData[c]),d.push(g);return{values:a,xData:e,yData:d}}}))})(b,p,q)});
//# sourceMappingURL=aroon-oscillator.js.map
