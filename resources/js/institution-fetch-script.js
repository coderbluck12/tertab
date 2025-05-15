<!-- JavaScript for Dynamic Institution Fetching -->
document.addEventListener("DOMContentLoaded", function () {
    let stateDropdown = document.getElementById("state");
    let institutionDropdown = document.getElementById("institution");

    stateDropdown.addEventListener("change", function () {
        let stateId = this.value;
        institutionDropdown.innerHTML = '<option value="">Select Institution</option>'; // Reset

        if (stateId) {
            fetch(`/institutions-by-state/${stateId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(institution => {
                        let option = document.createElement("option");
                        option.value = institution.id;
                        option.textContent = institution.name;
                        institutionDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error("Error fetching institutions:", error));
        }
    });
});
