import './bootstrap';
import './dashboard';

// ✅ Make requestURL globally accessible
window.requestURL = function(id) {



    fetch(`http://127.0.0.1:8000/download/${id}/true`)
        .then(response => {
            if (!response.ok) {
                alert('not found');
            } else {
                return response.text();
            }
        })
        .then(data => {
            const blurDiv = document.getElementById('blurDiv');
            
            const popup = document.getElementById('popup');
            const downloadLink = document.getElementById('downloadLink');

            downloadLink.innerText = data;
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            blurDiv.classList.add('blur-sm', 'pointer-events-none');
            document.addEventListener('mousedown', handleOutSideClick);
        });
};

window.closePopup = function () {

    const popup = document.getElementById('popup');
    const downloadLink = document.getElementById('downloadLink');
    const blurDiv = document.getElementById('blurDiv');
    let backdiv = document.querySelector('.turnGreen');
    backdiv.classList.remove('bg-green-300');
    backdiv.classList.add('bg-gray-300');
    downloadLink.innerText = '';
    popup.classList.add('hidden');
    popup.classList.remove('flex');
    blurDiv.classList.remove('blur-sm', 'pointer-events-none');
    document.removeEventListener('mousedown', handleOutSideClick);
    

};


window.handleOutSideClick = function (event) {
    const popup = document.getElementById('popup');
    if (popup && !popup.contains(event.target)) {
        closePopup();
    }
}



document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const form = document.getElementById('fomr'); // ⛔️ Probably a typo — should be 'form'

    let debounceTimer;
    if (input && form) {
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                form.submit();
            }, 300);
        });
    }



    const dropdownBtn = document.querySelectorAll('.dropdownBtn');
    const dropdownMenu = document.querySelectorAll('.dropdownMenu');

    document.querySelectorAll('.dropdownBtn').forEach((btn) => {
    btn.addEventListener('click', (e) => {
        const currentMenu = btn.nextElementSibling; // the .dropdownMenu

        // Toggle this menu
        const isHidden = currentMenu.classList.contains('hidden');

        // Hide all dropdowns first
        document.querySelectorAll('.dropdownMenu').forEach(menu => {
            if (menu !== currentMenu) {
                menu.classList.add('hidden');
            }
        });

        // Then toggle current one (based on its previous state)
        if (isHidden) {
            currentMenu.classList.remove('hidden');
        } else {
            currentMenu.classList.add('hidden');
        }

        // Stop click from bubbling to document click handler (if you add one later)
        e.stopPropagation();
    });
});

// Optional: Close dropdown if clicking outside
document.addEventListener('click', function (event) {
    const isDropdown = event.target.closest('.dropdownMenu') || event.target.closest('.dropdownBtn');
    if (!isDropdown) {
        document.querySelectorAll('.dropdownMenu').forEach(menu => menu.classList.add('hidden'));
    }
});


    

    const copyBtn = document.getElementById('copyBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const text = document.getElementById('downloadLink').innerText;
            navigator.clipboard.writeText(text).then(() => {
                
                let backdiv = document.querySelector('.turnGreen');
                backdiv.classList.remove('bg-gray-300');
                backdiv.classList.add('bg-green-300');
            });
        });
    }

    

  const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const fileList = document.getElementById("fileList");
    const hiddenElements = document.querySelectorAll(".hiddeBeforSelect");
    const fileName = document.getElementById("fileName");
    const fileSize = document.getElementById("fileSize");
    const formBtn = document.getElementById("formBtn");
    const formDiv = document.getElementById("formDiv");
    const uploadDiv = document.getElementById("uploadDiv");
    const folderSelector = document.getElementById("folderSelector");
    const selectFolder = document.getElementById("selectFolder");
    let formExpanded = false;


    uploadDiv.style.overflow = 'hidden';
    uploadDiv.style.transition = 'max-height 0.3s ease-in-out';
    uploadDiv.style.maxHeight = '6rem'; // Initial height
   




    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("bg-gray-200", "border-gray-400");
    })



    dropzone.addEventListener("click", () => {
        fileInput.click();

        fileInput.addEventListener("change", (e) => {
            if (fileInput.files.length > 0) { 
                const file = fileInput.files[0];
                fileName.append = file.name;
                fileSize.append = `${(file.size / 1024).toFixed(2)} KB`; 
                hiddenElements.forEach(el => el.classList.remove("hidden"));
                dropzone.classList.add("hidden");
                
            }
        })
    })


    formBtn.addEventListener("click", (e) => {
        if (!formExpanded) {
            formBtn.style.transform = 'rotate(45deg)';
            formDiv.classList.remove('hidden');
            uploadDiv.style.maxHeight = uploadDiv.scrollHeight + 'px';
            
        } else {
            formBtn.style.transform = 'rotate(0deg)';
            uploadDiv.style.maxHeight = '6rem';
        }
        formExpanded = !formExpanded;
        console.log(formExpanded);
       

    })

    folderSelector.addEventListener("change", (e) => {
        selectFolder.value = e.target.value;
        
    });






});
