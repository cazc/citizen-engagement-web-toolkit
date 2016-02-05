// is this how you fancy web devs write javascript these days?
(function($) {
    var rebind_buttons = function() {
        $(".handles-load-more:not(.is-load-more-action-bound)").addClass("is-load-more-action-bound").on("click", function() {
            $.ajax({
                url: this.getAttribute("data-src"),
                context: this.parentNode,
                dataType: "html"
            }).done(function(data, textStatus, jqXHR) {
                // the comment-list element
                var list = $(this.parentNode);

                // parse the loaded comments...
                var dummy = document.createElement("div");
                $(dummy).html(data);

                // now move them all onto the page.
                $(dummy).find(".comment-list").children().each(function(index, element) {
                    list.append(element);
                });

                // delete the load more button
                this.parentNode.removeChild(this);

                rebind_buttons();
            });
        });
    };

    $(document).ready(function() {
        rebind_buttons();
    });
})( jQuery );