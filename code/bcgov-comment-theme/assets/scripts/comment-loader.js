// is this how you fancy web devs write javascript these days?
(function($) {
    $(document).ready(function() {
        alert("ahh");

        $(".handles-load-more").on("click", function(e) {
            console.log(this);
        });
    });
})( jQuery );