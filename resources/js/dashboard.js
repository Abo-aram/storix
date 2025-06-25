import './utils.js';
document.addEventListener('DOMContentLoaded', function () {

    const moreBtn = document.querySelector('#moreFoldersBtn');
    const folderList = document.querySelector('#folderList');
    const userName = document.querySelector('#userName');
    const addFolderBtn = document.getElementById("addFolderBtn");
    const folderDiv = document.querySelector('#folderDiv');
    



    fetch('http://127.0.0.1:8000/getfolders')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            let isActiveFolder = false;
            data.forEach(folder => {
                const newFolder = document.createElement('li');
                newFolder.id = folder.id; // Assuming each folder has a unique ID
                newFolder.textContent = folder.name;
                newFolder.classList.add('bg-gray-700', 'p-1', 'rounded-lg','border-2', 'border-gray-700');
                newFolder.addEventListener('click', function () { 
                    
                    
                    const folders = Array.from(folderList.children);
                    folders.forEach(f => {
                        f.classList.remove('activeFolder');
                        f.classList.add('bg-gray-700');
                    });
                    
                    newFolder.classList.add('activeFolder');
                    if(newFolder.classList.contains('activeFolder')) {
                        isActiveFolder = true;
                        syncFolderSelection();
                    }
                })
                folderList.appendChild(newFolder);

            })
        })

    

    fetch('http://127.0.0.1:8000/dashboard/userName')
        .then(response => response.text())
        .then(data => {
            userName.append(" ", data);
        });

    // Apply transition styling once
    folderList.style.overflow = 'hidden';
    folderList.style.transition = 'max-height 0.3s ease-in-out';
    folderList.style.maxHeight = '8.5rem';
    
    moreBtn.style.transition = 'transform 0.3s ease-in-out';


    let isExpanded = false;

    moreBtn.addEventListener('click', function () {
        if (isExpanded) {
            folderList.style.maxHeight = '8.5rem';
            moreBtn.style.transform = 'rotate(0deg)';
            moreBtn.classList.add('bg-white');
            moreBtn.classList.remove('bg-gray-400');
             // Rotate back
        } else {
            moreBtn.style.transform = 'rotate(180deg)';
            moreBtn.classList.remove('bg-white');
            moreBtn.classList.add('bg-gray-400');
            folderList.style.maxHeight = folderList.scrollHeight + 'px'; // Expand

        }
        isExpanded = !isExpanded;
    });



   
 
    
    
    addFolderBtn.addEventListener('click', function () {


        const folderInput = document.createElement("input");
        folderInput.type = "text";
        folderInput.classList.add('bg-gray-700','p-1','rounded-lg');
       
        folderList.insertBefore(folderInput, folderList.firstChild);
        folderInput.focus();
        folderInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const newFolder = document.createElement('li');
                newFolder.textContent = folderInput.value;
                newFolder.classList.add('bg-gray-700', 'p-1', 'rounded-lg', 'border-2', 'border-gray-700');
                folderList.insertBefore(newFolder, folderList.firstChild);
                newFolder.addEventListener('click', function () { 
                    
                    
                    const folders = Array.from(folderList.children);
                    folders.forEach(f => {
                        f.classList.remove('activeFolder');
                        
                    });
                    
                    newFolder.classList.add('activeFolder');
                })
              
                
                // Send the new folder name to the server
                
                fetch('http://127.0.0.1:8000/createfolder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    stored_name: folderInput.value,
                    parent_id: null // or use a valid folder ID
                })
                })
                .then(response => {
                    if (!response.ok) {
                    // If validation or server error
                    return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data.message);
                    window.messageToUser(true, "Folder created successfully!");
                })
                .catch(error => {
                    console.error('Error creating folder:', error);
                    if (error.errors) {
                    // Laravel validation errors
                    console.log(error.errors);
                    }
                });


                
                folderInput.remove(); // Remove the input field after adding the folder





            }
        });
        
    })






});
