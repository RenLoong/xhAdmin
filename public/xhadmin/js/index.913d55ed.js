import{a as j,u as V,i as R,j as X,X as $,_ as T,d as z,k as G}from"./index.28e4fa7f.js";import{d as B,U as F,e as x,o as i,g as h,u as e,h as v,i as k,t as H,l as A,p as J,j as K,a as C}from"./.pnpm.9bc250f1.js";const L=n=>(J("data-v-d4464101"),n=n(),K(),n),M={class:"xhadmin-page-pro"},Q={key:0,class:"flex items-center bg-white px-5 py-4 mb-[10px]"},W=L(()=>v("span",{class:"pl-1 text-[16px]"},"返回",-1)),Y={key:0,class:"pl-20px text-[16px] select-none"},Z={class:"form-pro flex-1"},q="GET",P=B({__name:"user",setup(n){var g;const a=F(),t=x(),o=x(),p=j().siteInfo,r=V(),u=((g=p.public_api)==null?void 0:g.user_edit)??"",s={...a.query};s!=null&&s.isBack&&(s==null||delete s.isBack);const b=x({...s}),c=x({submitBtn:{},resetBtn:{}}),_=d=>{var m;const f=(m=d==null?void 0:d.response)==null?void 0:m.extraConfig;c.value=f},l=()=>{o.value.setDisabled(!1)},O=()=>{t.value.submit()},E=()=>{r.getUserInfo(),l()},w=()=>{t.value.reset()},D=()=>{o.value.setDisabled(!0)};return(d,f)=>{var y,S,I;const m=T;return i(),h("div",M,[(y=e(a).query)!=null&&y.isBack?(i(),h("div",Q,[v("div",{class:"flex items-center cursor-pointer select-none",onClick:f[0]||(f[0]=(...N)=>e(R)&&e(R)(...N))},[k(m,{icon:"ArrowLeftOutlined",size:16}),W]),(S=e(a).meta)!=null&&S.title?(i(),h("div",Y,H((I=e(a).meta)==null?void 0:I.title),1)):A("",!0)])):A("",!0),v("div",Z,[k(e(X),{ref_key:"formRef",ref:t,class:"xh-form-create",api:e(u),method:q,"ajax-params":e(b),"onUpdate:initOk":_,"onUpdate:submit":D,"onUpdate:submitOk":E,"onUpdate:submitError":l,"onUpdate:restOk":l,"onUpdate:initError":l},null,8,["api","ajax-params"])]),k(e($),{ref_key:"buttonRef",ref:o,class:"xh-buttons-pro","extra-config":e(c),"onUpdate:submit":O,"onUpdate:rest":w},null,8,["extra-config"])])}}});const ee=z(P,[["__scopeId","data-v-d4464101"]]),te=B({name:"/personal"}),ne=B({...te,setup(n){var p,r;const t=j().siteInfo,o=(p=t==null?void 0:t.remote_url)==null?void 0:p.map(u=>u.path),U=o==null?void 0:o.includes(((r=t.public_api)==null?void 0:r.user_edit)??"");return(u,s)=>{var c,_;const b=G;return e(U)&&((c=e(t).public_api)!=null&&c.user_edit)?(i(),C(b,{key:0,file:(_=e(t).public_api)==null?void 0:_.user_edit},null,8,["file"])):(i(),C(ee,{key:1}))}}});export{ne as default};
