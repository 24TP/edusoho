webpackJsonp(["app/js/courseset/create/index"],{f9fb8354c8bd8e47ad7e:function(e,t){"use strict";function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}Object.defineProperty(t,"__esModule",{value:!0});var i=function(){function e(e,t){for(var r=0;r<t.length;r++){var i=t[r];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(t,r,i){return r&&e(t.prototype,r),i&&e(t,i),t}}(),n=function(){function e(t){r(this,e),this.$element=t,this.$courseSetType=this.$element.find(".js-courseSetType"),this.$currentCourseSetType=this.$element.find(".js-courseSetType.active"),this.init()}return i(e,[{key:"init",value:function(){var e=this;this.validator=this.$element.validate({rules:{title:{maxlength:100,required:!0,trim:!0,course_title:!0}},messages:{title:{required:Translator.trans("course_set.title_required_error_hint"),trim:Translator.trans("course_set.title_required_error_hint")}}}),this.$courseSetType.click(function(t){e.$courseSetType.removeClass("active"),e.$currentCourseSetType=$(t.currentTarget).addClass("active"),$('input[name="type"]').val(e.$currentCourseSetType.data("type"));var r=$("#course_title");r.rules("remove"),"live"!=e.$currentCourseSetType.data("type")?r.rules("add",{required:!0,trim:!0,course_title:!0}):r.rules("add",{required:!0,trim:!0,open_live_course_title:!0})})}}]),e}();t.default=n},0:function(e,t,r){"use strict";function i(e){return e&&e.__esModule?e:{default:e}}var n=r("f9fb8354c8bd8e47ad7e"),u=i(n);new u.default($("#courseset-create-form"))}});