

$(function () {

    $('[data-item=sort]').each(function () {
        const $container = $(this);

        $container.on('click', function (e) {
            e.preventDefault();

            const type = $container.data('type');
            const href = $container.data(`${type}Href`);

            console.log('Type:', type); // Вывод значения type в консоль
            console.log('Href:', href); // Вывод значения href в консоль

            $.ajax({
                url: href,
                method: 'POST',
                data: { type: type }
            }).then(function (data) {
                $container.data('type', type === 'DESC' ? 'ASC' : 'DESC');
            });
        });
    });

});