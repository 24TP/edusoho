webpackJsonp(["app/js/message/conversation-list/index"],[function(n,e){"use strict";$("#site-navbar").find(".message-badge-container .badge").remove(),$(".conversation-list").on("click","a",function(n){n.stopPropagation()}),$(".conversation-list").on("click",".media",function(n){window.location.href=$(this).data("url")}),$(".conversation-list").on("click",".delete-conversation-btn",function(n){if(!confirm(Translator.trans("confirm.private_message_delete.message")))return!1;var e=$(this).parents(".media");$.post($(this).data("url"),function(){e.remove()})})}]);