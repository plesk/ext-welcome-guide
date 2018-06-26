define(["plesk-ui-library"],function(e){return function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=7)}([function(t,n){t.exports=e},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=n(0);t.default=function(){return(0,r.createElement)("style",null,"\n            #welcome-box {\n                margin-bottom: 20px;\n            }\n\n            #welcome-box .welcome-single-page > .welcome-single-item:first-child {\n                border-top: 1px solid #DCDCDC;\n            }\n\n            #welcome-box .welcome-single-item {\n                padding: 20px 10px;\n                border-bottom: 1px solid #DCDCDC;\n            }\n\n            #welcome-box .welcome-single-item.completed\n            {\n                pointer-events: none;\n                opacity: 0.4;\n                background: #EAEAEA;\n            }\n\n            #welcome-box .welcome-single-item .button-toggle-status\n            {\n                pointer-events: auto;\n                float: right;\n            }\n\n            #welcome-box .welcome-single-item .pul-item__title {\n                font-weight: bold;\n                margin-bottom: 4px;\n                display: inline-block;\n                font-size: 14px;\n            }\n\n            #welcome-box .pul-card__side {\n                max-width: 300px;\n            }\n\n            #welcome-box .pul-button--secondary:hover {\n                background-image: url('/modules/welcome/images/buttonToggleStatusHover.png');\n                background-repeat: round;\n            }\n        ")}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=function(){function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,n,r){return n&&e(t.prototype,n),r&&e(t,r),t}}(),o=n(0);var l=function(e){function t(e){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t);var n=function(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n.setCompletedStatus=function(e){return void 0!==n.state&&void 0!==n.state.completed?n.state.completed:!0===e},n.setCompletedButtonImage=function(e){return!0===e?"check-mark":""},n.setStepToggleStatus=function(){n.setState({completed:!n.state.completed}),n.setState({completedIcon:n.setCompletedButtonImage(!n.state.completed)})},n.setToggleButtonIntent=function(){return n.state.completed?"success":"secondary"},n.step=e,n.indexGroup=e.indexGroup,n.index=e.index,n.state={completed:n.setCompletedStatus(Boolean(n.step.completed))},n.state={completedIcon:n.setCompletedButtonImage(Boolean(n.state.completed))},n}return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(t,o.Component),r(t,[{key:"render",value:function(){var e=this;return(0,o.createElement)("div",{className:this.state.completed?"welcome-single-item completed":"welcome-single-item"},(0,o.createElement)(o.Grid,{xs:3,gap:"xs"},(0,o.createElement)(o.GridCol,{xs:9},(0,o.createElement)(o.Item,{icon:{src:this.step.image,size:"64"},title:this.step.title},(0,o.createElement)("div",{dangerouslySetInnerHTML:{__html:this.step.description}}))),(0,o.createElement)(o.GridCol,{xs:2},(0,o.createElement)("div",{className:"welcome-single-action-button"},this.step.buttons.map(function(e){var t=function(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}(e,[]);return(0,o.createElement)(o.Button,{component:"a",href:t.url,intent:"primary"},t.title)}))),(0,o.createElement)(o.GridCol,{xs:1},(0,o.createElement)("div",{className:"button-toggle-status"},(0,o.createElement)(o.Button,{onClick:function(){return e.setStepToggleStatus()},intent:this.setToggleButtonIntent()},(0,o.createElement)(o.Icon,{name:this.state.completedIcon,size:"16"}))))))}}]),t}();t.default=l},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r,o=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},l=function(){function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,n,r){return n&&e(t.prototype,n),r&&e(t,r),t}}(),a=n(0),u=n(2),i=(r=u)&&r.__esModule?r:{default:r};function c(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}var s=function(e){function t(e){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t);var n=function(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n.setStepToggleStatus=function(e,t){n.setState(function(e,t){return{index:!e.completed}})},n.renderOutputPlain=function(e,t){return(0,a.createElement)(a.Fragment,null,(0,a.createElement)("h2",null,e.title),(0,a.createElement)("div",{className:"welcome-single-page"},e.steps.map(function(e,n){var r=c(e,[]);return(0,a.createElement)(i.default,o({},r,{indexGroup:t,index:n}))})))},n.renderOutputTab=function(e,t){return(0,a.createElement)(a.Tab,{title:e.title},(0,a.createElement)("div",{className:"welcome-single-page"},e.steps.map(function(e,n){var r=c(e,[]);return(0,a.createElement)(i.default,o({},r,{indexGroup:t,index:n}))})))},n.renderOutputWrapper=function(){return"plain"===n.view?n.renderOutputPlain(n.groups[0]):(0,a.createElement)(a.Tabs,null,n.groups.map(function(e,t){var r=c(e,[]);return n.renderOutputTab(r,t)}))},n.groups=e.data.groups,n.view=1===n.groups.length?"plain":"tabs",n.state={},n}return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(t,a.Component),l(t,[{key:"render",value:function(){return(0,a.createElement)(a.Fragment,null,this.renderOutputWrapper())}}]),t}();t.default=s},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=n(0),o=a(n(3)),l=a(n(1));function a(e){return e&&e.__esModule?e:{default:e}}t.default=function(e){var t=function(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}(e,[]);return(0,r.createElement)(r.Fragment,null,(0,r.createElement)(l.default,null),(0,r.createElement)("div",{id:"welcome-box"},(0,r.createElement)(r.Card,{title:t.data.title,sideHeader:(0,r.createElement)(r.PreviewPanel,{image:t.data.image}),sideContent:(0,r.createElement)(r.Paragraph,null,(0,r.createElement)(r.Text,null,(0,r.createElement)("div",{dangerouslySetInnerHTML:{__html:t.data.description}})))},(0,r.createElement)(o.default,t))))}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var r,o=n(4),l=(r=o)&&r.__esModule?r:{default:r};t.default=l.default},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r,o=n(0),l=n(5),a=(r=l)&&r.__esModule?r:{default:r};t.default=function(e){e.locales;var t=function(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}(e,["locales"]);return(0,o.createElement)(a.default,t)}},function(e,t,n){"use strict";var r,o=n(0),l=n(6),a=(r=l)&&r.__esModule?r:{default:r};e.exports=function(e,t){(0,o.render)((0,o.createElement)(a.default,t),e)}}])});