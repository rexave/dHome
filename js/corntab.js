// copy and paste junk
// ZeroClipboard.setMoviePath( '/img/ZeroClipboard.swf' );
// //create client
// var clip = new ZeroClipboard.Client();
// //event
// clip.addEventListener('mousedown',function() {
// 	// clip.setText(document.getElementById('output-crontab').html());
// 	clip.setText("hey dude, you got some shit");
// });
// clip.addEventListener('complete',function(client,text) {
// 	console.log('copied: ' + text);
// });
// //glue it to the button
// clip.glue('copy');

var everys = new Array('min-every', 'hour-every');
var crontab_obj = new Object();
var output_vals = new Array();
var default_vals = new Array();
var current_crontab = '';
default_vals['min-out'] = '*';
default_vals['hour-out'] = '*';
default_vals['dom-out'] = '*';
default_vals['mon-out'] = '*';
default_vals['dow-out'] = '*';
default_vals['cmd-out'] = '/usr/sbin/update-motd';
default_vals['min-every'] = '5';
default_vals['hour-every'] = '1';

var slider_defaults = new Array();
slider_defaults['min-out'] = {
	range: "min",
	min: 1,
	max: 59,
	value: default_vals['min-every'],
	slide: function(event, ui) {
		id = $(this).attr("id").split('_')[0];
		set_every(ui, id);
	}
};
slider_defaults['hour-out'] = {
	range: "min",
	min: 1,
	max: 23,
	value: default_vals['hour-every'],
	slide: function(event, ui) {
		id = $(this).attr("id").split('_')[0];
		set_every(ui, id);
	}
};

reset_all();

$(document).ready(function() {
	// FOR TESTING
	// $("button#findchecked").button().click(function() {
	// 	find_checked();
	// 	$(this).removeClass('ui-state-focus');
	// });
	
	// email crontab dialog form
	$("#dialog-form").dialog({
		autoOpen: false,
		width: 580,
		modal: true,
		buttons: {
			'Send email': function() {
				$(".validation-message").html('<img src="/img/spinner.gif"> Sending...');
				var isValid = false;
				var action = $('form#UserCrontabEmailForm').attr('action');
				// post the data
				$.post(action, $('form#UserCrontabEmailForm').serialize(), function(data, status) {
					// Google Analytics tracking
					gaTrack(action);
					// testing 
					// console.log(data);
					if (data.error) {
						$(".validation-message").html(parseAjaxErrors(data.error));
					} else {
						$(".validation-message").html('<span style="color: #65c95e; font-weight: bold;">Your email was sent!</span>');
						setTimeout(function() {
							$("#dialog-form").dialog('close');
						}, 2000)
						
					}
				}, "json");
				
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
			$(".validation-message").html("");
			$("input#ContactEmail").val("");
		}
	});
	
	// copy button
	// $("button#copy").button().click(function() {
	// 	console.log("hey copy");
	// 	$(this).removeClass('ui-state-focus');
	// });
	// email button
	$("button#email").button().click(function() {
		$(".validation-message").html("");
		$("input#UserCrontab").val(current_crontab);
		$(this).removeClass('ui-state-focus');
		$('#dialog-form').dialog('open');
	});
	// reset button
	$("button#reset").button().click(function() {
		reset_all();
		$(this).removeClass('ui-state-focus');
	});
	
	// MINUTES JUNK
	// initialize the buttons
	$("#min-format").buttonset();
	// initialize the slider
	$("#min-out_slider").slider(slider_defaults['min-out']);
	$(".min-out_val").val($("#min-out_slider").slider("value"));
	// initialize the tabs
	$("#min-tabs").tabs({
		select: function(event, ui) {
			var id = $(ui.tab).attr("rel") + '-out';
			// we will use target to see if there is an index in default_vals so we can set the value when the tab is selected
			var target = $(ui.tab).attr("id").split("_")[0];
			// console.log(target);
			// reset the field for the tab they clicked on
			reset(id, target);
			fix_tabs();
		}
	});
	
	// HOURS JUNK
	// initialize the buttons
	$("#hour-format").buttonset();
	// initialize slider
	$("#hour-out_slider").slider(slider_defaults['hour-out']);
	$(".hour-out_val").val($("#hour-out_slider").slider("value"));
	// initialize the tabs
	$("#hour-tabs").tabs({
		select: function(event, ui) {
			var id = $(ui.tab).attr("rel") + '-out';
			// we will use target to see if there is an index in default_vals so we can set the value when the tab is selected
			var target = $(ui.tab).attr("id").split("_")[0];
			// console.log(target);
			// reset the field for the tab they clicked on
			reset(id, target);
			fix_tabs();
		}
	});
	
	// DOM JUNK
	$("#dom-format").buttonset();
	// initialize the tabs
	$("#dom-tabs").tabs({
		select: function(event, ui) {
			var id = $(ui.tab).attr("rel") + '-out';
			// we will use target to see if there is an index in default_vals so we can set the value when the tab is selected
			var target = $(ui.tab).attr("id").split("_")[0];
			// console.log(target);
			// reset the field for the tab they clicked on
			reset(id, target);
			fix_tabs();
		}
	});
	
	// MON JUNK
	$("#mon-format").buttonset();
	// initialize the tabs
	$("#mon-tabs").tabs({
		select: function(event, ui) {
			var id = $(ui.tab).attr("rel") + '-out';
			// we will use target to see if there is an index in default_vals so we can set the value when the tab is selected
			var target = $(ui.tab).attr("id").split("_")[0];
			// console.log(target);
			// reset the field for the tab they clicked on
			reset(id, target);
			fix_tabs();
		}
	});
	
	// DOW JUNK
	$("#dow-format").buttonset();
	// initialize the tabs
	$("#dow-tabs").tabs({
		select: function(event, ui) {
			var id = $(ui.tab).attr("rel") + '-out';
			// we will use target to see if there is an index in default_vals so we can set the value when the tab is selected
			var target = $(ui.tab).attr("id").split("_")[0];
			// console.log(target);
			// reset the field for the tab they clicked on
			reset(id, target);
			fix_tabs();
		}
	});
	
	// COMMAND JUNK
	$("#cmd-out_0").val(default_vals['cmd-out']);
	$("#cmd-out_0").keyup(function(){
		var id = $(this).attr("id").split("_")[0];
		var val = $(this).val();
		set_text(id, val);
	});
	
	
	// "checkboxes"
	$(":checkbox").click(function() {
		elId = $(this).attr("id");
		parts = elId.split('_');
		id = parts[0];
		v = parts[1];
		index = $(this).attr("name");
		if ($(this).is(":checked")) {
			set_each(id, v, index);
		} else {
			rm_each(id, v, index);
		}
	});
	// initialize the accordion
	$("#accordion").accordion({
		autoHeight: false
	});
	
});

// functions
function init_data() {
	output_vals['min-out'] =  default_vals['min-out'];
	output_vals['hour-out'] = default_vals['hour-out'];
	output_vals['dom-out'] =  default_vals['dom-out'];
	output_vals['mon-out'] =  default_vals['mon-out'];
	output_vals['dow-out'] =  default_vals['dow-out'];
	output_vals['cmd-out'] =  default_vals['cmd-out'];
	
	set_crontab(
		output_vals['min-out'], 
		output_vals['hour-out'], 
		output_vals['dom-out'], 
		output_vals['mon-out'], 
		output_vals['dow-out'], 
		output_vals['cmd-out']
	);
}
function get_val(id) {
	return $("#"+id).text();
}
function set_val(id, val) {
	$("#"+id).text(val);
}
function set_text(id, val) {
	output_vals[id] = val;
	crontab(output_vals);
}
function set_each(id, val) {
	var comma = ',';
	initialVal = ((output_vals[id] !== undefined) && (output_vals[id] !== '*')) ? output_vals[id] : '';
	if (initialVal=='') {comma = ''};
	output_vals[id] = initialVal + comma + val;
	out = (val=="") ? "*" : output_vals[id];
	crontab(output_vals);
}
function rm_each(id, val) {
	string = output_vals[id];
	var pattern = new RegExp(val+"[,]?");
	test = string.replace(pattern, '');
	test = test.replace(/,$/, '');
	output_vals[id] = test;
	// console.log(test);
	// console.log(output_vals);
	crontab(output_vals);
}
function set_every(ui, id) {
	// console.log(ui.value);
	// id should be min-out, hour-out, etc
	$("."+id+"_val").val(ui.value);
	output_vals[id] = "*/" + ui.value;
	crontab(output_vals);
}
function set_crontab(min, hour, dom, mon, dow, cmd) {
	output_vals['min-out'] = min;
	output_vals['hour-out'] = hour;
	output_vals['dom-out'] = dom;
	output_vals['mon-out'] = mon;
	output_vals['dow-out'] = dow;
	output_vals['cmd-out'] = cmd;
	
	crontab(output_vals);
}
function reset_all() {
	// clear all checkboxes
	$(":checked").each(function() {
		set_each("min-out", "");
		id = $(this).attr("id");
		$(this).attr("checked", false);
		$("label."+id).removeClass("ui-state-active");
		$("label."+id).attr("aria-pressed", "false");
		// console.log(id + ": " + $(this).val());
	});
	init_data();
}
function reset_checkboxes(index) {
	// resets the checkboxes only for the specified index, ie. min or hour
	$("#"+index+"-format input:checked").each(function(){
		id = $(this).attr("id");
		$(this).attr("checked", false);
		$("label."+id).removeClass("ui-state-active");
		$("label."+id).attr("aria-pressed", "false");
	});
}
function reset(field, def) {
	var newVal = default_vals[field];
	var newValRaw = default_vals[field];
	var field_base = field.split('-')[0];
	if (default_vals[def]) {newValRaw = default_vals[def]};
	// see if this field is an "every" so we can prepend "*/"
	if (jQuery.inArray(def, everys)>-1) {newVal = "*/" + newValRaw};
	// field and def will be min-out, hour-out, mon-out, etc
	output_vals[field] =  newVal;
	// console.log(slider_defaults);
	// reset sliders
	$("#"+field+"_slider").slider(slider_defaults[field]);
	$("."+field+"_val").val($("#"+field+"_slider").slider("value"));
	// reset the checkboxes
	reset_checkboxes(field_base);
	// console.log($("#"+field+"_slider").slider());
	crontab(output_vals);
}
function find_checked() {
	$(":checked").each(function() {
		id = $(this).attr("id");
		console.log(id + ": " + $(this).val());
	});
}
function fix_tabs() {
	$("ul.ui-tabs-nav li").each(function(){
		if ($(this).hasClass("ui-state-active")===false) {$(this).removeClass("ui-state-focus")};
	});
}
function crontab (v) {
	// min, hour, dom, mon, dow, cmd
	$("span#min-out").text(v['min-out']);
	$("span#hour-out").text(v['hour-out']);
	$("span#dom-out").text(v['dom-out']);
	$("span#mon-out").text(v['mon-out']);
	$("span#dow-out").text(v['dow-out']);
	$("span#cmd-out").text(v['cmd-out']);
	// set the crontab text
	current_crontab = v['min-out'] + ' ' + v['hour-out'] + ' ' + v['dom-out'] + ' ' + v['mon-out'] + ' ' + v['dow-out'] + ' ' + v['cmd-out'];
}

// parse AJAX response errors
function parseAjaxErrors(json) {
	n = 0;
	errRet = '';
	for (i in json) {
		if (typeof json[i] == "object") {
			parseAjaxErrors(json[i]);
		} else {
			errType = i;
			errRet = errRet + json[errType];
			if (n>0) {errRet = errRet + "<br />"};
			n=n+1;
		}
	}
	return errRet;
}

