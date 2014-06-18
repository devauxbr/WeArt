'use strict';

var wa;
if(wa === undefined)
	wa = {};

wa.MagicBox = new dejavu.Class.declare({
	$name: 'magicBox',

	_root: null,

	initialize: function(root) {
		if(root.length !== 1)
		{
			throw 'One and only one element should be provided to MagicBox';
		}
		
		this._root = root;

		// Bind
		var myObj = this;
		root.children().click(function() {
			myObj.goToSlide($(this));
		}.$member());
	},

	goToSlide: function(slide) {
		this._root.children().addClass('off');
		slide.removeClass('off');
	}
});