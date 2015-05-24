Handlebars.registerHelper('exists', function(variable, options) {
	if (typeof variable !== 'undefined') {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});
Handlebars.registerHelper('not_exists', function(variable, options) {
	if (typeof variable === 'undefined') {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});

Handlebars.registerHelper('is_null', function(variable, options) {
	if (variable === null) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});
Handlebars.registerHelper('is_not_null', function(variable, options) {
	if (variable !== null) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});
Handlebars.registerHelper('uc', function (str) {
	return encodeURIComponent(str);
});

