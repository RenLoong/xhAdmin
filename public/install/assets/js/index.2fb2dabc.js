import{d as g,r as L,a as y,o as v,c as E,w as P,u as O,z as R,b as T,e as A,f as w,g as b,h as C,n as m,i as I,j as V,A as q}from"./.pnpm.806cdd1d.js";(function(){const e=document.createElement("link").relList;if(e&&e.supports&&e.supports("modulepreload"))return;for(const n of document.querySelectorAll('link[rel="modulepreload"]'))s(n);new MutationObserver(n=>{for(const t of n)if(t.type==="childList")for(const i of t.addedNodes)i.tagName==="LINK"&&i.rel==="modulepreload"&&s(i)}).observe(document,{childList:!0,subtree:!0});function o(n){const t={};return n.integrity&&(t.integrity=n.integrity),n.referrerPolicy&&(t.referrerPolicy=n.referrerPolicy),n.crossOrigin==="use-credentials"?t.credentials="include":n.crossOrigin==="anonymous"?t.credentials="omit":t.credentials="same-origin",t}function s(n){if(n.ep)return;n.ep=!0;const t=o(n);fetch(n.href,t)}})();const D=g({__name:"index",setup(r){const e=L(R);return(o,s)=>{const n=y("a-config-provider");return v(),E(n,{locale:O(e)},{default:P(()=>[T(o.$slots,"default")]),_:3},8,["locale"])}}}),S=g({__name:"App",setup(r){return(e,o)=>{const s=y("router-view");return v(),E(D,null,{default:P(()=>[A(s)]),_:1})}}});const x="modulepreload",N=function(r){return"/install/"+r},h={},c=function(e,o,s){if(!o||o.length===0)return e();const n=document.getElementsByTagName("link");return Promise.all(o.map(t=>{if(t=N(t),t in h)return;h[t]=!0;const i=t.endsWith(".css"),l=i?'[rel="stylesheet"]':"";if(!!s)for(let u=n.length-1;u>=0;u--){const p=n[u];if(p.href===t&&(!i||p.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${t}"]${l}`))return;const a=document.createElement("link");if(a.rel=i?"stylesheet":x,i||(a.as="script",a.crossOrigin=""),a.href=t,document.head.appendChild(a),i)return new Promise((u,p)=>{a.addEventListener("load",u),a.addEventListener("error",()=>p(new Error(`Unable to preload CSS for ${t}`)))})})).then(()=>e())},j=[{path:"/",name:"layouts",component:()=>c(()=>import("./index.9fab4c3d.js"),["assets/js/index.9fab4c3d.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.f0ac960c.css"]),redirect:"/agreement",children:[{path:"/agreement",component:()=>c(()=>import("./index.27341835.js"),["assets/js/index.27341835.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.bae8755b.css"]),name:"agreement"},{path:"/environment",component:()=>c(()=>import("./index.3447b7ad.js"),["assets/js/index.3447b7ad.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.6adbb458.css"]),name:"environment"},{path:"/installType",component:()=>c(()=>import("./index.52651927.js"),["assets/js/index.52651927.js","assets/js/complete.127c5be6.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.5160db55.css"]),name:"installType"},{path:"/database",component:()=>c(()=>import("./index.a43e6c05.js"),["assets/js/index.a43e6c05.js","assets/js/app.498162da.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.f14faa78.css"]),name:"database"},{path:"/install",component:()=>c(()=>import("./index.af3d595a.js"),["assets/js/index.af3d595a.js","assets/js/app.498162da.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.544a9cb1.css"]),name:"install"},{path:"/complete",component:()=>c(()=>import("./index.0aaae98b.js"),["assets/js/index.0aaae98b.js","assets/js/complete.127c5be6.js","assets/js/.pnpm.806cdd1d.js","assets/css/.pnpm.9c178708.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.e9ba5796.css"]),name:"complete"}]}],_=w({routes:[...j],history:b("/")}),B=(r,e)=>{for(const o in e)if(e[o]===r)return!0;return!1},K=(r,e)=>{_.push({path:r,query:{...e}})},H="/",d=C.create({baseURL:H,timeout:6e4}),$=async r=>(r.headers.set("Content-Type","application/x-www-form-urlencoded"),r),k=r=>{var o;const e=r.data;return r.headers["content-type"]==="application/json"?B(e==null?void 0:e.code,[404,500])?((o=m)==null||o.error({message:"温馨提示",description:(e==null?void 0:e.msg)||"网络错误",duration:3}),Promise.reject(r.data)):(e==null?void 0:e.code)===200?e:Promise.reject(r.data):r.headers["content-type"]==="application/octet-stream"?Promise.resolve(e):Promise.resolve(r)},U=r=>{var e,o,s,n;if(r.response){const{data:t,status:i,statusText:l}=r.response;i===401?(e=m)==null||e.error({message:"请求错误",description:(t==null?void 0:t.msg)||l,duration:3}):i===403?(o=m)==null||o.error({message:"资源请求错误",description:(t==null?void 0:t.msg)||l,duration:3}):i===500?(s=m)==null||s.error({message:"服务器错误",description:(t==null?void 0:t.msg)||l,duration:3}):(n=m)==null||n.error({message:"系统错误",description:(t==null?void 0:t.msg)||l,duration:3})}return Promise.reject(r)};d.interceptors.request.use($);d.interceptors.response.use(k,U);const W=(r,e,o)=>d.request({url:r,params:e,method:"GET",...o}),J=(r,e,o)=>d.request({url:r,data:e,method:"POST",...o});_.beforeEach(async(r,e,o)=>{const{data:s}=await W("install/api/");(s==null?void 0:s.install)==="ok"&&r.path!=="/complete"?o({path:"/complete"}):o()});const z=I(),f=V(S);f.use(_);f.use(z);f.use(q);f.mount("#app");export{J as a,K as o,W as u};