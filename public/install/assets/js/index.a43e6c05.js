import{o as A,a as T}from"./index.2fb2dabc.js";import{u as E}from"./app.498162da.js";import{d as F,D as j,k as z,r as f,s as G,n as L,a as v,o as B,m as S,p as r,u as t,e,w as a,E as U,G as k,y as M,v as i,B as W,C as H}from"./.pnpm.806cdd1d.js";import{_ as J}from"./_plugin-vue_export-helper.c27b6911.js";const K=m=>(W("data-v-325b1f75"),m=m(),H(),m),O={class:"database-container"},Q={class:"database"},X={key:0,class:"item"},Y=K(()=>r("div",{class:"warning"},[r("div",null,"警告：在使用宝塔安装之前，请先在宝塔面板中进行开启API接口服务"),r("div",null,"接口开启步骤：宝塔->面板设置->API接口->开启->设置白名单为服务器公网IP地址")],-1)),Z={class:"item"},ee={class:"item"},ae={class:"item"},le={class:"flex justify-between"},te={class:"item"},se={class:"action"},oe={class:"item"},ne={class:"item"},ue=F({__name:"index",props:{api:{}},setup(m){const $=m,N=E(),V=j(),h=z(),c={layout:"vertical"},b=f({panel_url:"",panel_key:""}),P=f({server_port:"39600"}),d=f({type:"mysql",host:"127.0.0.1",username:"",database:"",password:"",port:"3306",prefix:"yc_",charset:"utf8mb4"}),y=f({username:"",password:""}),p=f({web_name:"",web_url:"",username:"",password:""}),x=f(!1),R=()=>{var _;const C={serverData:P.value,btData:b.value,database:d.value,cloud:y.value,site:p.value};x.value=!0;const l=`${$.api}&c=${(_=h.query)==null?void 0:_.type}`;T(l,C).then(n=>{var u;const{data:o}=n;x.value=!1,N.setData(o),A("/install",{type:(u=h.query)==null?void 0:u.type})}).catch(()=>{x.value=!1})};return G(()=>{var o;if(!((o=h.query)==null?void 0:o.type)){L.error({message:"温馨提示",description:"请选择安装方式",duration:2}),V.push("/installType");return}const l=location.protocol,_=location.hostname,n=`${l}//${_}`;p.value.web_url=n}),(C,l)=>{var q;const _=v("a-divider"),n=v("a-input"),o=v("a-form-item"),u=v("a-col"),g=v("a-row"),w=v("a-form"),D=v("a-input-password"),I=v("a-button");return B(),S("div",O,[r("div",Q,[((q=t(h).query)==null?void 0:q.type)==="bt"?(B(),S("div",X,[e(_,{class:"title"},{default:a(()=>[i(" 宝塔服务设置 ")]),_:1}),e(w,U(k(c)),{default:a(()=>[e(g,{gutter:30},{default:a(()=>[e(u,{span:12},{default:a(()=>[e(o,{label:"面板地址",name:"panel_url"},{default:a(()=>[e(n,{value:t(b).panel_url,"onUpdate:value":l[0]||(l[0]=s=>t(b).panel_url=s),placeholder:"面板地址，示例：http://1.116.41.3:8888，不带斜杠结尾"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"宝塔API密钥",name:"panel_key"},{default:a(()=>[e(n,{value:t(b).panel_key,"onUpdate:value":l[1]||(l[1]=s=>t(b).panel_key=s),placeholder:"请输入宝塔API密钥"},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16),Y])):M("",!0),r("div",Z,[e(_,{class:"title"},{default:a(()=>[i(" 框架服务 ")]),_:1}),e(w,U(k(c)),{default:a(()=>[e(g,{gutter:30},{default:a(()=>[e(u,{span:24},{default:a(()=>[e(o,{label:"启动端口（默认39600）",name:"server_port"},{default:a(()=>[e(n,{value:t(P).server_port,"onUpdate:value":l[2]||(l[2]=s=>t(P).server_port=s),placeholder:"启动端口服务"},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16)]),r("div",ee,[e(_,{class:"title"},{default:a(()=>[i(" 数据库设置 ")]),_:1}),e(w,U(k(c)),{default:a(()=>[e(g,{gutter:30},{default:a(()=>[e(u,{span:12},{default:a(()=>[e(o,{label:"数据库类型",name:"mysql"},{default:a(()=>[e(n,{value:t(d).type,"onUpdate:value":l[3]||(l[3]=s=>t(d).type=s),placeholder:"请输入数据库类型",disabled:""},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据库主机",name:"host"},{default:a(()=>[e(n,{value:t(d).host,"onUpdate:value":l[4]||(l[4]=s=>t(d).host=s),placeholder:"请输入数据库主机"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据库名称",name:"database"},{default:a(()=>[e(n,{value:t(d).database,"onUpdate:value":l[5]||(l[5]=s=>t(d).database=s),placeholder:"请输入数据库名称"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据库用户",name:"db_user"},{default:a(()=>[e(n,{value:t(d).username,"onUpdate:value":l[6]||(l[6]=s=>t(d).username=s),placeholder:"请输入数据库用户"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据库密码",name:"db_pass"},{default:a(()=>[e(D,{value:t(d).password,"onUpdate:value":l[7]||(l[7]=s=>t(d).password=s),placeholder:"请输入数据库密码"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据库端口",name:"port"},{default:a(()=>[e(n,{value:t(d).port,"onUpdate:value":l[8]||(l[8]=s=>t(d).port=s),placeholder:"请输入安全端口"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据表前缀",name:"prefix"},{default:a(()=>[e(n,{value:t(d).prefix,"onUpdate:value":l[9]||(l[9]=s=>t(d).prefix=s),placeholder:"请输入数据表前缀"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"数据字符集",name:"charset"},{default:a(()=>[e(n,{value:t(d).charset,"onUpdate:value":l[10]||(l[10]=s=>t(d).charset=s),placeholder:"请输入数据字符集",disabled:""},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16)]),r("div",ae,[e(_,{class:"title"},{default:a(()=>[i(" 云服务登录 ")]),_:1}),e(w,U(k(c)),{default:a(()=>[e(o,{label:"登录账号",name:"cloud_user"},{default:a(()=>[e(n,{value:t(y).username,"onUpdate:value":l[11]||(l[11]=s=>t(y).username=s),placeholder:"请输入云服务账号"},null,8,["value"])]),_:1}),e(o,{label:"登录密码",name:"cloud_pass"},{default:a(()=>[e(D,{value:t(y).password,"onUpdate:value":l[12]||(l[12]=s=>t(y).password=s),placeholder:"请输入云服务登录密码"},null,8,["value"])]),_:1}),r("div",le,[e(I,{href:"http://www.kfadmin.net//#/register",target:"_blank",type:"link"},{default:a(()=>[i(" 注册账号 ")]),_:1}),e(I,{href:"http://www.kfadmin.net/#/forgot",target:"_blank",type:"link"},{default:a(()=>[i(" 忘记密码 ")]),_:1})])]),_:1},16)]),r("div",te,[e(_,{class:"title"},{default:a(()=>[i(" 站点设置 ")]),_:1}),e(w,U(k(c)),{default:a(()=>[e(g,{gutter:30},{default:a(()=>[e(u,{span:12},{default:a(()=>[e(o,{label:"站点名称",name:"web_name"},{default:a(()=>[e(n,{value:t(p).web_name,"onUpdate:value":l[13]||(l[13]=s=>t(p).web_name=s),placeholder:"请输入站点名称"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"站点域名（结尾不带斜杠）",disabled:"",name:"domain"},{default:a(()=>[e(n,{value:t(p).web_url,"onUpdate:value":l[14]||(l[14]=s=>t(p).web_url=s),placeholder:"请输入站点域名"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"管理员账号",name:"username"},{default:a(()=>[e(n,{value:t(p).username,"onUpdate:value":l[15]||(l[15]=s=>t(p).username=s),placeholder:"请输入管理员账号"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(o,{label:"管理员密码",name:"password"},{default:a(()=>[e(D,{value:t(p).password,"onUpdate:value":l[16]||(l[16]=s=>t(p).password=s),placeholder:"请输入管理员密码"},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16)])]),r("div",se,[r("div",oe,[e(I,{type:"primary",onClick:l[17]||(l[17]=s=>t(A)("/installType"))},{default:a(()=>[i(" 上一步 ")]),_:1})]),r("div",ne,[e(I,{type:"primary",disabled:t(x),danger:"",onClick:R},{default:a(()=>[i(" 下一步 ")]),_:1},8,["disabled"])])])])}}});const ie=J(ue,[["__scopeId","data-v-325b1f75"]]);export{ie as default};