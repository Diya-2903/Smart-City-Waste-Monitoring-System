
const form = document.querySelector('form');
const nameInput = document.getElementById('name');
const mobile = document.getElementById('mobile');
const file = document.getElementById('file');
const error = document.getElementById('error');

if (form) {
    form.addEventListener('submit', (e) => {
        let msg = [];
        var letters = /^[A-Za-z\s]+$/;
        
        if(nameInput.value == '' || nameInput.value == null) {
            msg.push('name is required');
        }
        if(!letters.test(nameInput.value)) {
          msg.push('Invalid name format. use letters');
        }

        const ld = document.getElementById('locationdescription');
        if(!ld || ld.value == '' || ld.value == null) {
            msg.push('Location Description is required');
        }

        if(mobile.value.length < 8 || mobile.value.length > 15) {
            msg.push('Invalid mobile number');
        }
        if(mobile.value == '' || mobile.value == null )  {
            msg.push('mobile Number is required');
        }

        if (msg.length > 0) {
            e.preventDefault();
            error.innerText = msg.join(', ');
            error.style.color = 'red';
            window.scrollTo(0, 0);
        }
    });
}