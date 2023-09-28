import{d as h,c as L,a as y,w as E,u as O,r as P,o as v,b as R,z as w,e as b,f as T,g as A,E as C,h as I,i as N,j as V,k as j,l as S}from"./.pnpm.c979882e.js";(function(){const e=document.createElement("link").relList;if(e&&e.supports&&e.supports("modulepreload"))return;for(const r of document.querySelectorAll('link[rel="modulepreload"]'))s(r);new MutationObserver(r=>{for(const o of r)if(o.type==="childList")for(const i of o.addedNodes)i.tagName==="LINK"&&i.rel==="modulepreload"&&s(i)}).observe(document,{childList:!0,subtree:!0});function n(r){const o={};return r.integrity&&(o.integrity=r.integrity),r.referrerPolicy&&(o.referrerPolicy=r.referrerPolicy),r.crossOrigin==="use-credentials"?o.credentials="include":r.crossOrigin==="anonymous"?o.credentials="omit":o.credentials="same-origin",o}function s(r){if(r.ep)return;r.ep=!0;const o=n(r);fetch(r.href,o)}})();const q=h({__name:"index",setup(t){const e=L(()=>w);return(n,s)=>{const r=P("el-config-provider");return v(),y(r,{locale:O(e)},{default:E(()=>[R(n.$slots,"default")]),_:3},8,["locale"])}}}),x=h({__name:"App",setup(t){return(e,n)=>{const s=P("router-view");return v(),y(q,null,{default:E(()=>[b(s)]),_:1})}}});const D="modulepreload",k=function(t){return"/install/"+t},_={},a=function(e,n,s){if(!n||n.length===0)return e();const r=document.getElementsByTagName("link");return Promise.all(n.map(o=>{if(o=k(o),o in _)return;_[o]=!0;const i=o.endsWith(".css"),g=i?'[rel="stylesheet"]':"";if(!!s)for(let l=r.length-1;l>=0;l--){const m=r[l];if(m.href===o&&(!i||m.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${o}"]${g}`))return;const c=document.createElement("link");if(c.rel=i?"stylesheet":D,i||(c.as="script",c.crossOrigin=""),c.href=o,document.head.appendChild(c),i)return new Promise((l,m)=>{c.addEventListener("load",l),c.addEventListener("error",()=>m(new Error(`Unable to preload CSS for ${o}`)))})})).then(()=>e())},B=[{path:"/",name:"layouts",component:()=>a(()=>import("./index.342428e6.js"),["assets/js/index.342428e6.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.63700e4b.css"]),redirect:"/agreement",children:[{path:"/agreement",component:()=>a(()=>import("./index.e610eef0.js"),["assets/js/index.e610eef0.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.840a22a5.css"]),name:"agreement"},{path:"/environment",component:()=>a(()=>import("./index.689fed88.js"),["assets/js/index.689fed88.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.cec6fd17.css"]),name:"environment"},{path:"/database",component:()=>a(()=>import("./index.79e17630.js"),["assets/js/index.79e17630.js","assets/js/app.81110efc.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.2dbbee0d.css"]),name:"database"},{path:"/install",component:()=>a(()=>import("./index.c73aa62e.js"),["assets/js/index.c73aa62e.js","assets/js/app.81110efc.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.d01b4672.css"]),name:"install"},{path:"/complete",component:()=>a(()=>import("./index.217c93aa.js"),["assets/js/index.217c93aa.js","assets/js/.pnpm.c979882e.js","assets/css/.pnpm.b9d25494.css","assets/js/_plugin-vue_export-helper.c27b6911.js","assets/css/index.109c2ea8.css"]),name:"complete"}]}],f=T({routes:[...B],history:A("/")}),H=(t,e)=>{for(const n in e)if(e[n]===t)return!0;return!1},Q=(t,e)=>{f.push({path:t,query:{...e}})},$=(t,e,n="",s)=>{C({message:t,title:n,duration:2e3,type:e,...s})},u=(t,e="温馨提示",n)=>{$(t,"error",e,n)},U="/",d=I.create({baseURL:U,timeout:6e4}),W=async t=>(t.headers.set("Content-Type","application/x-www-form-urlencoded"),t),z=t=>{const e=t.data,n=t.headers["content-type"];return n.includes("application/json")?H(e==null?void 0:e.code,[404,500])?(u((e==null?void 0:e.msg)||"网络错误","温馨提示"),Promise.reject(t.data)):(e==null?void 0:e.code)===200?e:Promise.reject(e):n.includes("application/octet-stream")?Promise.resolve(e):Promise.reject(e)},F=t=>{if(t.response){const{status:e}=t.response;u(e===401?"请求错误":e===403?"资源请求错误":e===500?"服务器错误":"系统错误","温馨提示")}return Promise.reject(t)};d.interceptors.request.use(W);d.interceptors.response.use(z,F);const K=(t,e,n)=>d.request({url:t,data:e,method:"POST",...n});f.beforeEach(async(t,e,n)=>{const{data:s}=await K("/install/http/index.php");(s==null?void 0:s.installed)===!0&&t.path!=="/complete"?n({path:"/complete"}):n()});const G=N(),p=V(x);for(const[t,e]of Object.entries(j))p.component(t,e);p.use(f);p.use(G);p.use(S);p.mount("#app");export{u as a,Q as o,K as u};
