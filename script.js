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

function validateForm(name, age, email, course, year_level, avatar) {
    let isValid = true;

    // name validations
    if(name.length < 2 || !name.includes(" ")){
        isValid = false;
        alert("Valid name must be entered.");
    }

    // age validations
    if(isNaN(age) || age < 0 || age > 99){
        isValid = false;
        alert("Age must be between 0 and 99.");
    }

    // email validations
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(email)){
        isValid = false;
        alert("Invalid email format.");
    }

    // course validations
    if(course.length < 5){
        isValid = false;
        alert("Course must be entered.");
    }

    // year level validations
    if(!year_level){
        isValid = false;
        alert("Year Level must be entered.");
    }
    
    // avatar validations
    if(!avatar){
        alert("Select an avatar.");
    }

    return isValid;
}