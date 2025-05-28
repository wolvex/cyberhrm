$(document).ready(function() {
	$('.ui.accordion').accordion();
	$('.ui.dropdown').dropdown();
    
    
    if ($('#mnuAdmin').find(".item").length == 0) {
        $('#mnuAdmin').css('display', 'none');
    } else {
        $('#mnuAdmin').css('display', 'inline');
    }
    
    if ($('#mnuEmployee').find(".item").length == 0) {
        $('#mnuEmployee').css('display', 'none');
    } else {
        $('#mnuEmployee').css('display', 'inline');
    }

    if ($('#mnuPayroll').find(".item").length == 0) {
        $('#mnuPayroll').css('display', 'none');
    } else {
        $('#mnuPayroll').css('display', 'inline');
    }

});