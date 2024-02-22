document.addEventListener('DOMContentLoaded', function() {
    const sendDataLink = document.getElementById('sendDataLink');
    const responseMessage = document.getElementById('responseMessage');

    sendDataLink.addEventListener('click', function(event) {
        event.preventDefault();

        // Use the JavaScript variable containing the PHP data
        const idToSend = window.idToSend;

        // Configuration for the AJAX request
        const requestOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idToSend })
        };

        // URL of the page to which data will be sent
        const url = 'bdl_mission.php';

        // Send the AJAX request
        fetch(url, requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // or response.json() if expecting JSON response
            })
            .then(data => {
                // Display response from the server
                responseMessage.textContent = data;

                // Redirect the user to another page after receiving a successful response
                window.location.href = 'another_page.php'; // Replace with the URL of the page to which you want to redirect
            })
            .catch(error => {
                console.error('Error sending data to server:', error);
            });
    });
});
