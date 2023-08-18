function updateTimer() {
    var currentDate = new Date();
    var endOfDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), 23, 59, 59);
    var timeRemaining = endOfDay - currentDate;

    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

    document.querySelector('.CountDown-hours').textContent = hours;
    document.querySelector('.CountDown-minutes').textContent = minutes;
    document.querySelector('.CountDown-secs').textContent = seconds;
}

setInterval(updateTimer, 1000);