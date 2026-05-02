// Form validation before inserting to the database
document.addEventListener("DOMContentLoaded", function() {

    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('success') === '1') {
        alert("Request successful!");
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Register new user   
    if (document.getElementById("regisForm")) { 
        document.getElementById("regisForm").addEventListener("submit", function (e) {
            const name = document.getElementById("name").value.trim();
            const age = parseInt(document.getElementById("age").value);
            const email = document.getElementById("email").value.trim();
            const course = document.getElementById("course").value.trim();
            const year_level = document.querySelector('input[name="year_level"]:checked');
            const file_input = document.querySelector('input[name="profile_photo"]');

            if (!validateForm(name, age, email, course, year_level, file_input)) {
                e.preventDefault();
                return;
            }
        });
    }

    // Add accountability
    if (document.getElementById("add_acc")) {
        document.getElementById("add_acc").addEventListener('click', function() {
            const student_query = document.getElementById("studentQueryAccountability").value.trim();
            if (student_query === "") {
                alert("Please enter a Student Number or Name first.");
                return;
            }

            const placement = document.getElementById("accFormPlacement");
            placement.innerHTML = "";
            
            const clone = document.getElementById("templateAccountability").content.cloneNode(true);
            const form = clone.querySelector("form");

            const student = document.createElement("input");
            student.type = "hidden";
            student.name = "student_identifier";
            student.value = student_query;
            form.appendChild(student);

            form.addEventListener('submit', function (e) {
                const title = form.querySelector('[name="title"]').value.trim();
                const amount = parseFloat(form.querySelector('[name="amount"]').value);
                const status = form.querySelector('select[name="status"]').value;
                
                if (!validateAcc(title, amount, status)) {
                    e.preventDefault();
                    return;
                }

                placement.appendChild(clone);
            });

            
            
    
        });
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
}); 


function validateForm(name, age, email, course, year_level, file_input) {
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


    if (file_input.files.length === 0 || !file_input) {
        isValid = false;
        alert("Please upload an image.");
    }

    return isValid;
}

function validateAcc(title, amount, status) {
    let isValid = true;

    if (title.length === 0) {
        isValid = false;
        alert("Title is required.");
    }

    if (isNaN(amount) || amount < 0) {
        isValid = false;
        alert("Invalid amount.");
    }

    if (!status) {
        isValid = false;
        alert("Please select a status.");
    }

    return isValid;
}
