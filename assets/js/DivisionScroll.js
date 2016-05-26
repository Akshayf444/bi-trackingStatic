$('document').ready(function () {
    $("#fixeddiv2").click(function () {
        var leftPos = $('div.outer_container').scrollLeft();
        console.log(leftPos);
        $("div.outer_container").animate({
            scrollLeft: leftPos - 500
        }, 800);
    });
    $("#fixeddiv").click(function () {
        var leftPos = $('div.outer_container').scrollLeft();
        console.log(leftPos);
        $("div.outer_container").animate({
            scrollLeft: leftPos + 500
        }, 800);
    });
});