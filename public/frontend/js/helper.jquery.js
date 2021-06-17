/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $(document).on("keypress keyup blur", ".numberOnly", function (event) {
        $(this).val(
            $(this)
            .val()
            .replace(/[^0-9\.]/g, "")
        );
        if (
            (event.which !== 46 ||
                $(this)
                .val()
                .indexOf(".") !== -1) &&
            (event.which < 48 || event.which > 57)
        ) {
            event.preventDefault();
        }
    });

    $(document).on("keypress keyup blur", ".allowNumericWithDecimal", function (
        event
    ) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val(
            $(this)
            .val()
            .replace(/[^0-9\.]/g, "")
        );
        if (
            (event.which !== 46 ||
                $(this)
                .val()
                .indexOf(".") !== -1) &&
            (event.which < 48 || event.which > 57)
        ) {
            event.preventDefault();
        }
    });

    $(document).on(
        "keypress keyup blur",
        ".allowNumericWithoutDecimal",
        function (event) {
            $(this).val(
                $(this)
                .val()
                .replace(/[^\d].+/, "")
            );
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        }
    );


    $(document).on("click", ".delete", function () {
        var parent = $(this);
        //list of effects stored in array
        var effects = Array(
            "explode",
            "bounce",
            "fade",
            "blind",
            "clip",
            "drop",
            "fold",
            "transfer",
            "size",
            "shake"
        );
        //get random effect from effects array
        var effect = effects[Math.floor(Math.random() * effects.length)];
        var c = confirm("Delete this record?");
        if (c) parent.closest(".msg_body").hide(effect, 500);
    });

    $(".dropdown").hover(
        function () {
            $(".dropdown-menu", this).fadeIn("fast");
        },
        function () {
            $(".dropdown-menu", this).fadeOut("fast");
        }
    );


});
