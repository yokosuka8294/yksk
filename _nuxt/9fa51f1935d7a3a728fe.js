(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{279:function(t,n,e){var content=e(289);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,e(17).default)("790a7675",content,!0,{sourceMap:!1})},282:function(t,n,e){var content=e(298);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,e(17).default)("e882ae98",content,!0,{sourceMap:!1})},283:function(t,n,e){var content=e(300);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,e(17).default)("97e7ab7c",content,!0,{sourceMap:!1})},288:function(t,n,e){"use strict";var o=e(279);e.n(o).a},289:function(t,n,e){(n=e(16)(!1)).push([t.i,".header[data-v-174a4490]{display:flex;align-items:flex-end;flex-wrap:wrap}.pageTitle[data-v-174a4490]{font-size:30px;font-size:1.875rem;color:#4d4d4d;display:flex;align-items:center;line-height:1.35;font-weight:normal;margin:0 .5em 0 0}@media screen and (max-width: 600px){.pageTitle[data-v-174a4490]{font-size:20px;font-size:1.25rem}}",""]),t.exports=n},290:function(t,n,e){"use strict";var o=e(1).a.extend({props:{icon:{type:String}}}),r=(e(288),e(9)),l=e(38),c=e.n(l),_=e(272),component=Object(r.a)(o,(function(){var t=this.$createElement,n=this._self._c||t;return n("div",{staticClass:"header"},[n("h2",{staticClass:"pageTitle"},[this.icon?n("v-icon",{staticClass:"mr-2",attrs:{size:"40"}},[this._v("\n      "+this._s(this.icon)+"\n    ")]):this._e(),this._v(" "),this._t("default")],2)])}),[],!1,null,"174a4490",null);n.a=component.exports;c()(component,{VIcon:_.a})},297:function(t,n,e){"use strict";var o=e(282);e.n(o).a},298:function(t,n,e){(n=e(16)(!1)).push([t.i,".StaticCard{background-color:#fff;box-shadow:0 0 2px rgba(0,0,0,.15);border:.5px solid #d9d9d9 !important;border-radius:4px;padding:20px;margin-bottom:20px;overflow-wrap:break-word}.StaticCard>*:not(:first-child){margin-top:1.2em}.StaticCard h3{font-size:24px;font-size:1.5rem;color:#4d4d4d;font-weight:bold}.StaticCard h4{font-size:19px;font-size:1.1875rem;color:#4d4d4d;font-weight:bold}.StaticCard h5{font-size:16px;font-size:1rem;color:#4d4d4d;font-weight:bold}.StaticCard p{margin-bottom:0}.StaticCard sup{vertical-align:top}.StaticCard ul,.StaticCard ol{padding-left:24px}.StaticCard dt:not(:first-child){margin-top:.6em}.StaticCard dd{margin-top:.6em;margin-left:2em}@media screen and (max-width: 768px){.StaticCard dd{margin-left:4.1666666667vw}}.StaticCard dd>*:not(:first-child){margin-top:.6em}.StaticCard img{max-width:100%}.StaticCard figcaption{margin-top:.6em;color:#4d4d4d}.StaticCard strong,.StaticCard em{border-bottom:2px solid #003088}.StaticCard em{font-style:normal}.StaticCard a{font-size:14px;font-size:0.875rem;color:#006ca8 !important;text-decoration:none;font-size:inherit}.StaticCard a:hover{text-decoration:underline}.StaticCard a .ExternalLinkIcon{display:inline-block;color:#006ca8;text-decoration:none;vertical-align:inherit}.StaticCard-Note{display:flex}.StaticCard-Note>span{display:block}.StaticCard-Note>span:first-child{margin-right:.5em}",""]),t.exports=n},299:function(t,n,e){"use strict";var o=e(283);e.n(o).a},300:function(t,n,e){(n=e(16)(!1)).push([t.i,".ExternalLink{text-decoration:none}.ExternalLink .ExternalLinkIcon{vertical-align:text-bottom}",""]),t.exports=n},327:function(t,n,e){"use strict";var o=e(1).a.extend(),r=(e(297),e(9)),component=Object(r.a)(o,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"StaticCard"},[this._t("default")],2)}),[],!1,null,null,null);n.a=component.exports},328:function(t,n,e){"use strict";e(102);var o=e(1).a.extend({props:{url:{type:String,default:""},iconSize:{type:Number,default:15}}}),r=(e(299),e(9)),l=e(38),c=e.n(l),_=e(272),component=Object(r.a)(o,(function(){var t=this.$createElement,n=this._self._c||t;return n("a",{staticClass:"ExternalLink",attrs:{href:this.url,target:"_blank",rel:"noopener noreferrer"}},[this._t("default"),this._v(" "),n("v-icon",{staticClass:"ExternalLinkIcon",attrs:{size:this.iconSize,"aria-label":this.$t("別タブで開く"),role:"img","aria-hidden":!1}},[this._v("\n    mdi-open-in-new\n  ")])],2)}),[],!1,null,null,null);n.a=component.exports;c()(component,{VIcon:_.a})},468:function(t,n,e){"use strict";e.r(n);var o=e(1),r=e(290),l=e(327),c=e(328),_=o.a.extend({components:{PageHeader:r.a,StaticCard:l.a,ExternalLink:c.a},head:function(){return{title:this.$t("当サイトについて")}}}),d=e(9),component=Object(d.a)(_,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"About"},[e("page-header",{staticClass:"mb-3"},[t._v("\n    "+t._s(t.$t("当サイトについて"))+"\n  ")]),t._v(" "),e("static-card",[t._v("\n    "+t._s(t.$t("当サイトは新型コロナウイルス感染症 (COVID-19) に関する最新情報を提供するために、個人が開設したものです。"))),e("br"),t._v(" "),e("br"),t._v("\n    "+t._s(t.$t("横須賀市による公式情報と客観的な数値をわかりやすく伝えることで、横須賀市にお住まいの方や、横須賀市内に拠点を持つ企業の方、横須賀市を訪れる方が、現状を把握して適切な対策を取れるようにすることを目的としています。"))),e("br"),t._v(" "),e("br"),t._v("\n    "+t._s(t.$t("このサイトは、横須賀市が管理しているものではありません。複製・改変が許されたオープンソースライセンスで公開されている、東京都公式新型コロナウイルス対策サイトの仕組みを利用しています。"))+"\n  ")]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("ブラウザ環境について")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトは以下の環境でご覧いただくことを推奨いたします。"))+"\n    ")]),t._v(" "),e("ul",[e("li",[t._v(t._s(t.$t("Microsoft Edge 最新版")))]),t._v(" "),e("li",[t._v(t._s(t.$t("Mozilla Firefox 最新版")))]),t._v(" "),e("li",[t._v("\n        "+t._s(t.$t("Google Chrome 最新版（Windows 10以上, Android 8.0以上）"))+"\n      ")]),t._v(" "),e("li",[t._v(t._s(t.$t("Safari 最新版（macOS, iOS）")))]),t._v(" "),e("li",[t._v(t._s(t.$t("Opera Software ASA Opera 最新版")))])]),t._v(" "),e("p",{staticClass:"StaticCard-Note"},[e("span",[t._v(t._s(t.$t("※")))]),t._v(" "),e("span",[t._v("\n        "+t._s(t.$t("※ 推奨環境以外で利用された場合や、推奨環境下でもご利用のブラウザの設定等によっては、正しく表示されない場合がありますのでご了承ください。"))+"\n      ")])])]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("当サイトへのリンクについて")))]),t._v(" "),e("p",[t._v(t._s(t.$t("当サイトへのリンクは自由です。")))])]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("JavaScriptについて")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトではJavaScriptを使用しております。"))),e("br"),t._v("\n      "+t._s(t.$t("JavaScriptを無効にして使用された場合、各ページが正常に動作しない、または、表示されない場合がございます。"))),e("br"),t._v("\n      "+t._s(t.$t("当サイトをご利用の際には、JavaScriptを有効にして頂きますようお願いいたします。"))+"\n    ")])]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("クッキー (Cookie) について")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトの一部ではクッキーを使用しています。"))),e("br"),t._v("\n      "+t._s(t.$t("クッキーとは、Webコンテンツからの要求で利用者の手元の端末に一時的に保存されるデータのことで、当サイトでは利用状況の把握のためにクッキーを使用する場合があります。"))),e("br")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("ブラウザに関する情報の収集を希望しない場合は、インターネット閲覧ソフト（ブラウザ）をご自身で設定することにより、クッキーの機能が働かないようにすることも可能です。"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("ただし、クッキーを受け入れない設定をされている場合は、当サイトの機能が正常に動作しない場合がございます。"))+"\n    ")])]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("Google Analyticsの利用について")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトでは、サービス向上やサイトの改善のためにGoogle LLCの提供するアクセス分析のツールであるGoogle Analyticsを利用した計測を行っております。"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("Google Analyticsでは、当サイトが発行するクッキー (Cookie) 等を利用して、Webサイトの利用データ（アクセス状況、トラフィック、閲覧環境、IPアドレスなど）を収集しております。クッキーの利用に関してはGoogleのプライバシーポリシーと規約に基づいております。"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("取得したデータはWebサイト利用状況を分析しサービスの改善につなげるため、またはサイト運営者へのレポートを作成するため、その他のサービスの提供に関わる目的に限り、これを使用します。（サイト運営者へのレポートでは、クッキーはブラウザ単位で本サイトのユーザー数をカウントするため、IPアドレスはGoogle Analyticsの分析機能を通じてアクセス元の地域分布（国、州・都道府県、都市）を把握するために利用されています。）"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("Google Analyticsの利用規約及びプライバシーポリシーに関する説明については、Google Analyticsのサイトをご覧ください。"))+"\n    ")]),t._v(" "),e("ul",[e("li",[e("external-link",{attrs:{url:t.$t("https://marketingplatform.google.com/about/analytics/terms/jp/"),"icon-size":16}},[t._v("\n          "+t._s(t.$t("Google Analytics利用規約"))+"\n        ")])],1),t._v(" "),e("li",[e("external-link",{attrs:{url:t.$t("https://policies.google.com/privacy?hl=ja"),"icon-size":16}},[t._v("\n          "+t._s(t.$t("Googleのプライバシーポリシー"))+"\n        ")])],1),t._v(" "),e("li",[e("external-link",{attrs:{url:t.$t("https://support.google.com/analytics/answer/6004245?hl=ja"),"icon-size":16}},[t._v("\n          "+t._s(t.$t("Google Analyticsに関する詳細情報"))+"\n        ")])],1)]),t._v(" "),e("i18n",{attrs:{tag:"p",path:"Google Analyticsによる情報送信を回避する場合は、Google がサポートする{addon}をご利用ください。"},scopedSlots:t._u([{key:"addon",fn:function(){return[e("external-link",{attrs:{url:t.$t("https://tools.google.com/dlpage/gaoptout?hl=ja"),"icon-size":16}},[t._v("\n          "+t._s(t.$t("測定を無効にするブラウザ アドオン"))+"\n        ")])]},proxy:!0}])})],1),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("免責事項")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトに掲載されている情報の正確性については万全を期していますが、当サイト開発者は利用者が当サイトの情報を用いて行う一切の行為について責任を負うものではありません。"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("また、利用者が当サイトを利用したことにより発生した利用者の損害及び利用者が第三者に与えた損害に対して、責任を負うものではありません。"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトに掲載されている情報は、予告なしに変更又は削除することがあります。"))+"\n    ")])]),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("データについて")))]),t._v(" "),e("i18n",{attrs:{tag:"p",path:"本サイトで公表しているデータは、{catalogWebsite}より誰でも自由にダウンロードが可能です。"},scopedSlots:t._u([{key:"catalogWebsite",fn:function(){return[e("external-link",{attrs:{url:"https://www.city.yokosuka.kanagawa.jp/city-info/koho-kocho/koho/topics/corona-data.html","icon-size":16}},[t._v("\n          "+t._s(t.$t("横須賀市内の陽性患者の発生状況データ"))+"\n        ")])]},proxy:!0}])})],1),t._v(" "),e("static-card",[e("h3",[t._v(t._s(t.$t("ソースコードについて")))]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトのソースコードはMITライセンスで公開されており、誰でも自由に利用することができます。"))+"\n      "),e("i18n",{attrs:{path:"詳しくは、{githubRepo}をご確認ください。"},scopedSlots:t._u([{key:"githubRepo",fn:function(){return[e("external-link",{attrs:{url:"https://github.com/covid19yokohama/covid19","icon-size":16}},[t._v("\n            "+t._s(t.$t("GitHub リポジトリ"))+"\n          ")])]},proxy:!0}])})],1)]),t._v(" "),e("static-card",[e("h3",[t._v("\n      "+t._s(t.$t("「区別 陽性者人数マップ」に使用している横須賀市の地図データについて"))+"\n    ")]),t._v(" "),e("p",[t._v("\n      "+t._s(t.$t("当サイトで利用している地図データは国土数値情報を出典としており、本サイトの運営者が加工しています。"))+"\n      "),e("i18n",{attrs:{path:"詳しくは、{kokudosuri}をご確認ください。"},scopedSlots:t._u([{key:"kokudosuri",fn:function(){return[e("external-link",{attrs:{url:"http://nlftp.mlit.go.jp/ksj/index.html","icon-size":16}},[t._v("\n            "+t._s(t.$t("国土数値情報 ダウンロードサービス"))+"\n          ")])]},proxy:!0}])})],1)])],1)}),[],!1,null,null,null);n.default=component.exports}}]);