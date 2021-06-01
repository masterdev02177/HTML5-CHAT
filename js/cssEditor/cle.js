var cleCssLiveEdit = function (cle_prefix, cle_css, cle_html, cle_items) {
	var __slice = [].slice;

	var log = function() {
		var args;

		args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
		args.unshift("cle:");
		return console.log.apply(console, args);
	};
	
	var cleWindowSelector = '.' + cle_prefix + 'window';
	var cleHeaderSelector = '.' + cle_prefix + 'header';
	var cleBodySelector = '.' + cle_prefix + 'body';
	var cleRowSelector = '.' + cle_prefix + 'row';
	var cleGroupsSelector = '.' + cle_prefix + 'groups';
	var cleSelectedSelector = '.' + cle_prefix + 'selected';
	var cleUndermouseSelector = '.' + cle_prefix + 'undermouse';
	var cleButtons = cleGroupsSelector + ' input[type="button"]';
	var cleTextInputsSelectors = cleGroupsSelector + ' .' + cle_prefix + 'text';
	var cleInputsAndSelects = cleGroupsSelector + ' input[type!="button"], ' + cleGroupsSelector + ' select';
	var cleCheckSelectingSelector = '.' + cle_prefix + 'cb-selecting[type="checkbox"]';
	var cleActiveBoxSelector = '.' + cle_prefix + 'active';
	var cleHoveringBoxSelector = '.' + cle_prefix + 'hovering';
	
	if ($(cleWindowSelector).length >= 1)
	{
		log("CLE window already exists!");
		return;
	}
	
	var body = $('body');
	
	$(cle_html).appendTo(body);
	$('<style type="text/css">').html(cle_css).appendTo($('head'));

	var winTop = store.get('cleWinTop', 16);
	var winLeft = store.get('cleWinLeft', 16);
	
	var checkWindowBounds = function () {
		var wnd = $(cleWindowSelector);
		var pos = wnd.offset();
		var posTop = pos.top;
		var posLeft = pos.left;
		var wndWidth = wnd.outerWidth();
		var wndHeight = wnd.outerHeight();
		var screenWidth = $(window).width();
		var screenHeight = $(window).height();
		var viewLeft = $(document).scrollLeft();
		var viewTop = $(document).scrollTop();
		var i = (viewLeft + screenWidth - wndWidth);
		
		if (posLeft <= viewLeft)
		{
			posLeft = viewLeft;
		}
		else if (posLeft >= i)
		{
			posLeft = i;
		}
		
		i = (viewTop + screenHeight - wndHeight);
		
		if (posTop <= viewTop)
		{
			posTop = viewTop;
		}
		else if (posTop >= i)
		{
			posTop = i;
		}
		
		wnd.offset({
			top: posTop,
			left: posLeft
		});
	};
	
	$(window).resize(checkWindowBounds);
	
	$(document).ready(checkWindowBounds);
	
	checkWindowBounds();

	$(cleWindowSelector)
		.css({
			top: winTop + "px",
			left: winLeft + "px",
		})
		.drags({
			handle: cleHeaderSelector,
			onMove: function () {
				var pos = $(cleWindowSelector).offset();
				store('cleWinTop', pos.top - $(document).scrollTop());
				store('cleWinLeft', pos.left - $(document).scrollLeft());
			},
			zIndex: $(cleWindowSelector).css('z-index')
		});
	
	var activeElement = null;
	var activeElementSelector = "";
	var hoveringElement = null;
	
	var activeBoxElement = $('<div class="' + cle_prefix + 'active-box" style="dispaly:none"/>');
	var hoveringBoxElement = $('<div class="' + cle_prefix + 'hovering-box" style="dispaly:none"/>');
	
	body.append(hoveringBoxElement);
	body.append(activeBoxElement);
	//--

	var fastElems = {};
	
	log("cle version 1.08");

	var handleChange = function (event) {
		var el = $(this);
		var value = '';

		if (activeElement === null) return;
		if (activeElementSelector === "") return;

		if (typeof fastElems[activeElementSelector] === "undefined") {
			fastElems[activeElementSelector] = {
				defaults: {},
				changes: {},
				important: {}
			};
		}

		var data = fastElems[activeElementSelector];
		var cssKey = '';

		if (el.hasClass(cle_prefix + 'multiple-btn')) {
			var btns_container = el.parents('.' + cle_prefix + 'multiple-btns');
			var btns = btns_container.find('.' + cle_prefix + 'multiple-btn');
			var set = [];

			cssKey = btns_container.data('css-key');

			var action = el.data('action');

			if (typeof action !== "string") action = "";

			var resetting = el.hasClass(cle_prefix + 'multiple-btn-selected') && action === "reset";

			$.each(btns, function(k, v) {
				var btn_el = $(v);
				var btn_action = btn_el.data("action");

				if (resetting) {
					if (btn_action !== "reset") {
						btn_el.removeClass(cle_prefix + 'multiple-btn-selected');
					} else {
						btn_el.addClass(cle_prefix + 'multiple-btn-selected');
					}
				} else {
					if (btn_action === "reset") {
						btn_el.removeClass(cle_prefix + 'multiple-btn-selected');
					}
				}

				if (btn_el.hasClass(cle_prefix + 'multiple-btn-selected')) {
					set.push(btn_el.val());
				}
			});

			value = set.join(' ');
		} else {
			cssKey = el.data('css-key');
			value = el.val();
		}

		if (cssKey === "") return;

		var workingEl = $(activeElement);

		if (typeof data.defaults[cssKey] === "undefined") {
			var def_value = workingEl.css(cssKey);

			if (typeof def_value !== "undefined") {
				data.defaults[cssKey] = def_value;
			}
		}

		if ((value === 'Default') || (value === '')) {
			delete data.changes[cssKey];
			value = data.defaults[cssKey];
		} else {
			data.changes[cssKey] = value;
		}

		workingEl.css(cssKey, value);

		var changed = workingEl.css(cssKey);
		
		//log("changed", changed);
		//log(rgbToHex.apply(null, parseColor(changed)) + " !== " + value);
		
		if ((changed !== value) && (rgbToHex.apply(null, parseColor(changed)) !== value))
		{
			log(changed + " !== " + value);
			data.important[cssKey] = true;
			workingEl.xstyle(cssKey, value, 'important');
		}

		updateHighlightBoxes();
	};
	
	body
		.on('click', '.' + cle_prefix + 'group-name', function (event) {
			var el = $(event.target).parent();
			var thisCollapsed = el.hasClass(cle_prefix + 'group-collapsed');
			var group_els = el.siblings('.' + cle_prefix + 'group').not(el).addClass(cle_prefix + 'group-collapsed');
			
			if (thisCollapsed) {
				el.removeClass(cle_prefix + 'group-collapsed');
			} else {
				el.addClass(cle_prefix + 'group-collapsed');
			}
			
			return false;
		})
		.on('click', '.' + cle_prefix + 'multiple-btn', function (event) {
			var el = $(this);
			el.toggleClass(cle_prefix + 'multiple-btn-selected');
			return false;
		})
		.on('click', cleButtons, handleChange)
		.on('change keyup keydown', cleInputsAndSelects, handleChange)
		.on('change', cleCheckSelectingSelector, function (event) {
			var el = $(this);
			if (el.is(":checked"))
			{
				startSelecting();
			}
			else
			{
				stopSelecting();
			}
			
			return true;
		})

		.on('click', '#resetBtn.btn-get-css', function (event) {
			var res = confirm("Reset the whome CSS (ALL css will be removed)");
			if (res) {
				$.post('/chatadmin/ajax.php', {a: 'resetCss'}, function (res) {
					window.location.href = window.location.href;
				});
			}
		})

		.on('click', '#saveBtn.btn-get-css', function (event) {
			var cssOutput = '';
			var msg = '';

			$.each(fastElems, function(selector, data) {
				if (data.changes) {
					cssOutput += selector + " {\n";

					$.each(data.changes, function(k, v) {
						log(data);
						var imporant = (typeof data.important[k] !== "undefined") && (data.important[k]) ? " !important" : "";
						cssOutput += "\t" + k + ': ' + v + imporant + ";\n";
					});
					cssOutput += "}\n\n";
				}
			});

			// Get CSS
			if (cssOutput === "") {
				//msg += "CSS is empty! No changes was added to any selector!";
			} else {
				// save it !
				//debugger;
				$.post('/chatadmin/ajax.php', {a: 'css', css:cssOutput}, function (res) {
				});
				//console.log(cssOutput);

				//msg += "Here you can see a CSS! It is also outputed to console!\n";
				//msg += cssOutput;
				//log(cssOutput);
			}
			msg = "saved";

			if (msg != "") {
				//bootbox.alert(msg);
				alert(msg.replace(/\t/g, "  "+"  "));
			}

			return false;
		});

	var createElementGroup = function(groupName, groupLabel) {
		var uiGroup = $('<div class="' + cle_prefix + 'group ' + cle_prefix + 'group-collapsed ' + cle_prefix + 'group--' + groupName + '" style="display:none">');
		uiGroup.html('<div class="' + cle_prefix + 'group-name">:: ' + groupLabel + '</div>' +
			'<div class="' + cle_prefix + 'group-contents ' + cle_prefix + 'group--' + groupName + '-contents"></div>')

		$(cleGroupsSelector).append(uiGroup);
	};

	var addElementToGroup = function(groupName, options) {
		var uiGroupSelector = '.' + cle_prefix + 'group--' + groupName + '-contents';
		var uiGroup = $(uiGroupSelector);

		if (uiGroup.length === 0) {
			return;
		}

		uiGroup.parents('.' + cle_prefix + 'group--' + groupName).css('display', 'block');

		var cssKey = '';

		if (typeof options.css !== "") {
			cssKey = options.css;
		}

		var editRow = $('<div class="' + cle_prefix + 'row"/>');
		var rowHTML = '';

		if (typeof options.label !== "undefined" && options.label !== "") {
			rowHTML += '<span class="' + cle_prefix + 'label">' + options.label + '</span>';
		}

		if (options.type === "color") {
			rowHTML += '<input type="color" class="' + cle_prefix + 'color" name="' + options.name + '" data-css-key="' + cssKey + '">';
		} else if (options.type === "text") {
			rowHTML += '<input type="text" class="' + cle_prefix + 'text" name="' + options.name + '" data-css-key="' + cssKey + '">';
		} else if (options.type === "select") {
			rowHTML += '<select class="' + cle_prefix + 'select" name="' + options.name + '" data-css-key="' + cssKey + '">';

			var items = options.items;

			if (Array.isArray(items)) {
				for (var i = 0; i < items.length; i++) {
					var item = items[i];
					rowHTML += '<option value="' + item + '">' + item + '</option>';
				}
			}

			rowHTML += '</select>';
		} else if (options.type === "multiple") {
			var items = options.items;

			if (Array.isArray(items)) {
				rowHTML += '<div class="' + cle_prefix + 'multiple-btns" data-css-key="' + cssKey + '">';

				var resetIf = "";

				if (typeof options.resetIf === "string") {
					resetIf = options.resetIf;
				}

				for (var i = 0; i < items.length; i++) {
					var item = items[i];
					var action = "";
					if (resetIf === item) {
						action = "reset";
					}
					rowHTML += '<input type="button" class="' + cle_prefix + 'multiple-btn" value="' + item + '" data-action="' + action + '">';
				}

				rowHTML += '</div>';
			}
		} else if (options.type === "textarea") {
			rowHTML += '<textarea class="' + cle_prefix + 'textarea" name="' + options.name + '" data-css-key="' + cssKey + '"></textarea>';
		}

		editRow.html(rowHTML);
		uiGroup.append(editRow);
	};

	var setupUI = function() {
		createElementGroup('font', 'Font');
		createElementGroup('text', 'Text');
		createElementGroup('border', 'Border');
		createElementGroup('paddings-margins', 'Paddings & Margins');
		createElementGroup('position', 'Position');
		createElementGroup('background', 'Background');
		createElementGroup('additional', 'Additional');
		//createElementGroup('custom', 'Custom');
		
		$.each(cle_items, function(k, v) {
			for (var i = 0; i < v.length; i++) {
				addElementToGroup(k, v[i]);
			}
		});
	};

	var parseColor = function(color) {
		var cache,
			p = parseInt, // Use p as a byte saving reference to parseInt
			color = color.replace(/\s/g, ''); // Remove all spaces

		// Checks for 6 digit hex and converts string to integer
		if (cache = /#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})/.exec(color))
			cache = [p(cache[1], 16), p(cache[2], 16), p(cache[3], 16)];

		// Checks for 3 digit hex and converts string to integer
		else if (cache = /#([\da-fA-F])([\da-fA-F])([\da-fA-F])/.exec(color))
			cache = [p(cache[1], 16) * 17, p(cache[2], 16) * 17, p(cache[3], 16) * 17];

		// Checks for rgba and converts string to
		// integer/float using unary + operator to save bytes
		else if (cache = /rgba\(([\d]+),([\d]+),([\d]+),([\d]+|[\d]*.[\d]+)\)/.exec(color))
			cache = [+cache[1], +cache[2], +cache[3], +cache[4]];

		// Checks for rgb and converts string to
		// integer/float using unary + operator to save bytes
		else if (cache = /rgb\(([\d]+),([\d]+),([\d]+)\)/.exec(color))
			cache = [+cache[1], +cache[2], +cache[3]];

		// Otherwise throw an exception to make debugging easier
		else throw color + ' is not supported by parseColor';

		// Performs RGBA conversion by default
		isNaN(cache[3]) && (cache[3] = 1);

		// Adds or removes 4th value based on rgba support
		// Support is flipped twice to prevent erros if
		// it's not defined
		return cache.slice(0, 3 + !!$.support.rgba);
	};

	var componentToHex = function(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	};

	var rgbToHex = function(r, g, b) {
		return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
	};

	var resetUI = function() {
		var els = $(cleInputsAndSelects);

		// Loading colors, selects and other inputs
		$.each(els, function(k, v) {
			var el = $(v);
			var setValue = '';

			if (el.hasClass(cle_prefix + 'color')) {
				var cssKey = el.data('css-key');

				if (activeElement !== null && typeof cssKey === "string" && cssKey !== "") {
					var value = $(activeElement).css(cssKey);
					var color = "";

					try {
						color = rgbToHex.apply(null, parseColor(value));
					} catch (e) {
						log(e);
						color = value;
					}

					if (typeof color === "string" && color !== "") {
						setValue = color;
					}
				}
			} else {
				var cssKey = el.data('css-key');

				if (activeElement !== null && typeof cssKey === "string" && cssKey !== "") {
					if (typeof fastElems[activeElementSelector] !== "undefined") {
						var data = fastElems[activeElementSelector];

						if (typeof data.changes[cssKey] !== "undefined") {
							setValue = data.changes[cssKey];
						}
					}
				}
			}

			// Set the value of element
			el.val(setValue);
		});

		// Loading multiple buttons
		var multiple_btns = $('.' + cle_prefix + 'multiple-btns');

		$.each(multiple_btns, function(k, v) {
			var btns_container = $(v);
			var cssKey = btns_container.data('css-key');
			var btns = btns_container.find('.' + cle_prefix + 'multiple-btn');
			var vals = [];

			if (activeElement !== null && typeof cssKey === "string" && cssKey !== "") {
				if (typeof fastElems[activeElementSelector] !== "undefined") {
					var data = fastElems[activeElementSelector];

					if (typeof data.changes[cssKey] !== "undefined") {
						vals = data.changes[cssKey].split(' ');
					}
				}
			}

			$.each(btns, function(btn_key, btn) {
				var btn_el = $(btn);
				var val = btn_el.val();

				if ($.inArray(val, vals) > -1) {
					btn_el.addClass(cle_prefix + 'multiple-btn-selected');
				} else {
					btn_el.removeClass(cle_prefix + 'multiple-btn-selected');
				}
			});
		});
		
		updateHighlightBoxes();
	};

	setupUI();
	
	var selecting = false;
	
	var updateHoverBox = function () {
		updatePageElement(hoveringBoxElement, hoveringElement, selecting);
	};
	
	var updateActiveBox = function () {
		updatePageElement(activeBoxElement, activeElement);
	};
	
	var updateHighlightBoxes = function () {
		updateHoverBox();
		updateActiveBox();
	};
	
	var updatingInterval = setInterval(updateHighlightBoxes, 50);
	
	var unselectableSelectors = [cleWindowSelector, cleHoveringBoxSelector];
	
	var isSelectable = function (target) {
		for (i = 0; i < unselectableSelectors.length; i++) {
			var el = $(target);
			var selector = unselectableSelectors[i];
			
			if (el.is(selector) || el.parents(selector).length > 0)
			{
				return false;
			}
		}

		return true;
	};
	
	var cancelEvent = function (event) {
		event.preventDefault();
		event.stopPropagation();
		event.cancelBubble = true;
		return false;
	};
	
	var updatePageElement = function (boxElement, element, forceShow) {
		if (element === null)
		{
			boxElement.hide();
			return;
		}
		else if (typeof forceShow !== "undefined")
		{
			if (forceShow)
			{
				boxElement.show();
			}
			else
			{
				boxElement.hide();
			}
		}
		else if (!element.is(':visible'))
		{
			boxElement.hide();
		}
		else
		{
			boxElement.show();
		}
		
		var offset = element.offset();
		
		boxElement.css({
			top: offset.top,
			left: offset.left,
			width: element.outerWidth(),
			height: element.outerHeight(),
		});
	};
	
	var onSelectElement = function (event) {
		var target = event.target;
		
		if (!isSelectable(target)) return;
		
		activeElement = $(target);
		activeElementSelector = activeElement.getSelector().join(' ');
		updateActiveBox();
		$(cleSelectedSelector).text(activeElementSelector);
		
		resetUI();
		
		return cancelEvent(event);
	};
	
	var onHoverElement = function (event) {
		var target = event.target;
		
		if (!isSelectable(target)) return;
		
		$(cleUndermouseSelector).show();
		
		hoveringElement = $(target);
		updateHoverBox();
		$(cleUndermouseSelector).text(hoveringElement.getSelector().join(' '));
		
		return cancelEvent(event);
	}
	
	var startSelecting = function () {
		selecting = true;
		
		document.body.addEventListener('click', onSelectElement, {capture: true});
		document.body.addEventListener('mousemove', onHoverElement, {capture: true});
	};
	
	var stopSelecting = function () {
		selecting = false;
		$(cleUndermouseSelector).hide();
		
		document.body.removeEventListener('click', onSelectElement, {capture: true});
		document.body.removeEventListener('mousemove', onHoverElement, {capture: true});
	};
};