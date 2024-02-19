function setSessionData(idPrestataire, idComposante) {
    // Make an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "set_session_data.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Log the response for debugging
        }
    };
    // Send the data to the server
    xhr.send("id_prestataire=" + idPrestataire + "&id_composante=" + idComposante);
}