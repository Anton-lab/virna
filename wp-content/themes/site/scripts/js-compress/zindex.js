$(document).ready(function () {
    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);


/*    var toTop = $('.to-top');
    $(window).scroll(function () {
        if ($(this).scrollTop() >= $(document).height() - $(this).height()) {
            toTop.css({
                bottom: "105px",
                opacity: 1,
            });
        } else {
            toTop.css({
                bottom: "-70px",
            });
        }
    });

    toTop.click(function () {
        $("html, body").stop().animate({
            scrollTop: 0
        }, 1000)
    });*/
});

$(window).scroll(function () {
    if ($(this).scrollTop() === 0) {
        $('header').removeClass('white');
    } else {
        $('header').addClass('white');
    }
});

/*
function scrollDown() {
    $([document.documentElement, document.body]).animate({
        scrollTop: $(".banner-wrapper").innerHeight() - $('header').innerHeight() + 100
    }, 1000);
}*/
