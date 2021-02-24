(function ($) {
    // Application Modal
    // $('#applicationModal').on('show.bs.modal', function (e) {
    //     alert('CALLED');
    // }).modal('show');
    $('#applicationModal').modal()

    $(document).ready(function () {
        // Data Table
        $('#jobs-table').DataTable();

        // Bootstrap Date Picker
        $('#period-calendar-1, #period-calendar-2, #period-calendar-3, #period-calendar-4, #period-calendar-5').datepicker({
            multidate: true
        });

        if($('#applicant_calendar').length) {
            $.ajax({
                url: "/application/step/5/getAvailableDates",
            })
            .done(function (data) {
                var availableDates = data;

                $('#applicant_calendar').datepicker({
                    beforeShowDay: function (date) {
                        var formattedDate = moment(date).format('MM/DD/YYYY');
                        
                        // Check if the date is available and make it clickable
                        return availableDates.indexOf(formattedDate) > -1
                    },
                }).on('changeDate', function (e) {
                    var selectedFormattedDate = moment(e.date).format('MM/DD/YYYY');
                    $('#applicant_date').val(selectedFormattedDate);
                });;
            });
        }

        // Show career description
        $(document).bind('change', '#career', function() {
            var this_career = $(this);
            $('.career-desc').each(function(k,v) {
                if($(this).data('career') == this_career.val()) {
                    $(this).fadeIn();
                } else {
                    $(this).hide();
                }
            });
        })

    });
})(jQuery);