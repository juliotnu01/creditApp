import{v as d,d as r,b as e,u as t,w as o,F as c,o as m,G as f,t as _,e as p,a,n as w,f as g,h as b}from"./app-a2f9185e.js";import{A as y}from"./AuthenticationCard-c023aca2.js";import{_ as h}from"./AuthenticationCardLogo-92130823.js";import{_ as x,a as k}from"./TextInput-2378217d.js";import{_ as v}from"./InputLabel-2f68020c.js";import{_ as V}from"./PrimaryButton-68c405ac.js";import"./_plugin-vue_export-helper-c27b6911.js";const F=a("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),N={key:0,class:"mb-4 font-medium text-sm text-green-600"},$=["onSubmit"],C={class:"flex items-center justify-end mt-4"},D={__name:"ForgotPassword",props:{status:String},setup(l){const s=d({email:""}),n=()=>{s.post(route("password.email"))};return(S,i)=>(m(),r(c,null,[e(t(f),{title:"Forgot Password"}),e(y,null,{logo:o(()=>[e(h)]),default:o(()=>[F,l.status?(m(),r("div",N,_(l.status),1)):p("",!0),a("form",{onSubmit:b(n,["prevent"])},[a("div",null,[e(v,{for:"email",value:"Email"}),e(x,{id:"email",modelValue:t(s).email,"onUpdate:modelValue":i[0]||(i[0]=u=>t(s).email=u),type:"email",class:"mt-1 block w-full",required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),e(k,{class:"mt-2",message:t(s).errors.email},null,8,["message"])]),a("div",C,[e(V,{class:w({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:o(()=>[g(" Email Password Reset Link ")]),_:1},8,["class","disabled"])])],40,$)]),_:1})],64))}};export{D as default};
