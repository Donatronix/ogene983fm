/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(function () {

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    function capitalizeFirstLetter(s) {
        if (typeof s != 'string') {
            return '';
        }
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    function toUpper(str) {
        return str
            .toLowerCase()
            .split(' ')
            .map(function (word) {
                return word[0].toUpperCase() + word.substr(1);
            })
            .join(' ');
    }

    function AddErrorMsg(msg, title) {
        $.sweetModal({
            blocking: true,
            content: msg,
            title: title,
            icon: $.sweetModal.ICON_ERROR,
            buttons: [{
                label: 'Ok',
                classes: 'redB'
            }]
        });
    }

    function AddMsg(msg, title) {
        $.sweetModal({
            blocking: true,
            content: msg,
            title: title,
            icon: $.sweetModal.ICON_SUCCESS,
            buttons: [{
                label: 'Ok',
                classes: 'greenB'
            }]
        });
    }

    function showNotification(title, message, type, icon) {
        $.notify({
            title: title + ' : ',
            message: message,
            icon: 'fa ' + icon
        }, {
            type: type,
            allow_dismiss: true,
            placement: {
                from: "top",
                align: "right"
            },
        });
    }

    function flyToElement(flyer, flyingTo) {
        var divider = 3;
        var flyerClone = $(flyer).clone();
        $(flyerClone).css({
            position: 'absolute',
            top: $(flyer).offset().top + "px",
            left: $(flyer).offset().left + "px",
            opacity: 1,
            'z-index': 1000
        });
        $('body').append($(flyerClone));
        var gotoX = $(flyingTo).offset().left + ($(flyingTo).width() / 2) - ($(flyer).width() / divider) / 2;
        var gotoY = $(flyingTo).offset().top + ($(flyingTo).height() / 2) - ($(flyer).height() / divider) / 2;

        $(flyerClone).animate({
                opacity: 0.4,
                left: gotoX,
                top: gotoY,
                width: $(flyer).width() / divider,
                height: $(flyer).height() / divider
            }, 700,
            function () {
                $(flyingTo).fadeOut('fast', function () {
                    $(flyingTo).fadeIn('fast', function () {
                        $(flyerClone).fadeOut('fast', function () {
                            $(flyerClone).remove();
                        });
                    });
                });
            });
    }

    function getCount(str, stringsearch) {
        var count = -1;
        for (var i = 0; i < str.length; count += +(stringsearch === str[i++])) {}
        return count;
    }

    function ValidateEmail(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return (true);
        }
        alert("You have entered an invalid email address!");
        return (false);
    }

    function isEmpty(val) {
        if (val == "") {
            return true;
        } else if (val == null) {
            return true;
        } else if (Array.isArray(val)) {
            return true;
        } else if (val.length == 0) {
            return true;
        } else if (typeof val == 'undefined') {
            return true;
        }
        return false;
    }

    function snap() {
        // Get handles on the video and canvas elements
        var all = document.getElementsByTagName("video");

        for (var i = 0, max = all.length; i < max; i++) {
            var video = document.getElementsByTagName("video")[i];
            var id = video.id;

            //get canvas element
            var canvas = document.getElementsByClassName(id)[i];

            // Get a handle on the 2d context of the canvas element
            var context = canvas.getContext('2d');

            // Define some vars required later
            var w, h, ratio;

            // Calculate the ratio of the video's width to height
            ratio = video.videoWidth / video.videoHeight;

            // Define the required width as 100 pixels smaller than the actual video's width
            w = video.videoWidth - 100;

            // Calculate the height based on the video's width and the ratio
            h = parseInt(w / ratio, 10);

            // Set the canvas width and height to the values just calculated
            canvas.width = w;
            canvas.height = h;

            // Define the size of the rectangle that will be filled (basically the entire element)
            context.fillRect(0, 0, w, h);
            // Grab the image from the video
            context.drawImage(video, 0, 0, w, h);
        }
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function addElementToBody(elementTag, elementId, html) {
        // Adds an element to the document
        var p = document.getElementsByTagName("body")[0];
        var newElement = document.createElement(elementTag);
        newElement.setAttribute('id', elementId);
        newElement.innerHTML = html;
        p.appendChild(newElement);
    }

    function addElement(parentId, elementTag, elementId, html) {
        // Adds an element to the document
        var p = document.getElementById(parentId);
        var newElement = document.createElement(elementTag);
        newElement.setAttribute('id', elementId);
        newElement.innerHTML = html;
        p.appendChild(newElement);
    }

    function removeElement(elementId) {
        // Removes an element from the document
        var element = document.getElementById(elementId);
        element.parentNode.removeChild(element);
    }

    function displayLoading() {
        //    var loader = '<div id="loader-wrapper"><div id="loader"></div></div>';
        addElementToBody('div', "loader-wrapper", '<div id="loader"></div>');
    }

    function removeLoading() {
        var elementId = 'loader-wrapper';
        removeElement(elementId);
    }

    var myVar;

    function startTimer(func, duration) {
        myVar = setTimeout(func, duration);
    }

    function stopTimer() {
        clearTimeout(myVar);
    }

    // base url
    function baseUrl() {
        var pathparts = location.pathname.split('/');
        var url = null;
        if (location.host == 'localhost') {
            url = location.origin + '/' + pathparts[1].trim('/') + '/';
        } else {
            url = location.origin + '/';
        }
        return url;
    }
    var myProcess;

    function startProcess() {
        myProcess = false;
    }

    function endProcess() {
        myProcess = ~myProcess;
    }

    function isProcessing() {
        return myProcess;
    }

    function ucfirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function disableLink(link) {
        // 1. Add isDisabled class to parent span
        link.parentElement.classList.add('isDisabled');
        // 2. Store href so we can add it later
        link.setAttribute('data-href', link.href);
        // 3. Remove href
        link.href = '';
        // 4. Set aria-disabled to 'true'
        link.setAttribute('aria-disabled', 'true');
    }

    function enableLink(link) {
        // 1. Remove 'isDisabled' class from parent span
        link.parentElement.classList.remove('isDisabled');
        // 2. Set href
        link.href = link.getAttribute('data-href');
        // 3. Remove 'aria-disabled', better than setting to false
        link.removeAttribute('aria-disabled');
    }

    document.body.addEventListener('click', function (event) {
        // filter out clicks on any other elements
        if (event.target.nodeName == 'A' && event.target.getAttribute('aria-disabled') == 'true' && this.parentElement.classList.contains('isDisabled')) {
            event.preventDefault();
        }
    });

    function formatCurrency(value) {
        value = parseFloat(value);
        formatted = value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); // "1,234,567.89"
        return formatted;
    }

    function formatNumber(value) {
        value = parseInt(value);
        formatted = value.replace(/\d(?=(\d{3})+\.)/g, '$&,'); // "1,234,567"
        return formatted;
    }

    function copyUrl() {
        if (!window.getSelection) {
            alert('Please copy the URL from the location bar.');
            return;
        }
        const dummy = document.createElement('p');
        dummy.textContent = window.location.href;
        document.body.appendChild(dummy);

        const range = document.createRange();
        range.setStartBefore(dummy);
        range.setEndAfter(dummy);

        const selection = window.getSelection();
        // First clear, in case the user already selected some other text
        selection.removeAllRanges();
        selection.addRange(range);

        document.execCommand('copy');
        document.body.removeChild(dummy);
    }

    $(document).on("keypress keyup blur", ".money", function (event) {
        var $this = $(this);
        if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
                (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }

        var text = $(this).val();
        if ((event.which == 46) && (text.indexOf('.') == -1)) {
            setTimeout(function () {
                if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                    $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                }
            }, 1);
        }

        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }
    });

    $('.money').on("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if (isNumber(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        } else {
            e.preventDefault();
        }
    });

    $(document).on("keypress keyup blur", ".numberOnly", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ""));
        if ((event.which !== 46 || $(this).val().indexOf(".") !== -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $('.numberOnly').on("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if (isNumber(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        } else {
            e.preventDefault();
        }
    });

    $(document).on("keypress keyup blur", ".allowNumericWithDecimal", function (event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g, ""));
        if (
            (event.which !== 46 || $(this).val().indexOf(".") !== -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $('.allowNumericWithDecimal').on("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if (isNumber(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        } else {
            e.preventDefault();
        }
    });

    $(document).on("keypress keyup blur", ".allowNumericWithoutDecimal", function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });

    $('.allowNumericWithoutDecimal').on("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if (isNumber(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        } else {
            e.preventDefault();
        }
    });

    $(document).on("click", ".out-wrap ul li a", function () {
        snap();
    });

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
        if (c) {
            parent.closest(".msg_body").hide(effect, 500);
        }
    });

    $(".dropdown").on("mouseenter", function () {
            $(".dropdown-menu", this).fadeIn("fast");
        })
        .on("mouseleave", function () {
            $(".dropdown-menu", this).fadeOut("fast");
        });

    snap();

    $(document).on('click', '.copyUrl', function (e) {
        e.preventDefault();
        try {
            copyUrl();
        } catch (error) {
            AddErrorMsg(error, 'URL Copy');
        }
        AddMsg('Web url has been copied to the clipboard.<br/> You can paste wherever you wish.', 'URL Copy');
    });

});
