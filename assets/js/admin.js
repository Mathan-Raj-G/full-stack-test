(function ($) {
    "use strict";

    $(function () {
        $(document).on("submit", "[data-confirm-delete]", function (event) {
            if (!window.confirm("This action will permanently delete the selected record. Do you want to continue?")) {
                event.preventDefault();
            }
        });

        $("input[type='file']").on("change", function () {
            const file = this.files && this.files[0] ? this.files[0].name : "";
            const $hint = $(this).next(".form-text");

            if ($hint.length && file) {
                $hint.text("Selected file: " + file);
            }
        });
    });
}(jQuery));
