// Form validation before inserting to the database
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("regisForm").addEventListener("submit", function (e) {
        const name = document.getElementById("name").value.trim();
        const age = parseInt(document.getElementById("age").value);
        const email = document.getElementById("email").value.trim();
        const course = document.getElementById("course").value.trim();
        const year_level = document.querySelector('input[name="year_level"]:checked');
        const avatar = document.querySelector('input[name="avatar"]:checked');

        if (!validateForm(name, age, email, course, year_level, avatar)) {
            e.preventDefault();
            return;
        }
    });

}); 



 
function validateForm(name, age, email, course, year_level, grad_status, file_input) {
    let isValid = true;

    if (name.length < 2 || name.length > 40) {
        isValid = false;
        alert("Name must be between 2 and 40 characters.");
    }

    if (isNaN(age) || age < 0 || age > 99) {
        isValid = false;
        alert("Age must be between 0 and 99.");
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email) || email.length > 40) {
        isValid = false;
        alert("Invalid email or exceeds 40 characters.");
    }

    if (course.length === 0 || course.length > 40) {
        isValid = false;
        alert("Course is required (Max 40 chars).");
    }

    if (!year_level || !['1', '2', '3', '4'].includes(year_level.value)) {
        isValid = false;
        alert("Please select a Year Level (1-4).");
    }



    if (file_input.files.length === 0) {
        isValid = false;
        alert("Please upload an image.");
    }

    return isValid;
}

const studentInput = document.getElementById('studentName');
const searchBtn = document.getElementById('searchNameBtn');
const updateBtn = document.getElementById('updateNameBtn');

searchBtn.addEventListener('click', function() {
    const nameValue = studentInput.value;

    // Send the name to your PHP file
    fetch(`search.php?name=${encodeURIComponent(nameValue)}`)
        .then(response => response.text()) // Wait for the PHP response
        .then(data => {
            // 'data' is whatever your PHP file "echoes"
            console.log("Response from PHP:", data);
            alert("Result: " + data);
        })
        .catch(error => console.error('Error:', error));
});
updateBtn.addEventListener('click', function() {
    const nameValue = studentInput.value;

    if (!nameValue) {
        alert("Please enter a name to edit.");
        return;
    }

    // Using POST to send data
    fetch('update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `studentName=${encodeURIComponent(nameValue)}&status=updated`
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server says:", data);
        alert("Update status: " + data);
    })
    .catch(error => console.error('Error:', error));
});


// teka
document.getElementById("accForm").addEventListener("submit", function (e) {
        const title = document.getElementById("title").value.trim();
        const amount = parseFloat(document.getElementById("amount").value.trim());
        const status = document.querySelector('input[name="status"]:checked');
       
        if (!validateForm(name, age, email, course, year_level, avatar)) {
            e.preventDefault();
            return;
        }
    });