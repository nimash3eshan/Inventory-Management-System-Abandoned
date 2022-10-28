//for printing Invoice
function printInvoice() {
    window.print();
}

$(function () {
    'use strict';
    // initializing select 2
    $('.select2').select2();

    //showing report
    $("#reportStartDate").datepicker({
        changeDate:true,
        changeMonth:true,
        changeYear:true,
        yearRange:'1970:+0',
        dateFormat:'yy-mm-dd',
        onSelect:function(dateText){
            var DateCreated = $('#reportStartDate').val();
            var data = {DateCreated:DateCreated};
            var url = "/reports/getdailyreport";
            var appendId = "daily-report";
            getReports(data, url, appendId);
        }
    });

    // for date wise sales filtering
    $("#start_date, #end_date").datepicker({
        changeMonth:true,
        changeYear:true,
        yearRange:'1970:+0',
        dateFormat:'yy-mm-dd',
    });

    // for date wise sales filtering
    $("#lStartDate, #lEndDate").datepicker({
        changeMonth:true,
        changeYear:true,
        yearRange:'1970:+0',
        dateFormat:'yy-mm-dd',
        onSelect:function(dateText){
            var DateCreated = $('#lStartDate').val();
            var EndDate = $('#lEndDate').val();
            var inputData = {DateCreated:DateCreated,EndDate:EndDate};
            var url = "/reports/getsales";
            getReports(inputData, url, "list-sale-report")
        }
    });

});


function getReports(inputData, url, appendId)
{
    $.ajax({
        type : 'get',
        url : site_url + url,
        data : inputData,
        success:function(data)
        {
            $('#' + appendId).empty().html(data);
        }
    });
}

function submitForm(id)
{
    $(id).submit();
}

$("#holdSale").on("click", function(){
    $(this).parents('Form').append("<input type='hidden' name='action' value='hold-sale'>");
    $(this).parents('Form').submit();
});

function closeEl(removeParentDiv='', removeClass='') {
    $(removeParentDiv).removeClass("in");
    $(removeParentDiv).hide();
    $(".modal-backdrop").remove();
    $("body").removeClass("modal-open");
}