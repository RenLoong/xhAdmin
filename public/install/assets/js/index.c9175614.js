import{o as g,a as P}from"./index.ad6209c0.js";import{u as V}from"./app.37dce1ba.js";import{d as $,r as x,s as q,a as r,o as E,m as F,p as _,e,w as a,D as U,E as h,u as t,v as m}from"./.pnpm.e7cb82db.js";import{_ as S}from"./_plugin-vue_export-helper.c27b6911.js";const j={class:"database-container"},z={class:"database"},A={class:"item"},I={class:"item"},L={class:"flex justify-between"},M={class:"item"},R={class:"action"},T={class:"item"},W={class:"item"},G=$({__name:"index",props:{api:{}},setup(C){const B=C,D=V(),c={layout:"vertical"},o=x({type:"mysql",host:"127.0.0.1",username:"",database:"",password:"",port:"3306",prefix:"php_",charset:"utf8mb4"}),f=x({username:"",password:""}),p=x({web_name:"",web_url:"",username:"",password:""}),N=()=>{const v={database:o.value,cloud:f.value,site:p.value};P(B.api,v).then(l=>{const{data:i}=l;D.setData(i),g("/step4")})};return q(()=>{const v=location.protocol,l=location.hostname,i=`${v}//${l}`;p.value.web_url=i}),(v,l)=>{const i=r("a-divider"),d=r("a-input"),n=r("a-form-item"),u=r("a-col"),w=r("a-input-password"),k=r("a-row"),y=r("a-form"),b=r("a-button");return E(),F("div",j,[_("div",z,[_("div",A,[e(i,{class:"title"},{default:a(()=>[m(" 数据库设置 ")]),_:1}),e(y,U(h(c)),{default:a(()=>[e(k,{gutter:30},{default:a(()=>[e(u,{span:12},{default:a(()=>[e(n,{label:"数据库类型",name:"mysql"},{default:a(()=>[e(d,{value:t(o).type,"onUpdate:value":l[0]||(l[0]=s=>t(o).type=s),placeholder:"请输入数据库类型",disabled:""},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据库主机",name:"host"},{default:a(()=>[e(d,{value:t(o).host,"onUpdate:value":l[1]||(l[1]=s=>t(o).host=s),placeholder:"请输入数据库主机"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据库名称",name:"database"},{default:a(()=>[e(d,{value:t(o).database,"onUpdate:value":l[2]||(l[2]=s=>t(o).database=s),placeholder:"请输入数据库名称"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据库用户",name:"db_user"},{default:a(()=>[e(d,{value:t(o).username,"onUpdate:value":l[3]||(l[3]=s=>t(o).username=s),placeholder:"请输入数据库用户"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据库密码",name:"db_pass"},{default:a(()=>[e(w,{value:t(o).password,"onUpdate:value":l[4]||(l[4]=s=>t(o).password=s),placeholder:"请输入数据库密码"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"安全端口",name:"port"},{default:a(()=>[e(d,{value:t(o).port,"onUpdate:value":l[5]||(l[5]=s=>t(o).port=s),placeholder:"请输入安全端口"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据表前缀",name:"prefix"},{default:a(()=>[e(d,{value:t(o).prefix,"onUpdate:value":l[6]||(l[6]=s=>t(o).prefix=s),placeholder:"请输入数据表前缀"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"数据字符集",name:"charset"},{default:a(()=>[e(d,{value:t(o).charset,"onUpdate:value":l[7]||(l[7]=s=>t(o).charset=s),placeholder:"请输入数据字符集",disabled:""},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16)]),_("div",I,[e(i,{class:"title"},{default:a(()=>[m(" 云服务登录 ")]),_:1}),e(y,U(h(c)),{default:a(()=>[e(n,{label:"登录账号",name:"cloud_user"},{default:a(()=>[e(d,{value:t(f).username,"onUpdate:value":l[8]||(l[8]=s=>t(f).username=s),placeholder:"请输入云服务账号"},null,8,["value"])]),_:1}),e(n,{label:"登录密码",name:"cloud_pass"},{default:a(()=>[e(w,{value:t(f).password,"onUpdate:value":l[9]||(l[9]=s=>t(f).password=s),placeholder:"请输入云服务登录密码"},null,8,["value"])]),_:1}),_("div",L,[e(b,{href:"http://www.kfadmin.net//#/register",target:"_blank",type:"link"},{default:a(()=>[m(" 注册账号 ")]),_:1}),e(b,{href:"http://www.kfadmin.net/#/forgot",target:"_blank",type:"link"},{default:a(()=>[m(" 忘记密码 ")]),_:1})])]),_:1},16)]),_("div",M,[e(i,{class:"title"},{default:a(()=>[m(" 站点设置 ")]),_:1}),e(y,U(h(c)),{default:a(()=>[e(k,{gutter:30},{default:a(()=>[e(u,{span:12},{default:a(()=>[e(n,{label:"站点名称",name:"web_name"},{default:a(()=>[e(d,{value:t(p).web_name,"onUpdate:value":l[10]||(l[10]=s=>t(p).web_name=s),placeholder:"请输入站点名称"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"站点域名（结尾不带斜杠）",disabled:"",name:"domain"},{default:a(()=>[e(d,{value:t(p).web_url,"onUpdate:value":l[11]||(l[11]=s=>t(p).web_url=s),placeholder:"请输入站点域名"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"管理员账号",name:"username"},{default:a(()=>[e(d,{value:t(p).username,"onUpdate:value":l[12]||(l[12]=s=>t(p).username=s),placeholder:"请输入管理员账号"},null,8,["value"])]),_:1})]),_:1}),e(u,{span:12},{default:a(()=>[e(n,{label:"管理员密码",name:"password"},{default:a(()=>[e(w,{value:t(p).password,"onUpdate:value":l[13]||(l[13]=s=>t(p).password=s),placeholder:"请输入管理员密码"},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},16)])]),_("div",R,[_("div",T,[e(b,{type:"primary",onClick:l[14]||(l[14]=s=>t(g)("/step2"))},{default:a(()=>[m(" 上一步 ")]),_:1})]),_("div",W,[e(b,{type:"primary",danger:"",onClick:N},{default:a(()=>[m(" 下一步 ")]),_:1})])])])}}});const Q=S(G,[["__scopeId","data-v-3b677f98"]]);export{Q as default};