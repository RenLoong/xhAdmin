import{e as B,U as K,A as V,d as Q,g as z,w as o,aN as T,o as r,a5 as l,h as t,i as e,J as w,a0 as I,G as f,L as h,a3 as O,a4 as Y,I as H,S as M,a6 as W,aO as X,aP as Z,a1 as ee,ab as ne,Y as te,aQ as oe,aR as se,aw as ae}from"./.pnpm.b5f650ba.js";import{u as E,a as re,b as le,_ as _e,c as ce}from"./index.3f58f8a7.js";const ie=()=>{var u;const v=B(),n=B(!1),_=E(),x=re(),a=K(),y=(u=a.currentRoute.value)==null?void 0:u.query,c=V({username:null,password:null,...y}),k=V({username:[{required:!0,message:"请输入登录账号"}],password:[{required:!0,message:"请输入登录密码"}]});return{appStore:_,formRef:v,loading:n,model:c,rules:k,login:async()=>{var p,d,m;try{n.value=!0,await((p=v.value)==null?void 0:p.validate()),await x.login(c);const g=(m=(d=a.currentRoute.value)==null?void 0:d.query)==null?void 0:m.redirect;await a.push(g||"/"),n.value=!1}catch{n.value=!1}}}},ue={class:"flex-1 flex justify-center items-center"},pe={class:"w-380px bg-white p-30px rounded-5px"},de={class:"text-center"},me={class:"text-26px"},fe={key:0,class:"flex justify-between items-center pt-5"},ge=["href"],he=["href"],ve={class:"flex justify-center py-2 items-center text-16px text-white"},xe=Q({__name:"index",setup(v){const n=E(),{appStore:_,formRef:x,model:a,rules:y,login:c}=ie();return le(()=>c()),(k,i)=>{const u=H,p=M,d=W,m=X,g=Z,P=ee,$=ne,D=ae,F=te,G=T;return r(),z(G,{tag:"div",class:"pro-admin-login-container h-screen flex flex-col"},{default:o(()=>{var L,b,R,C,N,S,q,U,j,A;return[l("div",ue,[l("div",pe,[l("div",de,[t(u,{src:e(_).layout.logo,width:"50",height:"50"},null,8,["src"]),l("div",me,w((L=e(_).site)==null?void 0:L.web_name)+" "+w((b=e(_).site)==null?void 0:b.web_title),1)]),t(g,{ref_key:"formRef",ref:x,"label-placement":"left",model:e(a),rules:e(y),class:"pt-30px"},{default:o(()=>[t(m,{path:"username"},{default:o(()=>[t(d,{value:e(a).username,"onUpdate:value":i[0]||(i[0]=s=>e(a).username=s),placeholder:"请输入登录账号"},{prefix:o(()=>[t(p,{component:e(oe),size:"22"},null,8,["component"])]),_:1},8,["value"])]),_:1}),t(m,{path:"password"},{default:o(()=>[t(d,{value:e(a).password,"onUpdate:value":i[1]||(i[1]=s=>e(a).password=s),type:"password",placeholder:"请输入登录密码"},{prefix:o(()=>[t(p,{component:e(se),size:"22"},null,8,["component"])]),_:1},8,["value"])]),_:1})]),_:1},8,["model","rules"]),t(P,{type:"primary",block:"",secondary:"",strong:"",onClick:e(c)},{default:o(()=>[I(" 提交登录 ")]),_:1},8,["onClick"]),(R=e(n).loginLink)!=null&&R.register||(C=e(n).loginLink)!=null&&C.forget?(r(),f("div",fe,[(N=e(n).loginLink)!=null&&N.register?(r(),f("a",{key:0,href:(S=e(n).loginLink)==null?void 0:S.register}," 用户注册 ",8,ge)):h("",!0),(q=e(n).loginLink)!=null&&q.forget?(r(),f("a",{key:1,href:(U=e(n).loginLink)==null?void 0:U.forget}," 找回密码 ",8,he)):h("",!0)])):h("",!0),(A=(j=e(n).loginLink)==null?void 0:j.other_login)!=null&&A.length?(r(),f(O,{key:1},[t($,null,{default:o(()=>[I(" 其他登录方式 ")]),_:1}),t(F,{justify:"center",size:30},{default:o(()=>[(r(!0),f(O,null,Y(e(n).loginLink.other_login,(s,J)=>(r(),z(D,{key:J,trigger:"hover"},{trigger:o(()=>[t(u,{src:s==null?void 0:s.icon,class:"cursor-pointer",width:"40",height:"40","preview-disabled":!0},null,8,["src"])]),default:o(()=>[l("span",null,w((s==null?void 0:s.title)??"错误"),1)]),_:2},1024))),128))]),_:1})],64)):h("",!0)])]),l("div",ve,[t(e(_e))])]}),_:1})}}});const ke=ce(xe,[["__scopeId","data-v-ca376d2c"]]);export{ke as default};