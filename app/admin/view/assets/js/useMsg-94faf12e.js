import{al as t,am as u,m as g}from"./index-15dcfb22.js";const i=(s,e="")=>{r(s,"info",e)},a=(s,e="")=>{r(s,"warning",e)},c=(s,e="")=>{r(s,"error",e)},f=(s,e="")=>{r(s,"success",e)},r=(s,e="error",o="")=>{u({message:s,type:e,onClose(){typeof o=="string"&&o!=""&&g(o),typeof o=="function"&&o()}})};var n=null;const p=(s="正在加载中...",e=!0)=>(n=t.service({lock:e,text:s,background:"rgba(0, 0, 0, 0.7)"}),n),M=()=>{setTimeout(()=>{n==null||n.close()},300)},d=Object.freeze(Object.defineProperty({__proto__:null,useLoading:p,useLoadingHide:M,useMsg:r,useMsgError:c,useMsgInfo:i,useMsgSuccess:f,useMsgWarning:a},Symbol.toStringTag,{value:"Module"}));export{M as a,d as b,p as u};
