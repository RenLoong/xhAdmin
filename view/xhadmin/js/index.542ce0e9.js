import{d as u,r as O,b as c,o as i,n as _,p as e,u as o,t as g,g as t,w as n,v as r,s as p,F as P,q as Q,f as H}from"./.pnpm.7ed59634.js";import{u as R,a as W,b as Y,c as Z,_ as ee,d as oe}from"./index.cb044cbd.js";const se="/xhadmin/svg/logo.e3f6e6a9.svg",te="/xhadmin/jpg/login-banner.f74bd0ff.jpg",ne={class:"login-container"},ie={class:"login-position"},le={class:"login-box"},ce={class:"login-bg"},_e=["src"],ae={class:"title-mask"},re={class:"title"},de={class:"desc"},pe={class:"login-content"},ge={class:"logo-container"},ue=["src"],me={class:"title"},he={class:"form-container"},ve={class:"item"},fe={class:"item"},ke={class:"submit-button"},ye={key:0,class:"register-password"},be={key:0,class:"register"},we={key:1,class:"password"},xe={key:1,class:"other-login"},Ve={class:"other-conntent"},Ce={class:"other-item"},Ae=["src"],Le={class:"xhadmin-copyright"},Se=u({__name:"login",setup(K){const{login:m}=R(),s=W().siteInfo,a=O({username:"",password:""}),h=()=>{m(a.value)};return Y(()=>h()),(Ne,d)=>{var y,b,w,x,V,C,A,L,S,B,I,N,U,F,j,z,D,E,$,q;const X=c("el-image"),v=ee,f=c("el-input"),G=c("el-button"),k=c("router-link"),J=c("el-divider"),M=c("el-tooltip");return i(),_("div",ne,[e("div",ie,[e("div",le,[e("div",ce,[e("img",{class:"login-banner",src:o(te)},null,8,_e),e("div",ae,[e("div",re,g(((y=o(s))==null?void 0:y.web_title)||"用户登录"),1),e("div",de,g(((b=o(s))==null?void 0:b.login_desc)||"快速动态渲染式多应用SAAS框架"),1)])]),e("div",pe,[e("div",ge,[t(X,{class:"logo",src:(w=o(s))==null?void 0:w.web_logo},{error:n(()=>[e("div",null,[e("img",{src:o(se),class:"logo"},null,8,ue)])]),_:1},8,["src"]),e("div",me,g(((x=o(s))==null?void 0:x.web_name)||"XHAdmin"),1)]),e("div",he,[e("div",ve,[t(f,{modelValue:o(a).username,"onUpdate:modelValue":d[0]||(d[0]=l=>o(a).username=l),placeholder:"请输入账号",size:"large"},{prefix:n(()=>[t(v,{icon:"UserFilled",type:"element"})]),_:1},8,["modelValue"])]),e("div",fe,[t(f,{modelValue:o(a).password,"onUpdate:modelValue":d[1]||(d[1]=l=>o(a).password=l),type:"password",placeholder:"请输入密码",size:"large","show-password":""},{prefix:n(()=>[t(v,{icon:"Lock",type:"element"})]),_:1},8,["modelValue"])]),e("div",ke,[t(G,{type:"primary",class:"submit",onClick:h},{default:n(()=>[r(" 登录 ")]),_:1})]),(C=(V=o(s))==null?void 0:V.login_link)!=null&&C.register||(A=o(s).login_link)!=null&&A.forget?(i(),_("div",ye,[(S=(L=o(s))==null?void 0:L.login_link)!=null&&S.register?(i(),_("div",be,[r(" 没有账号？ "),t(k,{to:(I=(B=o(s))==null?void 0:B.login_link)==null?void 0:I.register},{default:n(()=>[r(" 立即注册 ")]),_:1},8,["to"])])):p("",!0),(U=(N=o(s))==null?void 0:N.login_link)!=null&&U.forget?(i(),_("div",we,[t(k,{to:(j=(F=o(s))==null?void 0:F.login_link)==null?void 0:j.forget},{default:n(()=>[r(" 忘记密码 ")]),_:1},8,["to"])])):p("",!0)])):p("",!0),(E=(D=(z=o(s))==null?void 0:z.login_link)==null?void 0:D.other_login)!=null&&E.length?(i(),_("div",xe,[t(J,null,{default:n(()=>[r(" 其他登录方式 ")]),_:1}),e("div",Ve,[(i(!0),_(P,null,Q((q=($=o(s))==null?void 0:$.login_link)==null?void 0:q.other_login,l=>(i(),H(M,{key:l,effect:"dark",content:l.title,placement:"top"},{default:n(()=>[e("div",Ce,[e("img",{src:l.url,class:"other-logo"},null,8,Ae)])]),_:2},1032,["content"]))),128))])])):p("",!0)])])])]),e("div",Le,[t(Z)])])}}});const Be=oe(Se,[["__scopeId","data-v-c4ec7579"]]),Ie=u({name:"/login"}),je=u({...Ie,setup(K){return(m,T)=>(i(),H(Be))}});export{je as default};