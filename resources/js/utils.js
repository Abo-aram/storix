
window.messageToUser = function (show, message) {
    
    const AlpineData = document.querySelector(['[x-data]']);
   
    Alpine.$data(AlpineData).message = message;
     Alpine.$data(AlpineData).show = show;
}

window.getSelectedFolder = function () {
    const folderList = document.querySelector('#folderList');
    const folders = Array.from(folderList.children);
    for (const folder of folders)
    {
        if (folder.classList.contains('activeFolder')) {
            return folder.id;
        }
    }
    return null; // No folder selected
}

window.deleteFileFromDB = function (fileId) {
    
} 

