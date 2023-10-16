function setUserData(user){
    const dashboard = document.querySelector("#dashboard");
    
    dashboard.innerHTML = dashboard.innerHTML.replace('{{username}}' ,user.username);
    dashboard.innerHTML = dashboard.innerHTML.replace('{{firstname}}',user.firstname);
    dashboard.innerHTML = dashboard.innerHTML.replace('{{lastname}}' ,user.lastname);
    dashboard.innerHTML = dashboard.innerHTML.replace('{{email}}'    ,user.email);
}

function LogoutUser(){
    fetch('/api/logout.php')
    .finally(() => {
        window.location.href = 'index.html';
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const loading = document.querySelector("#loading");
    const dashboard = document.querySelector("#dashboard");
    
    fetch('/api/user.php')
    .then(response => response.json() )
    .then(data => {
        //everything is ok
        if(data.status.code == 200)
        {
            setUserData(data.data);
            loading.style.display = "none";
            dashboard.style.display = "block";
        }
        //there are errors to display
        else
        {
            alert(data.errors.join('<br/>'));
            window.location.href = 'index.html';
        }
    })
    .catch(error => {
        // an unexpected error has happening
        alert('We cannot validate your session.');
        window.location.href = 'index.html';
    });
    
});