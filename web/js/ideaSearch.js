'use strict';

var wa;
if(wa === undefined)
	wa = {};

wa.IdeaSearch = new dejavu.Class.declare({
	$name: 'ideaSearch',

	_root: null,
	_disciplines: null,
	_themes: null,
	_tagPool: null,
	_tagInput: null,
	_searchBt: null,

	initialize: function(root) {
		if(root.length !== 1)
		{
			throw 'One and only one element should be provided to IdeaSearch';
		}
		
		this._root = root;
		this._disciplines = root.find('.disciplines').find('.btn');
		this._themes = root.find('.themeList').find('.theme');
		this._tagPool = root.find('.tagPool');
		this._tagInput = this._root.find(".findTagInput");
		this._searchBt = this._root.find('.searchBt');

		// Bind
		var myObj = this;
		
		this._searchBt.click(function() {
			myObj.onSearch();
		});
		
		this._disciplines.click(function() {
			myObj.changeDiscipline($(this));
		}.$member());
		
		this._themes.click(function() {
			myObj.changeTheme($(this));
		}.$member());
		
		this._tagInput.autocomplete(
		{
			source: function (request, response)
			{
				$.ajax(
				{
					url: autoCompleteUrl,
					dataType: "json",
					type: 'POST',
					data: JSON.stringify({text: request.term}),
					contentType: "application/json; charset=utf-8",
					success: function (data)
					{
						response($.map(data, function (item)
						{
							return {
								label: item.title,
								value: item.title,
								id: item.id
							};
						}));
					}
				});
			}.$member(),
			minLength: 2,
			select: function (event, ui)
			{
				myObj.onTagSelect(ui.item);
				
				setTimeout(function() {
					myObj._tagInput.val('');
				}.$member(), 0);
			}.$member()
		});
		
		// Disable search if needed
		this.onChanged();
	},
	
	onRemoveTag: function(cross) {
		cross.closest('.tag').remove();
		
		this.onChanged();
	},

	onTagSelect: function(tag) {
		if(this._tagPool.find('.tag[data-id=' + tag.id + ']').length === 0)
		{
			// Not already added
			var tagElem = $('<span class="tag greyTag"></span>');
			tagElem.text(tag.label + ' ');
			tagElem.attr('data-id', tag.id);
			
			var icon = $('<span class="glyphicon glyphicon-remove"></span>');
			
			var self = this;
			icon.click(function () {
				self.onRemoveTag($(this));
			}.$member());
			
			tagElem.append(icon);
			this._tagPool.append(tagElem);
			
			this.onChanged();
		}
	},

	changeDiscipline: function(bt) {
		if(! bt.hasClass('active'))
		{
			this._disciplines.removeClass('active');
			bt.addClass('active');
			
			this.onChanged();
		}
	},
	
	changeTheme: function(tag) {
		if(! tag.hasClass('active'))
		{
			this._themes.removeClass('active');
			tag.addClass('active');
			
			this.onChanged();
		}
	},
	
	onChanged: function() {
		this._searchBt.prop('disabled', this.extractForm() === null);
	},
	
	onSearch: function() {
		var searchData = this.extractForm();
		
		var self = this;
		
		$.ajax(
		{
			url: searchUrl,
			dataType: "json",
			type: 'POST',
			data: JSON.stringify(searchData),
			contentType: "application/json; charset=utf-8",
			success: function (data)
			{
				self.showResult(data);
			}
		});
		
		this._searchBt.prop('disabled', true);
	},
	
	extractForm: function() {
		var disElem = this._disciplines.filter('.active');
		if(disElem.length === 0)
			return null;
		
		var themeElem = this._themes.filter('.active');
		if(themeElem.length === 0)
			return null;
		
		var tags = [];
		this._tagPool.each(function() {
			tags.push($(this).attr('data-id'));
		});
		
		var data = {
			discipline: disElem.attr('data-id'),
			theme: themeElem.attr('data-id'),
			tags: tags
		};
		
		return data;
	},
	
	showResult: function(data)
	{
		var resultElem = $('.result');
		resultElem.empty();
		
		var template = $('#template').find('.ideaBlock');
		
		for (var key in data) {
			var idea = data[key];
			
			var clone = template.clone();
			
			clone.find('h3').text(idea.title);
			clone.find('.content').html(idea.description);
			clone.find('a').attr('href', consultUrl.substr(0, consultUrl.length - 1) + idea.id);
			
			resultElem.append(clone);
		}
	}
});