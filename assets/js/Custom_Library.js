$(function() {
	$("li[contenteditable]").keypress(function (evt) {
	  var keycode = evt.charCode || evt.keyCode;
	  if (keycode  == 13) { //Enter key's keycode
		return false;
	  }
	});
	
	//$('.modul').draggable();
	
	$('.tanggal').datepicker({dateFormat: "yy-mm-dd"});
	
	$('.tanggal_jam').datetimepicker({dateFormat: "yy-mm-dd"});
	
	$('.float_only').keypress(function(eve) {
		if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
			if (eve.which == 8) {
				//do nothing
			}
			else {
				eve.preventDefault();
			}
			
		}
	 
		// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
		$('.float_only').keyup(function(eve) {
			if($(this).val().indexOf('.') == 0) {
				$(this).val($(this).val().substring(1));
			}
		});
		
	});
	
	$('.integer_only').keypress(function(eve) {
		if (eve.which < 48 || eve.which > 57) {
			if (eve.which == 8) {
				//do nothing
			}
			else {
				eve.preventDefault();
			}
		}
		
		// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
		$('.integer_only').keyup(function(eve) {
			if($(this).val().indexOf('.') == 0) {
				$(this).val($(this).val().substring(1));
			}
		});
		
	});
	
	$('.char_only').keypress(function(eve) {
		if ((eve.which < 65 || eve.which > 90) && (eve.which < 97 || eve.which > 122) && (eve.which < 32 || eve.which > 32) ) {
			if (eve.which == 8) {
				//do nothing
			}
			else {
				eve.preventDefault();
			}
			
		}
	 
		// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
		$('.char_only').keyup(function(eve) {
			if($(this).val().indexOf('.') == 0) {
				$(this).val($(this).val().substring(1));
			}
		});
		
	});
	
	$('.char_and_number_only').keypress(function(eve) {
		if ((eve.which < 65 || eve.which > 90) && (eve.which < 97 || eve.which > 122) && (eve.which < 32 || eve.which > 32) && (eve.which < 48 || eve.which > 57) ) {
			if (eve.which == 8) {
				//do nothing
			}
			else {
				eve.preventDefault();
			}
		}
	 
		// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
		$('.char_only').keyup(function(eve) {
			if($(this).val().indexOf('.') == 0) {
				$(this).val($(this).val().substring(1));
			}
		});
		
	});
	
	/*$( '.telephone' ).mask('(000) 000-0000-0000');
	$( '.money' ).mask('0.000.000.000', {reverse: true});
	$( '.money2' ).mask('000.000.000.000.000', {reverse: true});
	$( '.readonly' ).attr('readonly', true);*/
	
});

//Report Dynamic
var list_mask = [];

function mask(id, format) {
	if (list_mask.indexOf(id) == -1) {
		AddMasks(id, format);
		list_mask.push(id);
	}
}

function datepick(id) {
	$( "#" + id ).datepicker({
		dateFormat: "yy-mm-dd",
	});
}

$(function () {
	$('#year_start').datetimepicker({
		format: 'Y-MM-DD'
	});
	$('#year_end').datetimepicker({
		format: 'Y-MM-DD'
	});
})

function pop_up(id, alert, type) {
    $(document).ready(function() {
        $("#"+id).animate({}, 300);
        $('<div class="'+type+'">' + '<button type="button" class="close" data-dismiss="alert">' + '&times;</button>'+alert+'</div>').hide().appendTo('#'+id).fadeIn(1000);
        $(".alert").delay(4000).fadeOut("normal",function(){ $(this).remove(); });
        $("#"+id).delay(4000).animate({}, 300);
    });
}

//===============end report_dynamic

function FilterTable(id_filter, id_table, index_column) {
	var input, filter, table, tr, td, i;
	input = document.getElementById(id_filter);
	filter = input.value.toUpperCase();

	table = document.getElementById(id_table);
	tr = table.getElementsByTagName("tr");
	
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[index_column];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			}
			else {
				tr[i].style.display = "none";
			}
		}       
	}
} 

function FilterTableAll(id_filter, id_table) {
  var input, filter, table, tr, td, i;
  input = document.getElementById(id_filter);
  filter = input.value.toUpperCase();
  table = document.getElementById(id_table);
  var rows = table.getElementsByTagName("tr");
  for (i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    var j;
    var rowContainsFilter = false;
    for (j = 0; j < cells.length; j++) {
      if (cells[j]) {
        if (cells[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
          rowContainsFilter = true;
          continue;
        }
      }
    }
	
    if (i>0){
		if (! rowContainsFilter) {
		  rows[i].style.display = "none";
		} 
		else {
		  rows[i].style.display = "";
		}
	}
  }
}

function sortTable(id) {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById(id);
  switching = true;
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[0];
      y = rows[i + 1].getElementsByTagName("TD")[0];
      // Check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        // If so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
	
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}  

var last_modul = [];

function show(id_modul) {
	//simpan id_modul yang sebelumnya aktif
	if (last_modul[last_modul.length - 1] != id_modul) {
		last_modul.push(id_modul);
	}
	
	//hide all modul
	var elements = document.querySelectorAll('[id^="modul_"]');
	for(var i=0; i< elements.length; i++) {
		elements[i].style.display = 'none';
	}
	
	document.getElementById('blocker').style.display = 'block';
	document.getElementById(id_modul).style.display = 'block';
}

function hide(id_modul) {
	last_modul.pop();
	
	document.getElementById('blocker').style.display = 'none';
	document.getElementById(id_modul).style.display = 'none';
	
	if (last_modul.length > 0) {
		show(last_modul[last_modul.length - 1]);
	}
}

function GI(id) { //Get innerHTML
	return document.getElementById(id).innerHTML;
}

function WI(id, value) { //Write innerHTML
	document.getElementById(id).innerHTML = value;
}

function AI(id, value) { //Append innerHTML
	document.getElementById(id).innerHTML = document.getElementById(id).innerHTML + value;
}

function GV(id) { //Get Value
	if($('#'+id).length > 0) {
		return document.getElementById(id).value;
	}
	else {
		return '';
	}
}

function WV(id, value) { //Write Value
	document.getElementById(id).value = value;
}

function AV(id, value) { //Append Value
	document.getElementById(id).value = document.getElementById(id).value + value;
}

function GN(id) { //Get Attribute Name
	return $('#'+id).attr('name');
}

function WN(id, value) { //write Attribute Name
	document.getElementById(id).setAttribute("name", value);
}

function WC(id, value) { //Write Input Checkbox
	if (GC(id) == value) {
		value = !value;
	}
	
	document.getElementById(id).checked = value;
}

function GC(id) { //Get Input Checkbox 
	return document.getElementById(id).checked;
}

function GV_SCB(id_pattern, mode) { //Get Value from Selected Check Box With ID like id_pattern 
	var temp="";
	
	switch (mode) {
		case 0:
			$('input:checkbox[id^="'+id_pattern+'"]:checked').each(function()
			{
				if (temp == "") {
					temp = ($(this).attr("id")).replace(id_pattern, '');
				}
				else {
					temp = temp + ',' + ($(this).attr("id")).replace(id_pattern, '');
				}
			});
			
			break;
			
		case 1:
			$('input:checkbox[id^="'+id_pattern+'"]:checked').each(function() {
				temp = GV($(this).attr("id"));
			});
			
			break;
		
		default:
			
			
	}
	
	return temp;
}

function authorized() {
	if (localStorage['group'] == 'administrator') {
		return true;
	}
	else {
		return false;
	}
}

function reformat_date(RawDate, DateFormat) {
	switch (DateFormat) {
		case 1:
			var month = new Array();
			month[0] = "Januari";
			month[1] = "Februari";
			month[2] = "Maret";
			month[3] = "April";
			month[4] = "Mei";
			month[5] = "Juni";
			month[6] = "Juli";
			month[7] = "Augustus";
			month[8] = "September";
			month[9] = "Oktober";
			month[10] = "November";
			month[11] = "Desember";

			var d = new Date(RawDate);
			var tanggal = d.getDate();
			
			if (tanggal <= 9) {
				tanggal= "0" + tanggal;
			}
			
			var bulan = month[d.getMonth()];
			var tahun = d.getFullYear();
			var result = tanggal + " " + bulan + " " + tahun;
			
			break;
		
		default:
			var result = "format type belum ada";
	}
	
	return result;
}

function UrlParam(name) {
    return (location.search.split(name + '=')[1] || '').split('&')[0];
}

function populate(pattern) {
	var temp_arr = [];
				
	temp_id = "";
	temp_value = "";
	
	$( pattern ).each(function( index ) {
		temp_id += $( pattern ).get(index).id + '|';
		temp_value += $( pattern ).get(index).value + '|';
	});
	
	temp_arr.push(temp_id);
	temp_arr.push(temp_value);
	
	return temp_arr;
}

function GetURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

function fnExcelReport(id)
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById(id); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 
	
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) { // If Internet Explorer
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else { //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
	}

    return (sa);
}

/*
function showHint() 
{
	var xhttp;
	
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() 
	{
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById("txtHint").innerHTML = this.responseText;
		}
	};
	
	xhttp.open("POST", "upload.php", true);
	xhttp.send();
}
*/

function formatDate(date) {
	var year = date.toLocaleString("default", { year: "numeric" });
	var month = date.toLocaleString("default", { month: "2-digit" });
	var day = date.toLocaleString("default", { day: "2-digit" });
	var formattedDate = year + "-" + month + "-" + day;
	return formattedDate;
}

function ValidateDataList(IdVal, IdObj) {
	var val = $("#"+IdVal+"").val();
	var obj = $("#"+IdObj+"").find("option[value='" + val + "']");
	if(obj != null && obj.length > 0) {
		return true;
	} else {
		return false;
	}
}

function PopulateModal(id) {

	//console.log(id);
	var flag_return = true;
	var names = '';
	var values = '';
	var temp = id.split('-')
	var prefix = temp[0] + '-';
	
	$("[id^='"+prefix+"']" ).each(function() {
	   
	   if ( $(this).prop('required') == true && GV(this.id) == '' ) { flag_return = false; } //required fields empty validation
	   if ( $(this).prop('minLength') > 0 && $(this).val().length < $(this).prop('minLength') ) { flag_return = false; } //required fields minLength
	   
	   if (this.id != id){
		   names += (this.id).replace(prefix,'') + "|";
		   values += GV(this.id) + "|";
	   }
	});
	
	if( $('#uid').length ) {
		names += ('uid').replace(prefix,'') + "|";
		values += GV('uid') + "|";
	}
	
	if (flag_return == true) { return [names, values]; }
}

function connection(mode, target, param, param_value) {
	if($('#loading').length > 0) { document.getElementById('loading').style.display = 'block'; }
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if(target != "") {
				var x = this.responseText;
				if (target == '*spread*') {
					var array_x = x.split(';');
					
					for (var i=0; i<array_x.length-1; i++) {
						var array_sub_x = array_x[i].split('<<');
						
						target = array_sub_x[0];
						value = array_sub_x[1];
						
						if (target.includes("(name)") == true) {
							WN(target.replace("(name)", ""), value);
						}
						else if (target.includes("(innerhtml)") == true) {
							document.getElementById( (target.replace("(innerhtml)", "")) ).innerHTML = value;
						}
						else if (target.includes("(value)") == true) {
							document.getElementById( (target.replace("(value)", "")) ).value = value;
						}
						else if (target.includes("(backgroundimage)") == true) {
							document.getElementById( (target.replace("(backgroundimage)", "")) ).style.backgroundImage = value;
						}
						else if (target.includes("(className)") == true) {
							document.getElementById( (target.replace("(className)", "")) ).className = value;
						}
						else if (target.includes("(alert)") == true) {
							alert(value);
						}
						else if (target.includes("(popup)") == true) {
							var id = (target.replace("(popup)", ""));
							pop_up('pop_body', value, 'alert alert-'+id);
						}
						else {
							if ($('#'+target).is("input") == true || $('#'+target).is("select") == true || $('#'+target).is("textarea") == true) {
								if ($('#'+target).is(":checkbox") == true) {
									document.getElementById(target).checked = Boolean(value);
								}
								else {
									document.getElementById(target).value = value;
								}
							}
							else {
								document.getElementById(target).innerHTML = value;
							}
						}
					}
				}
				else {
					if ($('#'+target).is("input") == true) {
						document.getElementById(target).value = x;
					}
					else {
						document.getElementById(target).innerHTML = x;
					}
				}
			}
			else {
				if ( (this.responseText).includes("_success") == true || (this.responseText).includes(" Success") == true ) {
					//do nothing
					pop_up('pop_body', this.responseText, 'alert alert-success');
				}
				else {
					pop_up('pop_body', this.responseText, 'alert alert-danger');
				}
			}
			//console.log(this.responseText);
			if($('#loading').length > 0){ document.getElementById('loading').style.display = 'none'; }
			next_action(mode, this.responseText);
		}
	};
	
	//replace symbol yang tidak bisa passing
	if (param_value) { 
		param_value = param_value.replace('+', '(symbol:plus)');
	}
	

	if(document.getElementById('CL_parameter').getAttribute("php") == 'input_inventory' )
    {
        xmlhttp.open("POST","../../page_php/"+document.getElementById('CL_parameter').getAttribute("php") + ".php?mode=" + mode + "&param=" + param + "&param_value=" + param_value, true);
    }
    else
    {
        xmlhttp.open("POST","../../application/page_php/"+document.getElementById('CL_parameter').getAttribute("php") + ".php?mode=" + mode + "&param=" + param + "&param_value=" + param_value, true);
    }
    
	xmlhttp.send();
}