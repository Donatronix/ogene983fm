<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
            <div class="content">
                <div class="form-group">
                    <label>Date</label>
                    <div class="input-group date datepicker">
                        <input class="form-control" placeholder="MM/DD/YYYY" /><span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <div class="input-group time timepicker">
                        <input class="form-control" placeholder="HH:MM AM/PM" /><span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-clock"></i></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700");
    @import url("https://fonts.googleapis.com/css?family=Pacifico");

    .input-group-addon {
        cursor: pointer;
    }

    .form-control {
        border: 1px solid #ccc;
        box-shadow: none;
    }

    .form-control:hover,
    .form-control:focus,
    .form-control:active {
        box-shadow: none;
    }

    .form-control:focus {
        border: 1px solid #34495e;
    }



    .content {
        background: #fff;
        border-radius: 3px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075), 0 2px 4px rgba(0, 0, 0, 0.0375);
        padding: 30px 30px 20px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu {
        border: 1px solid #34495e;
        border-radius: 0;
        box-shadow: none;
        margin: 10px 0 0 0;
        padding: 0;
        min-width: 300px;
        max-width: 100%;
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu.bottom:before,
    .bootstrap-datetimepicker-widget.dropdown-menu.bottom:after {
        display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td,
    .bootstrap-datetimepicker-widget.dropdown-menu table th {
        border-radius: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.old,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.new {
        color: #bbb;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.today:before {
        border-bottom-color: #0095ff;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.active,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover,
    .bootstrap-datetimepicker-widget.dropdown-menu table td span.active {
        background-color: #0095ff;
        text-shadow: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.active.today:before,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover.today:before,
    .bootstrap-datetimepicker-widget.dropdown-menu table td span.active.today:before {
        border-bottom-color: #fff;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table th {
        height: 40px;
        padding: 0;
        width: 40px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table th.picker-switch {
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table tr:first-of-type th {
        border-bottom: 1px solid #34495e;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.day {
        height: 32px;
        line-height: 32px;
        padding: 0;
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td span {
        border-radius: 0;
        height: 77px;
        line-height: 77px;
        margin: 0;
        width: 25%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-months tbody tr td,
    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-years tbody tr td,
    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
        padding: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
        height: 27px;
        line-height: 27px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span {
        display: block;
        float: left;
        width: 50%;
        height: 46px;
        line-height: 46px !important;
        padding: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span:not(.decade) {
        display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td {
        padding: 0;
        width: 30%;
        height: 20px;
        line-height: 20px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td:nth-child(2) {
        width: 10%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td a,
    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span,
    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
        border: none;
        border-radius: 0;
        height: 56px;
        line-height: 56px;
        padding: 0;
        width: 100%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span {
        color: #333;
        margin-top: -1px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
        background-color: #fff;
        color: #333;
        font-weight: bold;
        font-size: 1.2em;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button:hover {
        background-color: #eee;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td {
        border-top: 1px solid #34495e;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td a,
    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td span {
        display: block;
        height: 40px;
        line-height: 40px;
        padding: 0;
        width: 100%;
    }

    .todayText:before {
        content: "Today's Date";
    }

</style>
@endpush

@push('js')
<script>
    $(document).ready(function () {
        if (/Mobi/.test(navigator.userAgent)) {
            // if mobile device, use native pickers
            $(".date input").attr("type", "date");
            $(".time input").attr("type", "time");
        } else {
            // if desktop device, use DateTimePicker
            $(".datepicker").datetimepicker({
                useCurrent: false,
                format: "L",
                showTodayButton: true,
                icons: {
                    next: "fa fa-chevron-right",
                    previous: "fa fa-chevron-left",
                    today: 'todayText',
                }
            });
            $(".timepicker").datetimepicker({
                format: "LT",
                icons: {
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down"
                }
            });
        }
    });

</script>
@endpush
