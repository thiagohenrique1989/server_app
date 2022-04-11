;(function ($, formSelector, tableSelector) {
    let form = $(formSelector);
    let table = $(tableSelector);
    let controller = {
        bind: function () {
            form.find('#storage_slider').on('change', controller.slide);
            form.on('submit', controller.submit);
        },
        submit: function (event) {
            event.preventDefault();
            table.siblings('.spinner').removeClass('visually-hidden');
            table.find('tbody').html('');

            $.ajax('/search/index', {data: form.serialize()})
                .done(controller.show)
                .fail(controller.error);
        },
        show: function (data) {
            data.forEach(function(item){
                table.find('tbody').append(
                    "<tr>" +
                    "<td>" + item[0] + "</td>" +
                    "<td>" + item[1] + "</td>" +
                    "<td>" + item[2] + "</td>" +
                    "<td>" + item[3] + "</td>" +
                    "<td>" + item[4] + "</td>" +
                    "<tr/>"
                );
            });
            table.siblings('.spinner').addClass('visually-hidden');
        },
        error: function () {
            alert('An error has been occurred');
            table.siblings('.spinner').addClass('visually-hidden');
        },
        slide: function (event) {
            let slider = $(event.target);
            let ranges = [
                {value: 120, label: '120GB'},
                {value: 240, label: '240GB'},
                {value: 480, label: '480GB'},
                {value: 960, label: '960GB'},
                {value: 1000, label: '1TB'},
                {value: 1920, label: '1.92TB'},
                {value: 2000, label: '2TB'},
                {value: 2400, label: '2.4TB'},
                {value: 3840, label: '3.84TB'},
                {value: 4000, label: '4TB'},
                {value: 6000, label: '6TB'},
                {value: 8000, label: '8TB'},
                {value: 16000, label: '16TB'},
                {value: 24000, label: '24TB'}
            ];

            let selected = ranges[slider.val()];

            form.find('#storage_badge').text(selected.label);
            form.find('#storage').val(selected.value);
        }
    };

    return controller.bind();
})(jQuery, '#filter-form', '#list-table');
