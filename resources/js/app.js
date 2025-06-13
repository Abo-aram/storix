import './bootstrap';
import './dashboard';


    function showToast(message) {
        const checkAlpine = setInterval(() => {
            const toast = document.getElementById('toast');
            if (toast && toast.__x) {
                clearInterval(checkAlpine);
                toast.__x.$data.message = message;
                toast.__x.$data.show = true;
            }
        }, 50);
    }


// ✅ Make requestURL globally accessible
window.requestURL = function(id) {



    // Check if the id is a valid number
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

// ✅ Close popup and reset styles
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

// ✅ Handle outside click to close popup
window.handleOutSideClick = function (event) {
    const popup = document.getElementById('popup');
    if (popup && !popup.contains(event.target)) {
        closePopup();
    }
}


// ✅ listen for loaded event on the document
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const form = document.getElementById('fomr'); 
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
    let  formExpanded = false;
    const dropdownBtn = document.querySelectorAll('.dropdownBtn');
    const dropdownMenu = document.querySelectorAll('.dropdownMenu');
    const follderError = document.getElementById("FolderError");
    const fileDetailsDiv = document.getElementById("fileDetailsDiv");
    const stored_name = document.getElementById("stored_name");

    follderError.classList.add("overflow-hidden");
    follderError.style.maxHeight = '0'; // Initial height
    follderError.style.transition = 'max-height 0.3s ease-in-out';

    uploadDiv.style.overflow = 'hidden';
    uploadDiv.style.transition = 'max-height 0.3s ease-in-out';
    uploadDiv.style.maxHeight = '6rem'; // Initial height
   

     
        showToast("✅ File uploaded successfully!");



    // ✅ Add event listener for search input with debounce
    let debounceTimer;
    if (input && form) {
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                form.submit();
            }, 300);
        });
    }



    // ✅ Add event listener for dropdown buttons
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

//Close dropdown if clicking outside
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

    

 


    



    // ✅ Add drag and drop functionality
    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("bg-gray-200", "border-gray-400");
    })



    // ✅ add event listener for dragleave
    dropzone.addEventListener("click", () => {




        fileInput.click();

        fileInput.addEventListener("change", (e) => {
            const file = fileInput.files[0];
            if (fileInput.files.length > 0) { 
               
              
                fileName.innerText =" "+file.name;
                fileSize.innerText = ` ${((file.size / 1024)/1025).toFixed(2)} MB`; 
                hiddenElements.forEach(el => el.classList.remove("hidden"));
                dropzone.classList.add("hidden");
                
            }
        })
    })

    

    // ✅ Add event listener for drop
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
      


              fetch('http://127.0.0.1:8000/getfolders')
            .then(response => response.json())
            .then(data => {
                
                data.forEach(folder => {
                   
                    const option = document.createElement("option");
                    option.id = folder.id;
                    option.value = folder.name;
                    option.textContent = folder.name;
                    folderSelector.appendChild(option);

                })
                
            }
            )
            
    })


    // ✅ Add event listener for folder selection
    folderSelector.addEventListener("change", (e) => {
        selectFolder.value = e.target.value;
        
    });


    // synchronize the selectFolder input with the folderSelector dropdown
    selectFolder.addEventListener("input", (e) => { 
        
        const text = e.target.value;
        const options =Array.from( folderSelector.options);
        
        options.forEach(option => {
            if (option.value !== text && text !== "") {
                
                follderError.style.maxHeight = '1rem';
                follderError.classList.remove("overflow-hidden");
                follderError.classList.add("border-red-500", "border");

                
            }
            else {
                follderError.style.maxHeight = '0';
                follderError.classList.add("overflow-hidden");
                follderError.classList.remove("border-red-500", "border");
                folderSelector.value =option.value;
                
            }
        })


    });



    // ✅ Add event listener for upload form submission

    const uploadForm = document.getElementById("uploadForm");
    uploadForm.addEventListener("submit", (e) => {
        e.preventDefault();
          const formData = new FormData(uploadForm);
        let folderID = null;
        
        const selectedFolder = folderSelector.options[folderSelector.selectedIndex];
        
        if (folderSelector.value !== "Select Folder") {
            console.log("Selected fodler:");
            folderID = selectedFolder.id;
        }
        
        if (stored_name.innerText === " ") {
            console.log("Stored name is empty");
            fileName.innerText = fileInput.files[0].name;
        }
            
         alert("File is uploading, please wait...");

      
        fetch('http://127.0.0.1:8000/upload', {
            method: 'POST',
            headers: {
                
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    console.error('Error uploading file:', response.statusText);
                }
                return response.json();
            })
            .then(data => {
                const AlpineData = document.querySelector(['[x-data]']);
                Alpine.$data(AlpineData).show =true;
                Alpine.$data(AlpineData).message = "✅ File uploaded successfully!";
                // Reset the form
                uploadForm.reset();
                fileName.innerText = " ";
                fileSize.innerText = " ";
                hiddenElements.forEach(el => el.classList.add("hidden"));
                dropzone.classList.remove("hidden");
                formDiv.classList.add('hidden');        
                
            })

    })






});
