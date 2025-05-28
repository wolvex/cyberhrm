//var formatter = new Intl.NumberFormat('en-US', {
//    style: 'decimal',
//    minimumFractionDigits: 2,
//});
accounting.settings = {
	currency: {
		symbol : "Rp",   // default currency symbol is '$'
		format: {
            pos : "%s %v",   // for positive values, eg. "$ 1.00" (required)
            neg : "%s (%v)", // for negative values, eg. "$ (1.00)" [optional]
            zero: "%s  -- "  // for zero values, eg. "$  --" [optional]
        }, // controls output: %s = symbol, %v = value/number (can be object: see below)
		decimal : ".",  // decimal point separator
		thousand: ",",  // thousands separator
		precision : 2   // decimal places
	},
	number: {
		precision : 2,  // default precision on numbers is 0
		format: {
            pos : "%v",   // for positive values, eg. "$ 1.00" (required)
            neg : "(%v)", // for negative values, eg. "$ (1.00)" [optional]
            zero: "%v"  // for zero values, eg. "$  --" [optional]
        },
        thousand: ",",
		decimal : "."
	}
}

$.fn.search.settings.templates.compact = function(response, fields) {
    // do something with response
    var html = '';
    if(response[fields.results] !== undefined) {
        // each result
        $.each(response[fields.results], function(index, result) {
          if(result[fields.url]) {
            html  += '<a class="result" href="' + result[fields.url] + '">';
          } else {
            html  += '<a class="result">';
          }

          //if(result[fields.image] !== undefined) {
          //  html += '<div class="image"><img src="' + result[fields.image] + '"></div>';
          //}

          html += '<div class="content">';
          /**
          if(result[fields.price] !== undefined) {
            html += '<div class="price">' + result[fields.price] + '</div>';
          }
          if(result[fields.title] !== undefined) {
            html += '<div class="title">' + result[fields.title] + '</div>';
          }
          */
          if(result[fields.description] !== undefined) {
            html += '<div class="description" style="color:black">' + result[fields.description] + '</div>';
          }
          html += '</div></a>';
        });
    }
    return html;
};

function gridDecimal(cellvalue, options, rowObject) {
    var opts = {
        format: {
            pos : "%v",
            neg : "(%v)",
            zero: "%v"
        },
        precision: 0,
    }
    //console.log('cellValue = ' + cellvalue + ', formatted = ' + accounting.formatMoney(cellvalue, options));
    return accounting.formatMoney(cellvalue, opts);
}

function getBaseUrl(url) {
    return base_url + url;
}

function confirm(sMessage, sType, func) {
    swal({
        title: 'CyberHRM',
        text: sMessage,
        type: sType,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            func();
        }
    });
}

function msgbox(sMessage, sType) {
    swal({
        type: sType,
        text: sMessage,
        title: "CyberHRM",
    });
}

function toast(sMessage, sType) {
    wait = 1000;
    switch (sType) {
        case 'error':
            wait = 3000; break;
        case 'success':
            wait = 1000; break;
        case 'advise':
            wait = 5000; break;
    }
	swal({
		toast: true,
		position: 'center',
		showConfirmButton: false,
		timer: wait,
		type: (sType == 'advise' ? 'success': sType),
  		title: sMessage  
	});
}

function addCombo(cmbName, entity, defaultVal, afterFunc) {
    var cmb = $('#'+cmbName);
    if (!cmb.length) { return; }

    /**
    if (typeof exclude === 'undefined' || exclude == '') {
        exclude = '-';
    }*/
    cmb.closest('form').prop('disabled', true);

	$.ajax({
		type: "POST",
        url: base_url + "combo/query/?entity="+entity,
        async: false,
        success: function(data) {
            result = jQuery.parseJSON(data);
            // Remove current options
            cmb.children('option').remove();
            /**
            // Add the empty option with the empty message
            if (typeof emptyMessage !== 'undefined' && emptyMessage != '') {
                cmb.append('<option value="-255">' + emptyMessage + '</option>');
            }*/

            // Check result isnt empty
            isDefaultExists = false; firstVal = '';
            if(result != '') {
                // Loop through each of the results and append the option to the dropdown                
                $.each(result, function(k, v) {
                    if (typeof defaultVal !== 'undefined' && defaultVal != '' && v.ID == defaultVal) {
                        isDefaultExists = true;
                    }
                    cmb.append('<option value="' + v.ID + '">' + v.LABEL + '</option>').hide().show();
                    if (firstVal == '') firstVal = v.ID;
                });
                
            }

            cmb.ready(function(){
                if (isDefaultExists || firstVal != '') {
                    cmb.val((isDefaultExists ? defaultVal : firstVal)).change();
                }
                cmb.closest('form').prop('disabled', false);
                if (afterFunc != undefined) {
                    afterFunc();
                }    
            });            
		}
	});
}

function bindForm(frm, data) {
    $('#'+frm).trigger('reset');

    //Object.keys(data).forEach(function(key) {
    $('#'+frm+' input, #'+frm+' select, #'+frm+' textarea').each(function(index) {
        ctrl = $(this);
        //ctrl = $("[name='"+key+"']");
        key = ctrl.prop('name');
        
        //if (ctrl.length) {
        if (key in data) {
            val = data[key];            
            tag = ctrl.attr('data-tag');

            if (typeof tag === typeof undefined || tag === false) {
                tag = ";";
            }      

            //data types
            if (ctrl.is('select')) {
                ctrl.val(val).change();
            } else if (tag.indexOf('date') >= 0 && val != '') {
                d = moment(val,'YYYY-MM-DD').format('MMMM D, YYYY');
                if (d == 'Invalid date') d = "";
                ctrl.val(d);
            } else if (tag.indexOf('num') >= 0) {
                ctrl.val(accounting.format(parseFloat(val), 2));
                ctrl.css('text-align', 'right');
            } else if (tag.indexOf('int') >= 0) {
                ctrl.val(accounting.format(parseFloat(val), 0));
            } else {
                ctrl.val(val);
            }

            if (tag.indexOf('lock') >= 0 || tag.indexOf('key') >= 0) {
                ctrl.prop('readonly', true);
            }
        }
    });
}

function waitMode(b) {
    var frm = $('#mdlWait');
    if (!frm.length) {
        $("body").append("<div id=\"mdlWait\" class=\"ui basic modal\"><div class=\"content\" align=\"center\">"+
            "<i class=\"clock icon\"></i>Mohon tunggu ...</div></div>");
        frm = $('#mdlWait');
    }

    if (b) {
        frm.modal({
            closable: false
        }).modal('show');
    } else {
        frm.modal('hide');
    }
}

function lockKeys(frmId, bLock) {
    $('#'+frmId+' input[data-tag]').each(function(idx, el) {
        tag = $(this).data('tag');
        if (tag.indexOf('key') >= 0) {
            $(this).prop('readonly', bLock);
            if ( !bLock && $(this).val() == '' ) {
                //assign default values
                if (tag.indexOf('num') >= 0) {
                    $(this).val(accounting.format(0, 2));
                } else if (tag.indexOf('int') >= 0) {
                    $(this).val('0');
                } else if (tag.indexOf('date') >= 0) {
                    //$(this).val(moment().format('MMMM D, YYYY'));
                }
            }
        }
    });
    $('#'+frmId+' select[data-tag]').each(function(idx, el) {
        tag = $(this).data('tag');
        if (tag.indexOf('key') >= 0) {
            $(this).prop('disabled', bLock);
        }
    });
}

function editMode(frm, data) {
    bindForm(frm, data);
    lockKeys(frm, true);

    $('#'+frm+' #cmdDelete').css('display','inline');
}

function addNewMode(frm) {
    $('#'+frm).trigger('reset');
    lockKeys(frm, false);
    $('#'+frm+' #cmdDelete').css('display','none');
}

function showModal(frmId, title, approveFunc, denyFunc) {
    $('#'+frmId+' #title').text(title);
	$('#'+frmId).modal({
		closable: false,
		onDeny: function(e) {
			if (e.prop("id") != 'cmdCancel') {
                if (denyFunc != undefined) {
                    denyFunc(e.prop("id")); return false;
                }
			} else {
				return true;
			}
		},
		onApprove: function() {
            if (approveFunc != undefined) {
                approveFunc(); 
                return false;
            }
		}
    }).modal('show'); // show bootstrap modal when complete loaded
    $('#'+frmId+' .ui.calendar').each(function() {
        ctype = $(this).data('type');
        if (ctype == undefined) {

        } else {
            $(this).calendar({
                type: ctype,
                on: 'click',
                ampm: false
            });
        }
    });
}

function openModal(frmId, title, approveFunc, denyFunc) {
    //get modal div
    frmId = $('#'+frmId).parents('.ui.modal').prop('id');
    showModal(frmId, title, approveFunc, denyFunc);
}

function closeModal(frmId) {
    $('#'+frmId).parents('.ui.modal').modal('hide');
}

function queryRecord(uri, successFunc) {
    //waitMode(true);

	//Ajax Load data from ajax
	$.ajax({
		url : uri,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
            //waitMode(false);
			if (data.status) {
				successFunc(data);
			} else {
				toast("Data tidak ditemukan", "error");
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
            //waitMode(false);
			toast("Gagal berkomunikasi dengan server", "error");
		}
	});
}

function saveRecord(frmId, uri, successFunc, failFunc) {
    var cSave = $('#'+frmId+' #cmdSave');
    cSave.prop('disabled',true);
	
    // ajax adding data to database
    $.ajax({
        url : uri,
        type: "POST",
        data: $('#'+frmId).serialize(),
		dataType: "JSON",
        success: function(data) { 
			cSave.prop('disabled',false); //set button enable 
            if(data.status) {
				successFunc(data);
            } else {
                if (failFunc == undefined) {
                    toast(data.error, 'error');
                } else {
                    failFunc(data);
                }
			}
        },
        error: function (jqXHR, textStatus, errorThrown) {
            cSave.prop('disabled',false); //set button enable
            if (failFunc == undefined) {
                toast('Maaf, data tidak dapat disimpan','error');
            } else {
                failFunc();
            }
        }
	});
}

function removeRecord(frmId, msg, uri, successFunc, failFunc) {
    cDel = $('#'+frmId+' #cmdDelete');
    cDel.prop('disabled',true); //set button disable 
	
	confirm(msg, 'warning', function(){
		// ajax adding data to database
		$.ajax({
			url : uri,
			type: "POST",
			data: $('#'+frmId).serialize(),
			dataType: "JSON",
			success: function(data) { 
				cDel.prop('disabled',false); //set button enable 
				if(data.status) {
                    successFunc(data);
				} else {
                    if (failFunc == undefined) {
                        toast(data.error,'error');
                    } else {
                        failFunc(data);
                    }
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
                cDel.prop('disabled',false); //set button enable
                if (failFunc == undefined) {
                    toast('Maaf, data tidak dapat dihapus','error');
                } else {
                    failFunc();
                }
			}
		});
	});
}

function previewReport(params) {
    var frm = $('#frmReport');
    if (!frm.length) {
        $("body").append("<form style=\"display:none\" id=\"frmReport\"></form>");
        frm = $('#frmReport');
    }

    //clear form
    frm.children().each(function () {
		if ($(this).is('input')) $(this).remove();
	});

    //populate params
    var title = '';
    for (var key in params) {
        if (key == 'rptTitle') title = params[key];
        $('<input>', {
            type:'hidden',
            name: key,
            value: params[key]
        }).appendTo(frm);
    }

	window.open('about:blank', title);
    frm.prop('target', title);
    frm.prop('action', base_url + 'report/view');
    frm.prop('method', 'POST');
	frm.submit();
}