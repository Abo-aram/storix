import "./bootstrap";
import "./dashboard";

// ✅ Make requestURL globally accessible
window.requestURL = function (id) {
    // Check if the id is a valid number
    fetch(`http://127.0.0.1:8000/download/${id}/true`)
        .then((response) => {
            if (!response.ok) {
                alert("not found");
            } else {
                return response.text();
            }
        })
        .then((data) => {
            const blurDiv = document.getElementById("blurDiv");

            const popup = document.getElementById("popup");
            const downloadLink = document.getElementById("downloadLink");

            downloadLink.innerText = data;
            popup.classList.remove("hidden");
            popup.classList.add("flex");
            blurDiv.classList.add("blur-sm", "pointer-events-none");
            document.addEventListener("mousedown", handleOutSideClick);
        });
};

// ✅ Close popup and reset styles
window.closePopup = function () {
    const popup = document.getElementById("popup");
    const downloadLink = document.getElementById("downloadLink");
    const blurDiv = document.getElementById("blurDiv");
    let backdiv = document.querySelector(".turnGreen");
    backdiv.classList.remove("bg-green-300");
    backdiv.classList.add("bg-gray-300");
    downloadLink.innerText = "";
    popup.classList.add("hidden");
    popup.classList.remove("flex");
    blurDiv.classList.remove("blur-sm", "pointer-events-none");
    document.removeEventListener("mousedown", handleOutSideClick);
};

// ✅ Handle outside click to close popup
window.handleOutSideClick = function (event) {
    const popup = document.getElementById("popup");
    if (popup && !popup.contains(event.target)) {
        closePopup();
    }
};

// ✅ listen for loaded event on the document
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("search");
    const form = document.getElementById("fomr");
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
    const follderError = document.getElementById("FolderError");
    const stored_name = document.getElementById("stored_name");
    const sort = document.querySelector("#sort");
    const filter = document.querySelector("#filter");
    const search = document.querySelector("#search");
    const fileSection = document.querySelector("#fileSection");
    const loadedFilesId = [];
    const lastId = 0;
    const refreshBtn = document.querySelector("#refreshBtn");
    const fileDetails = document.querySelector("#fileDetails");
    const loadingDiv = document.createElement("div");
    const scrollable= document.querySelector(".scrollable");
    let scrollVariable = null;
    let numberOfFiles = 0;
    let availableFiles = 0;
    let isLoading = false;
    let lastScrollTime = 0;
    let isSearching = false;
    let isAMixedLoad = false;
    let isUploading = false;
    let previousSearchValue = "";
    let clear = false;
    let newfileAdded = false;

    // refreshBtn.addEventListener("click", () => {
    //     fetchFiles(loadedFilesId);
    // });


    

    follderError.classList.add("overflow-hidden");
    follderError.style.maxHeight = "0"; // Initial height
    follderError.style.transition = "max-height 0.3s ease-in-out";

    uploadDiv.style.overflow = "hidden";
    uploadDiv.style.transition = "max-height 0.3s ease-in-out";
    uploadDiv.style.maxHeight = "6rem"; // Initial height

    // ✅ Add event listener for dropdown buttons

    //Close dropdown if clicking outside
    document.addEventListener("click", function (event) {
        const isDropdown =
            event.target.closest(".dropdownMenu") ||
            event.target.closest(".dropdownBtn");
        if (!isDropdown) {
            document
                .querySelectorAll(".dropdownMenu")
                .forEach((menu) => menu.classList.add("hidden"));
        }
    });

    const copyBtn = document.getElementById("copyBtn");
    if (copyBtn) {
        copyBtn.addEventListener("click", () => {
            const text = document.getElementById("downloadLink").innerText;
            navigator.clipboard.writeText(text).then(() => {
                let backdiv = document.querySelector(".turnGreen");
                backdiv.classList.remove("bg-gray-300");
                backdiv.classList.add("bg-green-300");
            });
        });
    }

    // ✅ Add drag and drop functionality
    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropzone.classList.add("bg-gray-200", "border-gray-400");
    });

    // ✅ add event listener for dragleave
    dropzone.addEventListener("click", () => {
        fileInput.click();

        fileInput.addEventListener("change", (e) => {
            const file = fileInput.files[0];
            if (fileInput.files.length > 0) {
                fileName.innerText = " " + file.name;
                fileSize.innerText = ` ${(file.size / 1024 / 1025).toFixed(
                    2
                )} MB`;
                hiddenElements.forEach((el) => el.classList.remove("hidden"));
                dropzone.classList.add("hidden");
            }
        });
    });

    // ✅ Add event listener for drop
    formBtn.addEventListener("click", (e) => {
        if (!formExpanded) {
            hiddenElements.forEach((el) => el.classList.add("hidden"));
            formBtn.style.transform = "rotate(45deg)";
            dropzone.classList.remove("hidden");
            formDiv.classList.remove("hidden");
            uploadDiv.style.maxHeight = uploadDiv.scrollHeight + "px";
           // Set uploading state

            fetch("http://127.0.0.1:8000/getfolders")
                .then((response) => response.json())
                .then((data) => {
                    data.forEach((folder) => {
                        const option = document.createElement("option");
                        option.id = folder.id;
                        option.value = folder.name;
                        option.textContent = folder.name;
                        folderSelector.appendChild(option);
                    });
                    folderSelector.addEventListener("change", (e) => {
                        selectFolder.value = e.target.value;
                    });

                    window.syncFolderSelection();
                });
        } else {
            folderSelector.removec;

            formBtn.style.transform = "rotate(0deg)";
            uploadDiv.style.maxHeight = "6rem";
            folderSelector.innerHTML = ""; // Clear the folder options
            folderSelector.innerHTML =
                '<option value="Select Folder" selected>Select Folder</option>';
            selectFolder.value = ""; // Reset to default option
        }
        
        formExpanded = !formExpanded;
    });

    // ✅ Add event listener for folder selection

    // synchronize the selectFolder input with the folderSelector dropdown
    selectFolder.addEventListener("input", (e) => {
        const text = e.target.value;
        const options = Array.from(folderSelector.options);

        for (let option of options) {
            if (option.value !== text && text !== "") {
                follderError.style.maxHeight = "1rem";
                follderError.classList.remove("overflow-hidden");
                follderError.classList.add("border-red-500", "border");
            } else {
                follderError.style.maxHeight = "0";
                follderError.classList.add("overflow-hidden");
                follderError.classList.remove("border-red-500", "border");
                folderSelector.value = option.value;

                break; // STOP the loop here!
            }
        }
    });

    // ✅ Add event listener for upload form submission

    // ✅ fetch files from js instead of blade for better performance and to avoid reloading the page
    async function loadFiles() {
        if (isLoading) return; // Prevent multiple simultaneous loads
        isLoading = true; // Set loading state

  
        
      

        numberOfFiles = await fetchFiles(loadedFilesId, newfileAdded, isAMixedLoad, clear);

        availableFiles = localStorage.getItem("FilesBeforeLimit");
       

        isLoading = false; // Reset loading state
        isAMixedLoad = false; // Reset mixed load state
        clear = false; // Reset clear state
        isSearching = false; // Reset searching state
        newfileAdded = false; // Reset new file added state
    }

    const uploadForm = document.getElementById("uploadForm");

uploadForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(uploadForm);

    
    

    let folderID = null;
    isAMixedLoad = true; // Set uploading state

    const selectedFolder = folderSelector.options[folderSelector.selectedIndex];
    if (folderSelector.value !== "Select Folder") {
        folderID = selectedFolder.id;
        formData.set("folder_id", folderID);
    }

    if (stored_name.innerText === "") {
        fileName.innerText = fileInput.files[0].name;
    }



    const xhr = new XMLHttpRequest();
    xhr.open("POST", "http://127.0.0.1:8000/upload");

    // CSRF Token header
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    );

    // Handle progress bar update
    const loadingDiv = document.createElement("div");
    xhr.upload.addEventListener("loadstart", function (event) {
        if (event.lengthComputable) {
                loadingDiv.innerHTML = `<div id="uploadCard" class="bg-white shadow-lg relative z-10 rounded-xl p-4 flex flex-col justify-end items-center border border-gray-200 h-[296px] overflow-hidden">
  
                <!-- Progress bar container -->
                <div class="w-12 h-40 relative bg-gray-200 rounded overflow-hidden mb-8">
                    <!-- Filling progress -->
                    <div id="progressBar"
                        class="absolute bottom-0 left-0 w-full bg-blue-600 transition-all duration-300 ease-out"
                        style="height: 0%;">
                    </div>

                </div>

                

                <!-- Status text -->
                <p id="uploadStatus" class="text-gray-500 text-xs mb-4"></p>

                </div>
                `;
                //add loading div to the file section in the top
            fileSection.insertBefore(loadingDiv, fileSection.firstChild);
            

            formBtn.style.transform = "rotate(0deg)";
            uploadDiv.style.maxHeight = "6rem";
            formExpanded = !formExpanded;

            folderSelector.innerHTML =
                '<option value="Select Folder" selected>Select Folder</option>';
            uploadForm.reset();
            fileName.innerText = "";
            fileSize.innerText = "";
            fileInput.value = "";
        }
    });

    xhr.upload.addEventListener("progress", function (event) {
            const progressBar = loadingDiv.querySelector("#progressBar");
            const uploadStatus = loadingDiv.querySelector("#uploadStatus");

            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 100);

                // Fill the vertical progress bar
                progressBar.style.width = percent + "%";

                // Update text status
                uploadStatus.textContent = `Uploading: ${percent}%`;
            }
});



    // Handle success
    xhr.onload = function () {
        if (xhr.status === 200 || xhr.status === 201) {
            window.messageToUser(true, "✅ File uploaded successfully!");
            //remove loading div with fade-out animatino
            setTimeout(() => {
                loadingDiv.remove();
                loadingDiv.classList.add("fade-out")
            }, 400);


            
            

   

            newfileAdded = true;
            loadFiles().then(() => { 
                window.removeEventListener("scroll", scrollHandler);
                window.addEventListener("scroll", scrollHandler);
            })
        } else {
            uploadStatus.textContent = "❌ Upload failed";
            console.error("Upload error:", xhr.statusText);
        }
    };

    xhr.onerror = function () {
        uploadStatus.textContent = "❌ Upload failed (network)";
        console.error("Network error during upload");
    };

    xhr.send(formData);
});


    // ✅ Add event listener for search input with debounce

    let debounceTimer;

    search.addEventListener("input", function () {
      
         
        
        if (search.value != "") {
            
           

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (search.value != "") {
                    window.scrollTo({ top: 0, behavior: "smooth" });
                }
               
                isSearching = true; // Set searching state
                loadFiles();

                
            }, 300);
        } else {
            if (previousSearchValue != "") {
                clear = true; // Set clear state
            }
            
            isSearching = false; // Reset searching state
            loadFiles();
        }
        previousSearchValue = search.value; // Store the current search value
    });


    const scrollHandler = () => {
        

        if (availableFiles <= 12 || isLoading) {
            console.log("No more files to load or currently loading or loading");

            return;
        }
        if (isSearching) isAMixedLoad = true;

        const now = Date.now();
        if (now - lastScrollTime < 2000) return;
        const scrollTop = scrollable.scrollTop;
        const containerHeight = scrollable.clientHeight;
        const scrollHeight = scrollable.scrollHeight;

        

        if (loadedFilesId.length != numberOfFiles) {
            
            

            if (scrollTop + containerHeight >= scrollHeight - 10)
 {
                 addLoading(fileSection);
                lastScrollTime = now;
                loadFiles().then(() => { 
                    addLoading(fileSection);
                    
                });
            }
        }

    };

    function addLoading(element) {
        console.log('loading cirlce')

       
        const parent = element.parentNode;



        if (element && !parent.querySelector(".loading-circle")) {
            const newElement = document.createElement("div");
            newElement.className = "loading-circle";
            element.parentNode.insertBefore(
                newElement,
                element.nextSibling
            );
        } else {
            const loadingCircle = parent.querySelector(".loading-circle")
            if (loadingCircle) {
                loadingCircle.classList.add("fade-out");
                setTimeout(() => {
                    loadingCircle.remove();
                    
                }, 800);
              
            }
        }
    }


        

    

    loadFiles().then(() => {

        scrollable.addEventListener('scroll', scrollHandler)
    });
    








});
