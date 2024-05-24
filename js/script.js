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

