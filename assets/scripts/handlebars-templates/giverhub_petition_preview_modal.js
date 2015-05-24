(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['giverhub_petition_preview_modal'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, helper, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n						<div class=\"giverhub-petition-media-content\">\n				        	img: <img src=\"";
  if (helper = helpers.img_url) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.img_url); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\"><br/>\n						</div>\n					";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n						<div class=\"giverhub-petition-media-content\">\n							<img src=\"";
  if (helper = helpers.photo_src) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.photo_src); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" style=\"vertical-align: middle;\"><br/>\n						</div>						\n					";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n						<div class=\"giverhub-petition-media-content\">\n						    <iframe\n								class=\"youtube-player youtube-preview-iframe\"\n								type=\"text/html\"\n						    	width=\"100%\"\n						    	height=\"\"\n						    	src=\"https://www.youtube.com/embed/";
  if (helper = helpers.video_id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.video_id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\"\n						    	allowfullscreen\n						    	frameborder=\"0\"></iframe>\n						</div>\n					";
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n						<div class=\"giverhub-petition-media-content\">\n							";
  if (helper = helpers.no_media) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.no_media); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n						</div>\n					";
  return buffer;
  }

  buffer += "<div class=\"col-md-5\">\n	<div class=\"modal-content\">\n        <header class=\"modal-header clearfix\">\n            <span class=\"header\">Review petition</span>\n            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">x</button>\n        </header>\n\n		<section class=\"modal-body clearfix giverhub-petition-modal-body\">\n			<h2 class=\"txtCntr\">Review</h2>\n			<div class=\"gh_lightbox_separator\"></div>\n			<div class=\"row\">\n				<div class=\"col-md-6\">\n	\n					";
  stack1 = (helper = helpers.exists || (depth0 && depth0.exists),options={hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data},helper ? helper.call(depth0, (depth0 && depth0.img_url), options) : helperMissing.call(depth0, "exists", (depth0 && depth0.img_url), options));
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n					";
  stack1 = (helper = helpers.exists || (depth0 && depth0.exists),options={hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data},helper ? helper.call(depth0, (depth0 && depth0.photo_src), options) : helperMissing.call(depth0, "exists", (depth0 && depth0.photo_src), options));
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n					";
  stack1 = (helper = helpers.exists || (depth0 && depth0.exists),options={hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data},helper ? helper.call(depth0, (depth0 && depth0.video_id), options) : helperMissing.call(depth0, "exists", (depth0 && depth0.video_id), options));
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n					";
  stack1 = (helper = helpers.exists || (depth0 && depth0.exists),options={hash:{},inverse:self.noop,fn:self.program(7, program7, data),data:data},helper ? helper.call(depth0, (depth0 && depth0.no_media), options) : helperMissing.call(depth0, "exists", (depth0 && depth0.no_media), options));
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n\n				</div>\n			\n				<div class=\"col-md-6 giverhub-petition-by-what\">\n					<div><span style=\"font-weight:bold\">Petition By:</span> ";
  if (helper = helpers.giverhub_petition_added_by) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.giverhub_petition_added_by); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</div>\n					<div style=\"margin-top:8px;\"><span style=\"font-weight:bold\">Petitioning</span>  ";
  if (helper = helpers.target) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.target); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n					<div>";
  if (helper = helpers.what) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.what); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</div>\n				</div>\n			</div>\n\n			<div class=\"row\">\n				<div class=\"col-md-12 giverhub-petition-why\">";
  if (helper = helpers.why) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.why); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</div>\n			</div>\n			\n		</section>\n\n		<footer class=\"modal-footer\">\n			<button type=\"button\" class=\"btn btn-success btn-publish\" data-loading-text=\"Publish\">Publish</button>\n			<button type=\"button\" class=\"btn btn-edit\">Edit</button>\n		</footer>\n	</div>\n</div>\n";
  return buffer;
  });
})();
