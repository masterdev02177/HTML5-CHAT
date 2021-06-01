jQuery(document).ready(function () {
	var zIndexWindow = 25500;
	var zIndexActive = 25450;
	var zIndexHovering = 25400;
	
	var cle_prefix = 'cleV101b--';
	
	var cle_css = '.' + cle_prefix + 'window{\
  font: 12px Verdana;\
  color: #000;\
  position: fixed;\
  top: 16px;\
  left: 16px;\
  padding: 2px;\
  border: 1px solid #ccc;\
  border-radius: 3px;\
  background: #fff;\
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);\
  box-sizing: border-box;\
  z-index: ' + zIndexWindow +';\
  line-height: 1.2;\
  width: 448px;\
}\
.' + cle_prefix + 'header{\
  padding: 6px;\
  font: 12px Verdana;\
  font-weight: 600;\
  color: #000;\
  background: #ddd;\
  line-height: 1.2;\
}\
.' + cle_prefix + 'body{\
  padding: 2px;\
}\
.' + cle_prefix + 'row{\
  margin: 6px;\
}\
.' + cle_prefix + 'set-page{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 6px;\
  border: 1px solid #ccc;\
  border-radius: 2px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 4px 0;\
  width: 80%;\
  line-height: 1.2;\
  -moz-appearance: menulist;\
  -webkit-appearance: menulist;\
  -o-appearance: menulist;\
  -ms-appearance: menulist;\
  appearance: menulist;\
  outline: 0;\
  text-decoration: none;\
  height: 32px;\
}\
.' + cle_prefix + 'set-page:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'group{\
  margin: 2px 0 4px;\
}\
.' + cle_prefix + 'group:not(:first-child){\
  border-top: none;\
}\
.' + cle_prefix + 'group:not(.' + cle_prefix + 'group-collapsed) .' + cle_prefix + 'group-name, .' + cle_prefix + 'group-collapsed .' + cle_prefix + 'group-name:hover{\
  background: #555;\
}\
.' + cle_prefix + 'group-contents{\
  background: #fff;\
  padding: 4px 6px;\
  overflow: hidden;\
  border: 2px solid #555;\
  border-top: 0;\
  border-radius: 3px;\
  border-top-left-radius: 0;\
  border-top-right-radius: 0;\
  display: block;\
}\
.' + cle_prefix + 'group-collapsed .' + cle_prefix + 'group-contents{\
  display: none;\
}\
.' + cle_prefix + 'group-name{\
  background: #444;\
  border-radius: 3px;\
  color: #eee;\
  padding: 6px;\
  font-weight: 600;\
  cursor: pointer;\
}\
.' + cle_prefix + 'group:not(.' + cle_prefix + 'group-collapsed) .' + cle_prefix + 'group-name{\
  border-bottom-left-radius: 0;\
  border-bottom-right-radius: 0;\
}\
.' + cle_prefix + 'label{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 4px 0;\
  height: auto;\
  width: 20%;\
}\
.' + cle_prefix + 'select{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 6px;\
  border: 1px solid #ccc;\
  border-radius: 2px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 2px 0;\
  width: 80%;\
  line-height: 1.2;\
  -moz-appearance: menulist;\
  -webkit-appearance: menulist;\
  -o-appearance: menulist;\
  -ms-appearance: menulist;\
  appearance: menulist;\
  outline: 0;\
  text-decoration: none;\
  height: 32px;\
}\
.' + cle_prefix + 'select:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'text[type="text"]{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 6px;\
  border: 1px solid #ccc;\
  border-radius: 2px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 2px 0;\
  width: 80%;\
  line-height: 1.2;\
  outline: 0;\
  text-decoration: none;\
  height: 32px;\
}\
.' + cle_prefix + 'text[type="text"]:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'textarea{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 6px;\
  border: 1px solid #ccc;\
  border-radius: 2px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 2px 0;\
  width: 100%;\
  min-width: 100%;\
  max-width: 100%;\
  line-height: 1.2;\
  outline: 0;\
  text-decoration: none;\
  height: 128px;\
  min-height: 128px;\
  max-height: 256px;\
}\
.' + cle_prefix + 'textarea:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'multiple-btns{\
  display: inline-block;\
  vertical-align: middle;\
  overflow: hidden;\
  margin: 0 -2px;\
  width: 80%;\
  box-sizing: border-box;\
}\
.' + cle_prefix + 'btn[type="button"], .' + cle_prefix + 'multiple-btn[type="button"]{\
  display: inline-block;\
  vertical-align: middle;\
  font: 12px Verdana;\
  color: #000;\
  padding: 6px;\
  border: 1px solid #ccc;\
  border-radius: 3px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 2px;\
  width: auto;\
  line-height: 1.2;\
  outline: 0;\
  text-decoration: none;\
  height: auto;\
  cursor: pointer;\
  letter-spacing: normal;\
  text-overflow: clip;\
  text-transform: none;\
  \
  /* transition */\
  -moz-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;\
  -webkit-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;\
  -o-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;\
  -ms-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;\
  transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;\
  transition-property: background-color, color;\
  transition-duration: 0.2s, 0.2s;\
  transition-timing-function: ease-in-out, ease-in-out;\
  transition-delay: initial, initial;\
}\
.' + cle_prefix + 'btn[type="button"]:focus, .' + cle_prefix + 'multiple-btn[type="button"]:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'btn[type="button"]:hover, .' + cle_prefix + 'multiple-btn[type="button"]:not(.' + cle_prefix + 'multiple-btn-selected):hover{\
  background: #fff;\
}\
.' + cle_prefix + 'multiple-btn-selected[type="button"]{\
  color: #fff !important;\
  background: #444;\
  border-color: #333;\
}\
.' + cle_prefix + 'multiple-btn-selected[type="button"]:hover{\
  color: #fff !important;\
  background: #555;\
}\
.' + cle_prefix + 'color[type="color"]{\
  display: inline-block;\
  vertical-align: middle;\
  padding: 1px;\
  border: 1px solid #ccc;\
  border-radius: 2px;\
  background: #eee;\
  box-sizing: border-box;\
  margin: 2px;\
  width: 48px;\
  line-height: normal;\
  outline: 0;\
  text-decoration: none;\
  height: 31px;\
}\
.' + cle_prefix + 'color[type="color"]:focus{\
  border-color: #e44;\
  box-shadow: 0 0 0 1px #e88;\
}\
.' + cle_prefix + 'cb[type="checkbox"]{\
  display: inline-block;\
  vertical-align: middle;\
  border: 0;\
  margin: 2px;\
}\
.' + cle_prefix + 'right-menu{\
  margin: 7px;\
  float:right;\
}\
.' + cle_prefix + 'cb-label{\
  display: inline-block;\
  vertical-align: middle;\
  border: 0;\
  margin: 2px;\
  font-weight: 400;\
}\
.' + cle_prefix + 'selected{\
  font: 11px Verdana;\
  color: #3a3;\
  margin: 4px;\
}\
.' + cle_prefix + 'undermouse{\
  font: 11px Verdana;\
  color: #666;\
  margin: 4px;\
}\
.' + cle_prefix + 'hovering-box{\
  display: block;\
  position: absolute;\
  top: 0;\
  left: 0;\
  box-sizing: border-box;\
  border: 2px dotted #44f;\
  z-index: ' + zIndexHovering +';\
  pointer-events: none;\
  opacity: 0.5;\
}\
.' + cle_prefix + 'active-box{\
  display: block;\
  position: absolute;\
  top: 0;\
  left: 0;\
  box-sizing: border-box;\
  border: 2px dotted #f44;\
  z-index: ' + zIndexActive + ';\
  pointer-events: none;\
  opacity: 1;\
}';
	
	var cle_html = '' +
			'<div class="' + cle_prefix + 'window">\
				<div class="' + cle_prefix + 'header">CSS Live Editor</div>\
				<div class="' + cle_prefix + 'body">\
					<div class="' + cle_prefix + 'groups"></div>\
					<button class="btn btn-default btn-get-css" id="saveBtn"><i class="fa fa-floppy-o"></i> Save CSS</button>\
					<button class="btn btn-default btn-get-css" id="resetBtn"><i class="fa fa-eraser"></i> Reset CSS</button>\
					<div class="' + cle_prefix + 'right-menu">\
						<input type="checkbox" class="' + cle_prefix + 'cb ' + cle_prefix + 'cb-selecting" name="cleCbSelecting" id="' + cle_prefix + 'cb-selecting" value="1">\
						<label for="' + cle_prefix + 'cb-selecting" class="' + cle_prefix + 'cb-label" style="color:red"><i class="fa fa-arrow-circle-o-up"></i>Select DOM</label>\
					</div>\
					<div style="clear:both"></div>\
					<div class="' + cle_prefix + 'selected">Check "Selecting DOM" and choose any item on page.</div>\
					<div class="' + cle_prefix + 'undermouse"></div>\
				</div>\
			</div>\
';
	
	var cle_items = {
		'border': [{
			name: "cleBorderWidth",
			label: "Width:",
			css: "border-width",
			type: "text",
		}, {
			name: "cleBorderStyle",
			label: "Style:",
			css: "border-style",
			type: "select",
			items: ["Default", "none", "hidden", "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset"]
		}, {
			name: "cleBorderColor",
			label: "Color:",
			css: "border-color",
			type: "color"
		}, {
			name: "cleBorderRadius",
			label: "Radius:",
			css: "border-radius",
			type: "text"
		}, ],
		'paddings-margins': [{
			name: "clePadding",
			label: "Padding:",
			css: "padding",
			type: "text"
		}, {
			name: "cleMargin",
			label: "Margin:",
			css: "margin",
			type: "text"
		}, ],
		'font': [{
			name: "cleFontFamily",
			label: "Font:",
			css: "font-family",
			type: "select",
			items: ["Default", "Arial", "Helvetica", "sans-serif", "Tahoma", "Times New Roman", "Verdana"]
		}, {
			name: "cleFontSize",
			label: "Size:",
			css: "font-size",
			type: "text"
		}, {
			name: "cleFontWeight",
			label: "Weight:",
			css: "font-weight",
			type: "select",
			items: ["Default", "normal", "bold", "bolder", "lighter"]
		}, {
			name: "cleFontVariant",
			label: "Variant:",
			css: "font-variant",
			type: "select",
			items: ["Default", "normal", "small-caps"]
		}, {
			name: "cleColor",
			label: "Color:",
			css: "color",
			type: "color"
		}, {
			name: "cleLineHeight",
			label: "Line Height:",
			css: "line-height",
			type: "text"
		}],
		'text': [{
			name: "cleTextAlign",
			label: "Align:",
			css: "text-align",
			type: "select",
			items: ["Default", "left", "center", "right"]
		}, {
			name: "cleTextDecoration",
			label: "Text Decoration:",
			css: "text-decoration",
			type: "multiple",
			items: ["none", "blink", "line-through", "overline", "underline"],
			resetIf: "none"
		}],
		'position': [{
			name: "clePosition",
			label: "Position:",
			css: "position",
			type: "select",
			items: ["Default", "absolute", "fixed", "relative", "static", "inherit"]
		}, {
			name: "clePositionLeft",
			label: "Left:",
			css: "left",
			type: "text"
		}, {
			name: "clePositionTop",
			label: "Top:",
			css: "top",
			type: "text"
		}, {
			name: "clePositionRight",
			label: "Right:",
			css: "right",
			type: "text"
		}, {
			name: "clePositionBottom",
			label: "Bottom:",
			css: "bottom",
			type: "text"
		}],
		'background': [{
			name: "cleBgColor",
			label: "Color:",
			css: "background-color",
			type: "color"
		}, {
			name: "cleBgImage",
			label: "Image:",
			css: "background-image",
			type: "text"
		}, {
			name: "cleBgRepeat",
			label: "Repeat:",
			css: "background-repeat",
			type: "select",
			items: ["Default", "no-repeat", "repeat", "repeat-x", "repeat-y"]
		}, {
			name: "cleBgSize",
			label: "Size:",
			css: "background-size",
			type: "select",
			items: ["Default", "cover", "contain"]
		}],
		'additional': [{
			name: "cleCursor",
			label: "Cursor:",
			css: "cursor",
			type: "text"
		}, {
			name: "cleBoxSizing",
			label: "Box Sizing:",
			css: "box-sizing",
			type: "select",
			items: ["Default", "content-box", "border-box", "initial"]
		}]
	};
	
	cleCssLiveEdit(cle_prefix, cle_css, cle_html, cle_items);
	
	$(document).off('focusin.modal');
})
