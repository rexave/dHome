
var output_vals = new Array();
var current_crontab = '';

init_data();

// functions
function init_data() {
	output_vals['min-out'] =  '*';
	output_vals['hour-out'] = '*';
	output_vals['dom-out'] =  '*';
	output_vals['mon-out'] =  '*';
	output_vals['dow-out'] =  '*';
}

function set_crontab(min, hour, dom, mon, dow) {
	output_vals['min-out'] = min;
	output_vals['hour-out'] = hour;
	output_vals['dom-out'] = dom;
	output_vals['mon-out'] = mon;
	output_vals['dow-out'] = dow;
	
	crontab(output_vals);
}

function reset_checkboxes(index) {
	// resets the checkboxes only for the specified index, ie. min or hour
	$("#div-"+index+" input:checked").each(function(){
		id = $(this).attr("id");
		$(this).attr("checked", false).checkboxradio("refresh");
	});
}

function crontab (v) {
	// min, hour, dom, mon, dow, cmd
	$("span#min-out").text(v['min-out']);
	$("span#hour-out").text(v['hour-out']);
	$("span#dom-out").text(v['dom-out']);
	$("span#mon-out").text(v['mon-out']);
	$("span#dow-out").text(v['dow-out']);
	// set the crontab text
	current_crontab = v['min-out'] + ' ' + v['hour-out'] + ' ' + v['dom-out'] + ' ' + v['mon-out'] + ' ' + v['dow-out'];
	
	$("#valeur_cron").val(v['min-out'] + ' ' + v['hour-out'] + ' ' + v['dom-out'] + ' ' + v['mon-out'] + ' ' + v['dow-out']);
}

function action_slider(obj){
	id = obj.id.split('_')[0];
	output_vals[id] = "*/" + obj.value;
	crontab(output_vals);
}

function display_cron(id){
	unit = id.split('-')[0];
	$(".div-"+unit).hide();
	$("#div-"+id).show();
	output_vals[unit+"-out"] = "*";
	crontab(output_vals);
	reset_checkboxes(id);
}

//type: min,hour,...
function update_each(type){
	var selection_each = "";
	var comma = '';
	
	$("#div-"+type+"-selected input:checked").each(function(){
		id = $(this).attr("id");
		selection_each += comma + id.split('_')[1];
		comma = ',';
	});
	
	output_vals[type+"-out"] = (selection_each=="") ? "*" : selection_each;

	crontab(output_vals);

}
