// reset button
function resetForm(){
    document.getElementById('signupForm').reset();
}

// show current datetime
function updateDateTime() {
    const currentDateTimeElement = document.getElementById('currentDateTime');
    const now = new Date();
    const formattedDateTime = now.toLocaleString();
    currentDateTimeElement.textContent = formattedDateTime;
}

setInterval(updateDateTime, 1000);
updateDateTime();

// sidebar FoodList

document.getElementById('food-list').addEventListener('click', function (e) {
    e.preventDefault();
    fetch('food.php')
        .then(response => response.text())
        .then(data => document.getElementById('content').innerHTML = data);
});
