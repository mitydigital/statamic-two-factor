function n(s,t,e,r,c,l,_,m){var a=typeof s=="function"?s.options:s;t&&(a.render=t,a.staticRenderFns=e,a._compiled=!0),r&&(a.functional=!0),l&&(a._scopeId="data-v-"+l);var i;if(_?(i=function(o){o=o||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!o&&typeof __VUE_SSR_CONTEXT__<"u"&&(o=__VUE_SSR_CONTEXT__),c&&c.call(this,o),o&&o._registeredComponents&&o._registeredComponents.add(_)},a._ssrRegister=i):c&&(i=m?function(){c.call(this,(a.functional?this.parent:this).$root.$options.shadowRoot)}:c),i)if(a.functional){a._injectStyles=i;var u=a.render;a.render=function(p,d){return i.call(d),u(p,d)}}else{var f=a.beforeCreate;a.beforeCreate=f?[].concat(f,i):[i]}return{exports:s,options:a}}const v={mixins:[Fieldtype],props:{route:{required:!0}}};var h=function(){var t=this,e=t._self._c;return e("div",[e("div",{staticClass:"mb-8"},[e("div",{staticClass:"font-semibold mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.enable.title")))]),e("div",{staticClass:"text-xs text-gray-700 mb-4"},[e("p",{staticClass:"mb-1"},[t._v(t._s(t.__("statamic-two-factor::profile.enable.intro")))])]),e("div",{staticClass:"flex space-x-2"},[e("a",{staticClass:"btn",attrs:{href:t.route}},[t._v(t._s(t.__("statamic-two-factor::profile.enable.enable")))])])])])},g=[],w=n(v,h,g,!1,null,null,null,null);const C=w.exports,x={mixins:[Fieldtype],props:{route:{required:!0}},computed:{timerId(){return"statamic-two-factor-locked-"+this._uid}},data:function(){return{confirming:!1}},methods:{action(){this.$progress.start(this.timerId),fetch(this.route,{method:"DELETE",headers:{"X-CSRF-TOKEN":Statamic.$config.get("csrfToken"),"X-Requested-With":"XMLHttpRequest"}}).then(s=>s.json()).then(s=>{this.$toast.success(__("statamic-two-factor::profile.locked.success")),this.$emit("update","locked",!1)}).catch(s=>{this.$toast.error(s.message)}).finally(()=>{this.$progress.complete(this.timerId),this.confirming=!1})}}};var b=function(){var t=this,e=t._self._c;return e("div",[e("div",{staticClass:"mb-8"},[e("div",{staticClass:"font-semibold mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.locked.title")))]),e("div",{staticClass:"text-xs text-gray-700 mb-4"},[e("p",{staticClass:"mb-1"},[t._v(t._s(t.__("statamic-two-factor::profile.locked.intro")))])]),e("div",{staticClass:"flex space-x-2"},[e("button",{staticClass:"btn",on:{click:function(r){r.preventDefault(),t.confirming=!0}}},[t._v(t._s(t.__("statamic-two-factor::profile.locked.unlock")))])])]),t.confirming?e("confirmation-modal",{attrs:{title:t.__("statamic-two-factor::profile.locked.confirm_title"),danger:!0},on:{confirm:t.action,cancel:function(r){t.confirming=!1}}},[e("p",{staticClass:"mb-2",domProps:{innerHTML:t._s(t.__("statamic-two-factor::profile.locked.confirm_1"))}}),e("p",{staticClass:"mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.locked.confirm_2")))]),e("p",{staticClass:"font-medium text-red-500"},[t._v(t._s(t.__("statamic-two-factor::profile.locked.confirm_3")))])]):t._e()],1)},$=[],y=n(x,b,$,!1,null,null,null,null);const k=y.exports,F={mixins:[Fieldtype],props:{codes:{required:!0},routes:{required:!0}},computed:{timerId(){return"statamic-two-factor-recovery-codes-"+this._uid}},data:function(){return{codes:null,confirming:!1,newCodes:!1}},methods:{regenerate(){this.routes.generate&&(this.codes=null,this.confirming=!1,this.$progress.start(this.timerId),fetch(this.routes.generate,{method:"POST",headers:{"X-CSRF-TOKEN":Statamic.$config.get("csrfToken"),"X-Requested-With":"XMLHttpRequest"}}).then(s=>s.json()).then(s=>{this.codes=s.recovery_codes,this.newCodes=!0}).catch(s=>{this.$toast.error(s.message)}).finally(()=>{this.$progress.complete(this.timerId),this.confirming=!1}))},show(){this.routes.show&&(this.codes=null,this.$progress.start(this.timerId),fetch(this.routes.show,{method:"GET",headers:{"X-Requested-With":"XMLHttpRequest"}}).then(s=>s.json()).then(s=>{this.codes=s.recovery_codes}).catch(s=>{this.$toast.error(s.message)}).finally(()=>{this.$progress.complete(this.timerId),this.confirming=!1}))}}};var R=function(){var t=this,e=t._self._c;return e("div",[e("div",{staticClass:"mb-8"},[e("div",{staticClass:"font-semibold mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.title")))]),e("div",{staticClass:"text-xs text-gray-700 mb-4"},[e("p",{staticClass:"mb-1"},[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.intro")))])]),e("div",{staticClass:"sm:flex -mt-2"},[e("button",{staticClass:"btn mt-2 mr-2",attrs:{disabled:t.codes},on:{click:function(r){return r.preventDefault(),t.show.apply(null,arguments)}}},[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.show.action"))+" ")]),e("button",{staticClass:"btn mt-2",on:{click:function(r){r.preventDefault(),t.confirming=!0}}},[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.regenerate.action"))+" ")])]),t.codes?e("div",{staticClass:"bg-gray-200 dark:bg-dark-650 inline-block rounded-lg px-4 py-4 mt-6"},[e("div",{staticClass:"px-2 text-sm font-medium mb-2"},[t.newCodes?e("span",[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.codes.new"))+":")]):e("span",[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.codes.show"))+":")])]),e("div",{staticClass:"font-mono flex flex-wrap text-gray-700"},t._l(t.codes,function(r,c){return e("div",{key:r,staticClass:"px-2"},[t._v(t._s(r))])}),0),t.newCodes?e("div",{staticClass:"text-sm mt-2 px-2 text-red-500"},[t._v(" "+t._s(t.__("statamic-two-factor::profile.recovery_codes.codes.footnote"))+" ")]):t._e()]):t._e()]),t.confirming?e("confirmation-modal",{attrs:{danger:!0,title:t.__("statamic-two-factor::profile.recovery_codes.regenerate.confirm.title")},on:{cancel:function(r){t.confirming=!1},confirm:t.regenerate}},[e("p",{staticClass:"mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.regenerate.confirm.body_1")))]),e("p",[t._v(t._s(t.__("statamic-two-factor::profile.recovery_codes.regenerate.confirm.body_2")))])]):t._e()],1)},T=[],E=n(F,R,T,!1,null,null,null,null);const S=E.exports,q={mixins:[Fieldtype],props:{enforced:{type:Boolean,required:!0},languageUser:{required:!0},route:{required:!0}},computed:{languageUserEnforced(){return this.languageUser},timerId(){return"statamic-two-factor-reset-"+this._uid}},data:function(){return{confirming:!1}},methods:{action(){this.$progress.start(this.timerId),fetch(this.route,{method:"DELETE",headers:{"X-CSRF-TOKEN":Statamic.$config.get("csrfToken"),"X-Requested-With":"XMLHttpRequest"}}).then(s=>s.json()).then(s=>{this.$toast.success(__("statamic-two-factor::profile.reset.success")),this.$emit("update","setup",!1),s.redirect&&(window.location=s.redirect)}).catch(s=>{this.$toast.error(s.message)}).finally(()=>{this.$progress.complete(this.timerId),this.confirming=!1})}}};var I=function(){var t=this,e=t._self._c;return e("div",[e("div",[e("div",{staticClass:"font-semibold mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.reset.title")))]),e("div",{staticClass:"text-xs text-gray-700 mb-4"},[e("p",{staticClass:"mb-1"},[t._v(t._s(t.__("statamic-two-factor::profile.reset."+t.languageUserEnforced+"_intro_1")))]),e("p",{staticClass:"mb-1"},[t._v(t._s(t.__("statamic-two-factor::profile.reset."+t.languageUserEnforced+"_intro_2")))])]),e("div",[e("button",{staticClass:"btn-danger",on:{click:function(r){r.preventDefault(),t.confirming=!0}}},[t._v(" "+t._s(t.__("statamic-two-factor::profile.reset.action"))+" ")])])]),t.confirming?e("confirmation-modal",{attrs:{title:t.__("statamic-two-factor::profile.reset.confirm.title"),danger:!0},on:{confirm:t.action,cancel:function(r){t.confirming=!1}}},[e("p",{staticClass:"mb-2",domProps:{innerHTML:t._s(t.__("statamic-two-factor::profile.reset.confirm."+t.languageUserEnforced+"_1"))}}),e("p",{staticClass:"mb-2",domProps:{innerHTML:t._s(t.__("statamic-two-factor::profile.reset.confirm."+t.languageUserEnforced+"_2"))}}),e("p",{staticClass:"font-medium text-red-500"},[t._v(" "+t._s(t.__("statamic-two-factor::profile.reset.confirm."+t.languageUserEnforced+"_3")))])]):t._e()],1)},X=[],L=n(q,I,X,!1,null,null,null,null);const M=L.exports,U={mixins:[Fieldtype],components:{TwoFactorEnable:C,TwoFactorLocked:k,TwoFactorRecoveryCodes:S,TwoFactorReset:M},computed:{languageUser(){return(this.meta.is_me?"me":"user")+(this.meta.is_enforced?"_enforced":"")}},data:function(){return{locked:!1,setup:!1}},methods:{updateState(s,t){this.$data[s]=t}},mounted(){this.locked=this.meta.is_locked,this.setup=this.meta.is_setup}};var H=function(){var t=this,e=t._self._c;return e("div",[t.meta.enabled?t.meta.is_me&&t.meta.is_user_edit&&!t.setup?[e("two-factor-enable",{attrs:{route:t.meta.routes.setup}})]:!t.meta.is_me&&t.meta.is_user_edit&&!t.setup?[e("div",{staticClass:"text-sm"},[e("p",{staticClass:"font-medium mb-2"},[t._v(t._s(t.__("statamic-two-factor::profile.messages.not_setup_1")))]),e("p",[t._v(t._s(t.__("statamic-two-factor::profile.messages.not_setup_2")))])])]:t.meta.is_user_edit?[t.locked?e("two-factor-locked",{attrs:{route:t.meta.routes.locked},on:{update:t.updateState}}):t._e(),t.meta.is_me?e("two-factor-recovery-codes",{attrs:{routes:t.meta.routes.recovery_codes}}):t._e(),e("two-factor-reset",{attrs:{route:t.meta.routes.reset,enforced:t.meta.is_enforced,"language-user":t.languageUser},on:{update:t.updateState}})]:[e("div",{staticClass:"text-sm"},[e("p",{staticClass:"text-red-500 font-medium"},[t._v(t._s(t.__("statamic-two-factor::profile.messages.wrong_view")))])])]:[e("div",{staticClass:"text-sm"},[e("p",[t._v(t._s(t.__("statamic-two-factor::profile.messages.not_enabled")))])])]],2)},z=[],O=n(U,H,z,!1,null,null,null,null);const D=O.exports,N={mixins:[IndexFieldtype],computed:{status(){try{return JSON.parse(this.value)}catch{return this.value}}}};var W=function(){var t=this,e=t._self._c;return e("div",{staticClass:"flex flex-nowrap space-x-4 text-xs"},[t.status.locked?e("div",{staticClass:"flex items-center flex-nowrap"},[e("svg",{staticClass:"w-4 h-4 text-orange",attrs:{fill:"currentColor",viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"}},[e("path",{attrs:{d:"M17 9V7c0-2.8-2.2-5-5-5S7 4.2 7 7v2c-1.7 0-3 1.3-3 3v7c0 1.7 1.3 3 3 3h10c1.7 0 3-1.3 3-3v-7c0-1.7-1.3-3-3-3zM9 7c0-1.7 1.3-3 3-3s3 1.3 3 3v2H9V7z"}})]),e("span",{staticClass:"ml-1 font-medium text-orange"},[t._v(t._s(t.__("statamic-two-factor::fieldtype.status.locked")))])]):t.status.setup?e("div",{staticClass:"flex items-center flex-nowrap"},[e("svg",{staticClass:"w-4 h-4 text-green-500",attrs:{fill:"currentColor",viewBox:"0 0 512 512",xmlns:"http://www.w3.org/2000/svg"}},[e("path",{attrs:{d:"M256 48C141.1 48 48 141.1 48 256s93.1 208 208 208 208-93.1 208-208S370.9 48 256 48zm-32.1 281.7c-2.4 2.4-5.8 4.4-8.8 4.4s-6.4-2.1-8.9-4.5l-56-56 17.8-17.8 47.2 47.2L340 177.3l17.5 18.1-133.6 134.3z"}})]),e("span",{staticClass:"ml-1"},[t._v(t._s(t.__("statamic-two-factor::fieldtype.status.set_up")))])]):e("div",{staticClass:"flex items-center flex-nowrap"},[e("svg",{staticClass:"w-4 h-4 text-red-500",attrs:{fill:"currentColor",viewBox:"0 0 512 512",xmlns:"http://www.w3.org/2000/svg"}},[e("path",{attrs:{d:"M403.1 108.9c-81.2-81.2-212.9-81.2-294.2 0s-81.2 212.9 0 294.2c81.2 81.2 212.9 81.2 294.2 0s81.2-213 0-294.2zM352 340.2L340.2 352l-84.4-84.2-84 83.8-11.8-11.8 84-83.8-84-83.8 11.8-11.8 84 83.8 84.4-84.2 11.8 11.8-84.4 84.2 84.4 84.2z"}})]),e("span",{staticClass:"ml-1"},[t._v(t._s(t.__("statamic-two-factor::fieldtype.status.not_set_up")))])])])},B=[],P=n(N,W,B,!1,null,null,null,null);const V=P.exports;Statamic.booting(()=>{Statamic.$components.register("two_factor-fieldtype",D),Statamic.$components.register("two_factor-fieldtype-index",V)});
