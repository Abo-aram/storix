document.addEventListener('DOMContentLoaded', function () {
    const moreBtn = document.querySelector('#moreFoldersBtn');
    const folderList = document.querySelector('#folderList');
    const userName = document.querySelector('#userName');

    fetch('http://127.0.0.1:8000/dashboard/userName')
        .then(response => response.text())
        .then(data => {
            userName.append(" ", data);
        });

    // Apply transition styling once
    folderList.style.overflow = 'hidden';
    folderList.style.transition = 'max-height 0.3s ease-in-out';
    folderList.style.maxHeight = '8rem';
    
    moreBtn.style.transition = 'transform 0.3s ease-in-out';


    let isExpanded = false;

    moreBtn.addEventListener('click', function () {
        if (isExpanded) {
            folderList.style.maxHeight = '8rem';
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

    const addFolderBtn = document.getElementById("addFolderBtn");


 
    
    
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
                newFolder.classList.add('bg-gray-700','p-1','rounded-lg');
                folderList.insertBefore(newFolder, folderList.firstChild);
                folderInput.remove();
                
            }
        });
        
    })




});
