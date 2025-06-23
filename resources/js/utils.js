
window.messageToUser = function (show, message) {
    
    const AlpineData = document.querySelector(['[x-data]']);
   
    Alpine.$data(AlpineData).message = message;
     Alpine.$data(AlpineData).show = show;
}