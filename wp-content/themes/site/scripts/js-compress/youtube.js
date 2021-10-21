(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else {
        factory(root.jQuery);
    }
}(this, function ($) {
    'use strict';
    $.fn.youtube = function () {

        var videos = document.getElementsByClassName($(this).attr('class'));

        var nb_videos = videos.length;

        for (var i = 0; i < nb_videos; i++) {
            // Находим постер для видео, зная ID нашего видео
            videos[i].style.backgroundImage = 'url(http://i.ytimg.com/vi/' + videos[i].id + '/sddefault.jpg)';

            videos[i].onclick = function () {
                // Создаем iFrame и сразу начинаем проигрывать видео, т.е. атрибут autoplay у видео в значении 1
                var iframe = document.createElement("iframe");
                var iframe_url = "https://www.youtube.com/embed/" + this.id + "?autoplay=1&autohide=1";
                if (this.getAttribute("data-params")) iframe_url += '&' + this.getAttribute("data-params");
                iframe.setAttribute("src", iframe_url);
                iframe.setAttribute("frameborder", '0');
                // Высота и ширина iFrame будет как у элемента-родителя
                iframe.style.width = this.style.width;
                iframe.style.height = this.style.height;
                // Заменяем начальное изображение (постер) на iFrame
                this.parentNode.replaceChild(iframe, this);
            }
        }
    }
}));