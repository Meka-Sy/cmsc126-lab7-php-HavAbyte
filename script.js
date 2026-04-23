
function get_user_input() {
    document.getElementById("Form").addEventListener("submit", function (e)) {
        const name = document.getElementById("Name").value.trim();
        const age = parseInt(document.getElementById("age").value);
        const email = document.getElementById("email").value.trim();
        const course = document.getElementById("course").value.trim();
        const year_level = document.getElementsByName("year_level");
        const avatar = document.getElementsByName("avatar");
        const graduate = document.getElementById("graduate");
        graduate.style.display = checkbox.checked ? 'yes' : 'no';
    }
    
    
}

function validateForm(name, age, email, course, year_level, avatar, graduate){
    let isValid = true;

    // name validations
    if(name == ""){
        isValid = false;
        alert("Name must be entered.");
        return;
    }
    if(name.length < 2){
        isValid = false;
        alert("Name must have greater than 2 characters.");
        return;
    }
    if(name.includes(" ") == false){
        isValid = false;
        alert("Please write your full name.");
        return;
    }

    // age validations
    if(age == ""){
        isValid = false;
        alert("Age must be entered.");
        return;
    }
    if(age >= 0 && age <= 99){
        isValid = false;
        alert("Age must be between 0 and 99.");
        return;

    // email validations
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(email == ""){
        isValid = false;
        alert("Email must be entered.");
        return;
    }
    if(!emailRegex.test(email)){
        isValid = false;
        alert("Invalid email format.");
        return;
    }

    // course validations
    if(course == ""){
        isValid = false;
        alert("Course must be entered.");
        return;
    }
    if(course.length < 5){
        isValid = false;
        alert("Course must have greater than 5 characters.");
        return;
    }

    // year level validations
    if(year_level == ""){
        isValid = false;
        alert("Year Level must be entered.");
        return;
    }
    
    // avatar


    // graduate

     
    return isValid;
}