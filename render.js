/**
 * Takes two arguments: URL and CSS selector, both mandatory.
 */

var 
	system = require("system"),
	page = require("webpage").create(),
	homePage = system.args[1],
	selector = system.args[2],
$$;

page.viewportSize = {
	width: 1680,
	height: 800,
};

page.open(homePage);

page.onLoadFinished = function(status) {
	var url = page.url;

	var clipRect = page.evaluate(function (selector) { 
	        return document.querySelector(selector).getBoundingClientRect();
	}, selector);

	page.zoomFactor = 1;

	page.clipRect = {
		top:    clipRect.top,
		left:   clipRect.left,
		width:  clipRect.width,
		height: clipRect.height,
        };

	console.log("Status:  " + status);
	console.log("Loaded:  " + url);

	page.render("output.png");
	phantom.exit();
};
