import './bootstrap';

document.addEventListener('DOMContentLoaded', function () { 

    const input = document.getElementById('search');       // Correct input ID
    const form = document.getElementById('fomr');
  
    

    let debounceTimer;
    if (input != null) {
        input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            form.submit();
        }, 300); // Delay to prevent overloading on fast typing
    });
    }

    

});
