const fs = require('fs');
export default function register() {
    return render();
}


function main()
{
    const registerButton = document.getElementById('register-button');
    if (registerButton) {
        registerButton.addEventListener('click', saveUser);
    }
}

function saveUser(user) {
    const usersFilePath = 'users.json';
    let usersData = { students: [] };
    const email = document.querySelector('input[name="email"]').value;
    const name = document.querySelector('input[name="name"]').value;
    const password = document.querySelector('input[name="password"]').value;


    if (email && name && password) {
        const newUser = { email, name, password };
        usersData.students.push(newUser);
        fs.writeFileSync(usersFilePath, JSON.stringify(usersData, null, 2));
        console.log('User registered successfully');
    }
}


function render() {
    const registerPage = `
<div>
<form id="register-form">
<label>Email:</label>
<input type="email" name="email" required>
<br>
<label>Name:</label>
<input type="name" name="name" required>
<br>
<label>Password:</label>
<input type="password" name="password" required>
<br>
<button type="submit" id="register-button">Register</button>
</form>
</div>      
`;

    return registerPage;
}

main();