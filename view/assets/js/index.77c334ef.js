import{e as U,ao as M,A as z,d as P,g as I,w as o,aK as W,o as r,S as l,h as t,i as e,J as g,R as O,G as f,L as v,$ as E,a0 as H,I as Q,a3 as X,a1 as Y,aL as Z,aM as ee,W as ne,a7 as te,T as oe,aN as se,aO as ae,ay as re}from"./.pnpm.ba70e7a7.js";import{u as K,a as le,b as _e,s as ce,_ as ie}from"./index.c72ed940.js";const ue=()=>{var u;const y=U(),n=U(!1),_=K(),x=le(),a=M(),w=(u=a.currentRoute.value)==null?void 0:u.query,c=z({username:null,password:null,...w}),k=z({username:[{required:!0,message:"请输入登录账号"}],password:[{required:!0,message:"请输入登录密码"}]});return{appStore:_,formRef:y,loading:n,model:c,rules:k,login:async()=>{var p,d,m;try{n.value=!0,await((p=y.value)==null?void 0:p.validate()),await x.login(c);const h=(m=(d=a.currentRoute.value)==null?void 0:d.query)==null?void 0:m.redirect;await a.push(h||"/"),n.value=!1}catch{n.value=!1}}}},pe={class:"flex-1 flex justify-center items-center"},de={class:"w-450px bg-white p-30px rounded-5px"},me={class:"text-center"},ge={class:"text-26px"},fe={key:0,class:"flex justify-between items-center pt-5"},he=["href"],ve=["href"],ye={flex:"~ col",class:"items-center justify-center my-2 text-18px text-white"},xe=P({__name:"index",setup(y){const n=K(),{appStore:_,formRef:x,model:a,rules:w,login:c}=ue();return _e(()=>c()),(k,i)=>{const u=Q,p=X,d=Y,m=Z,h=ee,T=ne,$=te,D=re,F=oe,G=W;return r(),I(G,{tag:"div",class:"pro-admin-login-container h-screen flex flex-col"},{default:o(()=>{var L,b,R,C,N,S,q,V,j,A,B;return[l("div",pe,[l("div",de,[l("div",me,[t(u,{src:e(_).layout.logo,width:"50",height:"50"},null,8,["src"]),l("div",ge,g((L=e(_).site)==null?void 0:L.web_name)+" "+g((b=e(_).site)==null?void 0:b.web_title),1)]),t(h,{ref_key:"formRef",ref:x,"label-placement":"left",model:e(a),rules:e(w),class:"pt-30px"},{default:o(()=>[t(m,{path:"username"},{default:o(()=>[t(d,{value:e(a).username,"onUpdate:value":i[0]||(i[0]=s=>e(a).username=s),placeholder:"请输入登录账号"},{prefix:o(()=>[t(p,{component:e(se),size:"22"},null,8,["component"])]),_:1},8,["value"])]),_:1}),t(m,{path:"password"},{default:o(()=>[t(d,{value:e(a).password,"onUpdate:value":i[1]||(i[1]=s=>e(a).password=s),type:"password",placeholder:"请输入登录密码"},{prefix:o(()=>[t(p,{component:e(ae),size:"22"},null,8,["component"])]),_:1},8,["value"])]),_:1})]),_:1},8,["model","rules"]),t(T,{type:"primary",block:"",secondary:"",strong:"",onClick:e(c)},{default:o(()=>[O(" 提交登录 ")]),_:1},8,["onClick"]),(R=e(n).loginLink)!=null&&R.register||(C=e(n).loginLink)!=null&&C.forget?(r(),f("div",fe,[(N=e(n).loginLink)!=null&&N.register?(r(),f("a",{key:0,href:(S=e(n).loginLink)==null?void 0:S.register}," 用户注册 ",8,he)):v("",!0),(q=e(n).loginLink)!=null&&q.forget?(r(),f("a",{key:1,href:(V=e(n).loginLink)==null?void 0:V.forget}," 找回密码 ",8,ve)):v("",!0)])):v("",!0),(A=(j=e(n).loginLink)==null?void 0:j.other_login)!=null&&A.length?(r(),f(E,{key:1},[t($,null,{default:o(()=>[O(" 其他登录方式 ")]),_:1}),t(F,{justify:"center",size:30},{default:o(()=>[(r(!0),f(E,null,H(e(n).loginLink.other_login,(s,J)=>(r(),I(D,{key:J,trigger:"hover"},{trigger:o(()=>[t(u,{src:s==null?void 0:s.icon,class:"cursor-pointer",width:"40",height:"40","preview-disabled":!0},null,8,["src"])]),default:o(()=>[l("span",null,g((s==null?void 0:s.title)??"错误"),1)]),_:2},1024))),128))]),_:1})],64)):v("",!0)])]),l("div",ye," copyright "+g((B=e(n).site)==null?void 0:B.web_name)+" v"+g(e(ce)),1)]}),_:1})}}});const Le=ie(xe,[["__scopeId","data-v-28096449"]]);export{Le as default};
