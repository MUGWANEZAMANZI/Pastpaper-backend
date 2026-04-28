import register from './register.js';
const guestPage= `
<div>
<form id="login-form">
<label>Email:</label>
<input type="email" name="email" required>
<br>
<label>Password:</label>
<input type="password" name="password" required>
<br>
<button type="submit">Login</button>
<button type="button" id="register-button">Register</button>
</form>
</div>      
`;

const memberPage = `
<div>You are logged in</div>
<button id="logout">Logout</button>
`;

let isLoggedIn = false;
const page = isLoggedIn ? memberPage : guestPage;

document.addEventListener('DOMContentLoaded', function() {
  const mainElement = document.getElementById('main');
  if (mainElement) {
    mainElement.innerHTML = page;
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
      loginForm.addEventListener('submit', logIn); 
    }
    const registerButton = document.getElementById('register-button');
    if (registerButton) {
      registerButton.addEventListener('click', registration);
    }
  }
});

async function logIn(event){
  if (event) {
    event.preventDefault();
  }
  const emailInput = document.querySelector('input[name="email"]');
  const passwordInput = document.querySelector('input[name="password"]');

  if (!emailInput || !passwordInput) {
    return;
  }

  const email = emailInput.value;
  const password = passwordInput.value;

  try{
  const response = await fetch("users.json");
  const users = await response.json();
  const user = users.students.find(user => user.email === email && user.password === password);
  if (user) {
    isLoggedIn = true;
    document.getElementById('main').innerHTML = memberPage;
  } else {
    console.log("Invalid email or password");
  }
  console.log(users);
  }catch(e){
    console.log(e);
  }
  
}

function registration()
{
  const registerPage = register();
  const mainElement = document.getElementById('main');
  if (mainElement) {
    mainElement.innerHTML = registerPage;
  }
}