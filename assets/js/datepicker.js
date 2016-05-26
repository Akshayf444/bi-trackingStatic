$('document').ready(function () {
//    $(".datepicker").datepicker({
//        changeMonth: true,
//        changeYear: true,
//        dateFormat: 'yy-mm-dd'
//    });
    var dateToday = new Date();
    $('.datepicker').eq(0).datepicker({
        dateFormat: "yy-mm-dd",
        minDate: null,
        onSelect: function (selected) {
            $('.datepicker').eq(1).datepicker("option", "minDate", $('.datepicker').eq(0).datepicker('getDate'))
        }
    });

    $('.datepicker').eq(1).datepicker({
        dateFormat: "yy-mm-dd",
        minDate: null,
    });
});