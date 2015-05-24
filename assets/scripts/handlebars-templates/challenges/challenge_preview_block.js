(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['challenge_preview_block'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.name) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.name); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  return escapeExpression(stack1);
  }

function program3(depth0,data) {
  
  
  return "[Challenge name]";
  }

function program5(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.description) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.description); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  if(stack1 || stack1 === 0) { return stack1; }
  else { return ''; }
  }

function program7(depth0,data) {
  
  
  return "[Challenge description]";
  }

function program9(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n		<p class=\"friends\">Friends Challenged</p>\n		<ul class=\"friend-list\">\n			";
  stack1 = helpers.each.call(depth0, (depth0 && depth0.emails), {hash:{},inverse:self.noop,fn:self.program(10, program10, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n        </ul>\n	";
  return buffer;
  }
function program10(depth0,data) {
  
  var buffer = "";
  buffer += "\n                <li>"
    + escapeExpression((typeof depth0 === functionType ? depth0.apply(depth0) : depth0))
    + "</li>\n			";
  return buffer;
  }

function program12(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n        <iframe\n                class=\"youtube-player youtube-preview-iframe\"\n                type=\"text/html\"\n                width=\"100%\"\n                height=\"\"\n                src=\"https://www.youtube.com/embed/";
  if (helper = helpers.youtubeVideoId) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.youtubeVideoId); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\"\n                allowfullscreen\n                frameborder=\"0\"></iframe>\n	";
  return buffer;
  }

function program14(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n		<p class=\"chicken\">If you're too chicken to perform the challenge, or would prefer to donate,\n		";
  if (helper = helpers.from_user_first_name) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.from_user_first_name); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " requests that you make a donation to: </p>\n        <div class=\"nonprofit\">\n            <div class=\"nonprofit-name\">";
  if (helper = helpers.charity_name) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_name); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n            ";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.charity_score), {hash:{},inverse:self.noop,fn:self.program(15, program15, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n            ";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.charity_tagline), {hash:{},inverse:self.noop,fn:self.program(17, program17, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n            <div class=\"donate-container\">\n                <label id=\"activity-post-donate-amount-label\" for=\"activity-post-donate-amount\">Easy Donate: </label>\n                <input id=\"activity-post-donate-amount\" type=\"text\" class=\"form-control charity-profile-donation-amount-input\" placeholder=\"$ Amount\"/>\n                <a class=\"btn-donate-using-cc-paypal-button-with-amount paypal\"\n				   href=\"#\"><img src=\"/img/button_paypal.png\" alt=\"Donate to ";
  if (helper = helpers.charity_name) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_name); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " using Credit Card\"></a>\n                <a class=\"btn-donate-using-cc-paypal-button-with-amount cc\"\n				   href=\"#\"><img src=\"/img/button_cc.png\" alt=\"Donate to ";
  if (helper = helpers.charity_name) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_name); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " using Credit Card\"></a>\n            </div>\n        </div>\n	";
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n                <div class=\"clearfix gh_spacer_14 progress_bar gh_popover\"\n                     data-trigger=\"hover\"\n                     data-placement=\"top\"\n                     data-container=\"body\"\n                     data-toggle=\"popover\"\n                     data-html=\"true\"\n                     data-content=\"This is the nonprofits Overall Score. It's based on program services financial data. it's the program services percentage of total functional expenses. A score of 100 means that the nonprofit spends all their money on program services.\">\n                    <div class=\"col-xs-10 col-md-10 progress progress-secondary\">\n                        <div class=\"progress-bar progress-bar-success noise\" role=\"progressbar\" aria-valuenow=\"";
  if (helper = helpers.charity_score) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_score); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:";
  if (helper = helpers.charity_score) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_score); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "%\"></div>\n                    </div>\n                    <div class=\"col-xs-2 col-md-2 progress-secondary-percent progress-bar-resize\">";
  if (helper = helpers.charity_score) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_score); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n                </div>\n            ";
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n                <div class=\"nonprofit-tagline\" title=\"";
  if (helper = helpers.charity_tagline) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_tagline); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">\n					";
  if (helper = helpers.charity_tagline) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.charity_tagline); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\n                </div>\n            ";
  return buffer;
  }

  buffer += "<div class=\"block challenge-preview-block\">\n	<header><span class=\"preview\">Challenge Preview</span>";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.name), {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</header>\n	<p class=\"description\">";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.description), {hash:{},inverse:self.program(7, program7, data),fn:self.program(5, program5, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</p>\n	";
  stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.emails)),stack1 == null || stack1 === false ? stack1 : stack1.length), {hash:{},inverse:self.noop,fn:self.program(9, program9, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n	";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.video_id), {hash:{},inverse:self.noop,fn:self.program(12, program12, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n    <div class=\"accept-reject\">\n        <button class=\"btn btn-success btn-lg\">ACCEPT CHALLENGE</button>\n        <button class=\"btn btn-danger btn-lg\">REJECT CHALLENGE</button>\n    </div>\n	";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.charity_name), {hash:{},inverse:self.noop,fn:self.program(14, program14, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n</div>";
  return buffer;
  });
})();
