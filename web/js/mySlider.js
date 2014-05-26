'use strict';

var wa;
if(wa === undefined)
	wa = {};

wa.Slider = new dejavu.Class.declare({
	$name: 'slider',

	_root: null,
	
	_nav: null,
	_firstSlide: null,
	_buttons: null,
	
	_currentSlide: 0,

	initialize: function(root) {
		if(root.length !== 1)
		{
			throw 'One an only one element should be provided to Slider';
		}
		
		this._root = root;
		
		this._firstSlide = 
				root.find('.slider-in > *:first-child');
		this._nav = root.find('.slider-nav');
		this._buttons = root.find('.slider-nav').children();
		
		// Bind
		var myObj = this;
		root.find('button.slider-right').click(function() {
			myObj.goToNextSlide();
		}.$member());
		
		root.find('button.slider-left').click(function() {
			myObj.goToPrevSlide();
		}.$member());
		
		this._buttons.click(function() {
			var i = myObj._buttons.index($(this));
			myObj.goToSlide(i);
		}.$member());
		
		// Goto
		this.goToSlide(0);
	},
	
	getSlideCount: function() {
		return this._root.find('.slider-in').children().length;
	},
	
	goToSlide: function(i) {
		this._currentSlide = i;
		
		this._firstSlide.css('margin-left', '-' + i + '0%');
		this._buttons.removeClass('current');
		this._buttons.eq(i).addClass('current');
		
		this._root.find('button.slider-right')
				.prop('disabled', this.getSlideCount() - 1 === i);
		this._root.find('button.slider-left')
				.prop('disabled', i === 0);
	},
	
	goToPrevSlide: function() {
		if(this._currentSlide > 0)
		{
			this.goToSlide(this._currentSlide - 1);
		}
	},
	
	goToNextSlide: function() {
		var slideCount = this.getSlideCount();
		if(this._currentSlide < slideCount - 1)
		{
			this.goToSlide(this._currentSlide + 1);
		}
	}
});