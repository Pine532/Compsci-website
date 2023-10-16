function setFormMessage(formElement, type, message) {
    const messageElement = formElement.querySelector(".form__message");

    messageElement.textContent = message;
    messageElement.classList.remove("form__message--success", "form__message--error");
    messageElement.classList.add(`form__message--${type}`);
}

function setInputError(inputElement, message) {
    inputElement.classList.add("form__input--error");
    inputElement.parentElement.querySelector(".form__input-error-message").textContent = message;
}

function clearInputError(inputElement) {
    inputElement.classList.remove("form__input--error");
    inputElement.parentElement.querySelector(".form__input-error-message").textContent = "";
}

document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.querySelector("#login");
    const createAccountForm = document.querySelector("#createAccount");
    
    //check if the user is already in session
    fetch('/api/user.php')
    .then(response => response.json() )
    .then(data => {
        //user is already in session
        if(data.status.code == 200)
        {
            window.location.href = 'dashboard.html';
        }
    })
    
    document.querySelector("#linkCreateAccount").addEventListener("click", e => {
        e.preventDefault();
        loginForm.classList.add("form--hidden");
        createAccountForm.classList.remove("form--hidden");
    });

    document.querySelector("#linkLogin").addEventListener("click", e => {
        e.preventDefault();
        loginForm.classList.remove("form--hidden");
        createAccountForm.classList.add("form--hidden");
    });
    loginForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);

        fetch('/api/login.php', {method: 'POST', body: formData})
        .then(response => response.json() )
        .then(data => {
            //everything is ok
            if(data.status.code == 200)
            {
                setFormMessage(loginForm, "success", data.status.message);
                setTimeout(function () {
                    window.location.href = 'dashboard.html';
                }, 1200);
            }
            //there are errors to display
            else
            {
                setFormMessage(createAccountForm, "error", data.errors.join('<br/>'));
                // if (Array.isArray(data.errors)) {
                    // setFormMessage(loginForm, "error", data.errors.join('<br/>'));
                // } else {
                    // setFormMessage(loginForm, "error", "An unexpected error occurred. Please try again later.");
                // }
            }
        })
        .catch(error => {
            //an unexpected error has happening
            setFormMessage(loginForm, "error", 'There is an error on the comunication, please try again and if problem persist contact to the support team.');
        });
    });
	const form = document.getElementById('createAccount');
	form.addEventListener('submit', async (event) => {
		event.preventDefault();

		const formData = new FormData(event.target);
        
        fetch('/api/register.php', {method: 'POST', body: formData})
        .then(response => response.json() )
        .then(data => {
            //everything is ok
            if(data.status.code == 200)
            {
                setFormMessage(createAccountForm, "success", data.status.message);
                setTimeout(function () {
                    window.location.href = 'index.html';
                }, 1200);
            }
            //there are errors to display
            else
            {
                setFormMessage(createAccountForm, "error", data.errors.join('<br/>'));
            }
        })
        .catch(error => {
            //an unexpected error has happening
            setFormMessage(createAccountForm, "error", 'There is an error on the comunication, please try again and if problem persist contact to the support team.');
        });
    });

      document.querySelectorAll(".form__input").forEach(inputElement => {
        inputElement.addEventListener("blur", e => {
            if (e.target.id === "signupUsername" && e.target.value.length > 0 && e.target.value.length < 4) {
                setInputError(inputElement, "Username must be at least 4 characters in length");
            }
        });

        inputElement.addEventListener("input", e => {
            clearInputError(inputElement);
        });
    });
});