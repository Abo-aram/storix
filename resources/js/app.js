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


    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownBtn.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    function downloadFile(type) {
        alert('Pretending to download ' + type);
        // Replace with real logic, like:
        // window.location.href = `/download/${type}`;
    }

    

    document.getElementById('copyBtn').addEventListener('click', () => {
        const text = document.getElementById("downloadLink").innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert("copied to clipboard: ");
        });
        
    });
    
    
});