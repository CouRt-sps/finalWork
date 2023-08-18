import $ from 'jquery';

$(function () {
    $('.Sort-sortBy').on('click', function (e) {
        e.preventDefault();

        const $link = $(this);
        const currentDirection = $link.data('direction');
        const newDirection = currentDirection === 'DESC' ? 'ASC' : 'DESC';

        // Изменяем направление сортировки
        $link.data('direction', newDirection);

        // Отправляем AJAX запрос на сервер
        $.ajax({
            url: '/catalog',
            method: 'POST',
            data: {
                sortBy: $link.data('type'),
                direction: newDirection
            },
            success: function (response) {
                // Обработка успешного ответа от сервера
                // Здесь вы можете обновить содержимое страницы с отсортированными данными
            },
            error: function (error) {
                // Обработка ошибки
                console.error('Ошибка AJAX запроса:', error);
            }
        });
    });
});