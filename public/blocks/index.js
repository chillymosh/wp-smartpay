!function(t){var e={};function r(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)r.d(n,o,function(e){return t[e]}.bind(null,o));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="",r(r.s=1)}([function(t,e){t.exports=window.React},function(t,e,r){"use strict";r.r(e);var n=r(0),o=r.n(n);function a(t){return(a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function c(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function i(t,e){return(i=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function u(t,e){return!e||"object"!==a(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function l(t){return(l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var f=wp.i18n.__,s=wp.blockEditor.InspectorControls,p=wp.components,y=p.SelectControl,b=p.TextControl,m=p.CardBody,d=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&i(t,e)}(d,t);var e,r,n,a,p=(n=d,a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}(),function(){var t,e=l(n);if(a){var r=l(this).constructor;t=Reflect.construct(e,arguments,r)}else t=e.apply(this,arguments);return u(this,t)});function d(t){return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,d),p.call(this,t)}return e=d,(r=[{key:"render",value:function(){return o.a.createElement(s,null,o.a.createElement(m,null,o.a.createElement(y,{label:f("Shortcode behavior","smartpay"),value:this.props.attributes.behavior,onChange:this.props.onSetBehavior,options:[{value:null,label:f("Select a behavior","smartpay"),disabled:!0},{value:"popup",label:f("Popup","smartpay")},{value:"embedded",label:f("Embedded","smartpay")}]}),"popup"===this.props.attributes.behavior&&o.a.createElement(b,{label:f("Button label","smartpay"),value:this.props.attributes.label,onChange:this.props.onSetLabel})))}}])&&c(e.prototype,r),d}(o.a.Component);function h(t){return(h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function v(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function S(t,e){return(S=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function g(t,e){return!e||"object"!==h(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function w(t){return(w=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}wp.i18n.__;var O=wp.components.SelectControl,_=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&S(t,e)}(i,t);var e,r,n,a,c=(n=i,a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}(),function(){var t,e=w(n);if(a){var r=w(this).constructor;t=Reflect.construct(e,arguments,r)}else t=e.apply(this,arguments);return g(this,t)});function i(t){return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,i),c.call(this,t)}return e=i,(r=[{key:"render",value:function(){return o.a.createElement(O,{className:this.props.class,value:this.props.formId,onChange:this.props.onSetId,options:this.props.formOptions})}}])&&v(e.prototype,r),i}(o.a.Component);function E(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}var j=wp.i18n.__,R=wp.blocks.registerBlockType,P=wp.element.Fragment;function x(t){return(x="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function C(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function k(t,e){return(k=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function I(t,e){return!e||"object"!==x(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function N(t){return(N=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}R("smartpay/form",{title:j("SmartPay Form","smartpay"),description:j("Simple block to show a form","smartpay"),icon:"format-aside",category:"widgets",attributes:{id:{type:"integer",default:0},behavior:{type:"string",default:"popup"},label:{type:"string",default:""}},edit:function(t){var e=t.attributes,r=t.setAttributes;function n(t){r({id:parseInt(t)})}var o,a=[{value:null,label:j("Select a form","smartpay")}].concat(function(t){if(Array.isArray(t))return E(t)}(o=JSON.parse(smartpay_block_editor_forms).map((function(t){return{value:t.id,label:"(#".concat(t.id,") ").concat(t.title)}})))||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(o)||function(t,e){if(t){if("string"==typeof t)return E(t,void 0);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?E(t,void 0):void 0}}(o)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}());return React.createElement(P,null,React.createElement("div",{className:"smartpay"},React.createElement("div",{className:"container block-editor form card py-4"},React.createElement("div",{className:"card-body text-center"},React.createElement("strong",null,j("SmartPay","smartpay")),React.createElement("div",{className:"d-flex justify-content-center mt-1"},React.createElement("div",{className:"col-md-8"},React.createElement("h5",{className:"text-center mb-3 m-0 font-weight-normal",style:{fontSize:"16px"}},j("Select a Form","smartpay")),React.createElement(_,{formOptions:a,formId:e.id,onSetId:n,className:"form-control form-control-sm mx-auto"})))))),React.createElement(d,{attributes:e,onSetId:n,onSetBehavior:function(t){r({behavior:t})},onSetLabel:function(t){r({label:t})}}))},save:function(t){var e=t.attributes;return'[smartpay_form id="'.concat(e.id,'" behavior="').concat(e.behavior,'" label="').concat(e.label,'"]')}});var A=wp.i18n.__,T=wp.blockEditor.InspectorControls,B=wp.components,D=B.SelectControl,M=B.TextControl,F=B.CardBody,L=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&k(t,e)}(i,t);var e,r,n,a,c=(n=i,a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}(),function(){var t,e=N(n);if(a){var r=N(this).constructor;t=Reflect.construct(e,arguments,r)}else t=e.apply(this,arguments);return I(this,t)});function i(t){return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,i),c.call(this,t)}return e=i,(r=[{key:"render",value:function(){return o.a.createElement(T,null,o.a.createElement(F,null,o.a.createElement(D,{label:A("Shortcode behavior","smartpay"),value:this.props.attributes.behavior,onChange:this.props.onSetBehavior,options:[{value:null,label:A("Select a behavior","smartpay"),disabled:!0},{value:"popup",label:A("Popup","smartpay")},{value:"embedded",label:A("Embedded","smartpay")}]}),"popup"===this.props.attributes.behavior&&o.a.createElement(M,{label:A("Button label","smartpay"),value:this.props.attributes.label,onChange:this.props.onSetLabel})))}}])&&C(e.prototype,r),i}(o.a.Component);function z(t){return(z="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function J(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function U(t,e){return(U=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function $(t,e){return!e||"object"!==z(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function q(t){return(q=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}wp.i18n.__;var G=wp.components.SelectControl,H=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&U(t,e)}(i,t);var e,r,n,a,c=(n=i,a=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}(),function(){var t,e=q(n);if(a){var r=q(this).constructor;t=Reflect.construct(e,arguments,r)}else t=e.apply(this,arguments);return $(this,t)});function i(t){return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,i),c.call(this,t)}return e=i,(r=[{key:"render",value:function(){return o.a.createElement(G,{className:this.props.class,value:this.props.productId,onChange:this.props.onSetId,options:this.props.productOptions})}}])&&J(e.prototype,r),i}(o.a.Component);function K(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}var Q=wp.i18n.__,V=wp.blocks.registerBlockType,W=wp.element.Fragment;V("smartpay/product",{title:Q("SmartPay Product","smartpay"),description:Q("Simple block to show a product","smartpay"),icon:"format-aside",category:"widgets",attributes:{id:{type:"integer",default:0},behavior:{type:"string",default:"popup"},label:{type:"string",default:""}},edit:function(t){var e=t.attributes,r=t.setAttributes;function n(t){r({id:parseInt(t)})}var o,a=[{value:null,label:Q("Select a product","smartpay")}].concat(function(t){if(Array.isArray(t))return K(t)}(o=JSON.parse(smartpay_block_editor_products).map((function(t){return{value:t.id,label:"(#".concat(t.id,") ").concat(t.title)}})))||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(o)||function(t,e){if(t){if("string"==typeof t)return K(t,void 0);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?K(t,void 0):void 0}}(o)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}());return React.createElement(W,null,React.createElement("div",{className:"smartpay"},React.createElement("div",{className:"container block-editor product card py-4"},React.createElement("div",{className:"card-body text-center"},React.createElement("strong",null,Q("SmartPay","smartpay")),React.createElement("div",{className:"d-flex justify-content-center mt-1"},React.createElement("div",{className:"col-md-8"},React.createElement("h5",{className:"text-center mb-3 m-0 font-weight-normal",style:{fontSize:"16px"}},Q("Select a Product","smartpay")),React.createElement(H,{productOptions:a,productId:e.id,onSetId:n,className:"form-control form-control-sm mx-auto"})))))),React.createElement(L,{attributes:e,onSetId:n,onSetBehavior:function(t){r({behavior:t})},onSetLabel:function(t){r({label:t})}}))},save:function(t){var e=t.attributes;return'[smartpay_product id="'.concat(e.id,'" behavior="').concat(e.behavior,'" label="').concat(e.label,'"]')}})}]);