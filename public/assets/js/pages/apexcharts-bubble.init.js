"use strict";function generateData(e,a,t){for(var n=0,r=[];n<a;){var m=Math.floor(750*Math.random())+1,i=Math.floor(Math.random()*(t.max-t.min+1))+t.min,b=Math.floor(61*Math.random())+15;r.push([m,i,b]),n++}return r}var options={series:[{name:"Bubble1",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Bubble2",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Bubble3",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Bubble4",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})}],chart:{height:350,type:"bubble",parentHeightOffset:0},colors:["#287F71","#963b68","#E7366B","#108dff"],dataLabels:{enabled:!1},fill:{opacity:.8},title:{text:"Simple Bubble Chart"},xaxis:{tickAmount:12,type:"category"},yaxis:{max:70}},chart=new ApexCharts(document.querySelector("#simple_bubble_chart"),options);chart.render();options={series:[{name:"Product1",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Product2",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Product3",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})},{name:"Product4",data:generateData(new Date("11 Feb 2017 GMT").getTime(),20,{min:10,max:60})}],chart:{height:350,type:"bubble",parentHeightOffset:0},dataLabels:{enabled:!1},fill:{type:"gradient"},title:{text:"3D Bubble Chart"},xaxis:{tickAmount:12,type:"datetime",labels:{rotate:0}},colors:["#287F71","#963b68","#E77636","#01D4FF"],yaxis:{max:70},theme:{palette:"palette2"}};(chart=new ApexCharts(document.querySelector("#animation_bubble_chart"),options)).render();