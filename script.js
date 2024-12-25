document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    // Collect form data
    const loginData = {
        username: document.getElementById("username").value,
        password: document.getElementById("password").value
    };

    // Send a POST request to login.php with the login data
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(loginData) // Send login data as JSON
    })
    .then(response => {
        // Log the response to see what it contains
        console.log(response);
        
        // Check if the response is HTML or JSON
        if (response.ok) {
            return response.json(); // Parse as JSON if successful
        } else {
            return response.text(); // Otherwise, return the response as plain text
        }
    })
    .then(data => {
        console.log(data); // Log the parsed data
        
        if (data.status === 'success') {
            // Redirect to dashboard after successful login
            window.location.href = "dashboard.php";
        } else {
            // Show error message if login failed
            alert('Error: ' + data.message);
            document.getElementById('error-message').textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error); // Log the error in console
        alert('An error occurred: ' + error.message); // Alert the error
    });
});



//clearance

fetch('clearance.php') // Change to your actual PHP endpoint
.then(response => response.json())
.then(data => {
    if (data.status === 'success') {
        const clearanceStatus = data.clearance.is_cleared === 1 ? 'Cleared' : 'Not Cleared';
        document.getElementById('clearance-status').textContent = clearanceStatus;
        document.getElementById('clearance-date').textContent = data.clearance.clearance_date || 'Not Available';
    } else {
        document.getElementById('clearance-status').textContent = 'Error: ' + data.message;
        document.getElementById('clearance-date').textContent = '';
    }
})
.catch(error => {
    console.error('Error fetching clearance data:', error);
    document.getElementById('clearance-status').textContent = 'Error';
    document.getElementById('clearance-date').textContent = 'Error';
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('api/clearance.php') 
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                const clearance = data.clearance;
                const clearanceStatus = clearance.is_cleared === 1 ? 'Cleared' : 'Not Cleared';
                document.getElementById('clearance-status').textContent = clearanceStatus;
                document.getElementById('clearance-date').textContent = clearance.clearance_date || 'Not Available';

                const departmentsTableBody = document.getElementById('department-status');
                departmentsTableBody.innerHTML = ''; 

                for (const [department, status] of Object.entries(clearance.departments)) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${department}</td><td>${status === 1 ? 'Signed' : 'Not Signed'}</td>`;
                    departmentsTableBody.appendChild(row);
                }
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching clearance data:', error);
        });
});


//profile



fetch('profile.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('name').innerText = `${data.first_name} ${data.middle_name} ${data.last_name}`;
        document.getElementById('email').innerText = data.email;
        document.getElementById('phone').innerText = data.phone;
        document.getElementById('address').innerText = data.address;
        document.getElementById('profile-image').src = data.profile_image || 'default-image.jpg';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load profile data. Check the console for details.');
    });


