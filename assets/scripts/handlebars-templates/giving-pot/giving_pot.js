(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['giving_pot'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, helper, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n			<img src=\"";
  if (helper = helpers.companyLogo) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.companyLogo); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">\n		";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n			<span class=\"company-name\">";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.companyName), {hash:{},inverse:self.program(6, program6, data),fn:self.program(4, program4, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</span>\n		";
  return buffer;
  }
function program4(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.companyName) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.companyName); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  return escapeExpression(stack1);
  }

function program6(depth0,data) {
  
  
  return "[logo/name]";
  }

function program8(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.summary) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.summary); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  return escapeExpression(stack1);
  }

function program10(depth0,data) {
  
  
  return "[Short Description]";
  }

function program12(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.body) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.body); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  if(stack1 || stack1 === 0) { return stack1; }
  else { return ''; }
  }

function program14(depth0,data) {
  
  
  return "[Body]";
  }

function program16(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.buttonUrl) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.buttonUrl); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  return escapeExpression(stack1);
  }

function program18(depth0,data) {
  
  
  return "#";
  }

function program20(depth0,data) {
  
  
  return "target=\"_blank\"";
  }

function program22(depth0,data) {
  
  var stack1, helper;
  if (helper = helpers.buttonText) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.buttonText); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  return escapeExpression(stack1);
  }

function program24(depth0,data) {
  
  
  return "[BUTTON TEXT]";
  }

  buffer += "<div class=\"block giving-pot-block\">\n	<header>\n		<span class=\"scope\">";
  if (helper = helpers.scope) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.scope); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</span>\n		The\n		";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.companyLogo), {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += " Giving Pot\n	</header>\n	<header class=\"second\">\n		<div class=\"col-xs-6 left-col\">Original Pot: $";
  if (helper = helpers.potSize) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.potSize); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n        <div class=\"col-xs-6 right-col\">Amount Remaining: $";
  if (helper = helpers.amountRemaining) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.amountRemaining); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n	</header>\n    <p class=\"summary\">";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.summary), {hash:{},inverse:self.program(10, program10, data),fn:self.program(8, program8, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</p>\n	<p class=\"body\">";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.body), {hash:{},inverse:self.program(14, program14, data),fn:self.program(12, program12, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</p>\n\n	<a href=\"";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.buttonUrl), {hash:{},inverse:self.program(18, program18, data),fn:self.program(16, program16, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\"\n	   ";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.buttonUrl), {hash:{},inverse:self.noop,fn:self.program(20, program20, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n	   type=\"button\"\n	   class=\"btn btn-primary\">";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.buttonText), {hash:{},inverse:self.program(24, program24, data),fn:self.program(22, program22, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</a>\n	<p class=\"real-promotion\">THIS IS NOT A REAL PROMOTION</p>\n</div>";
  return buffer;
  });
})();
