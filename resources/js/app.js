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
            formBtn.style.transform = "rotate(45deg)";
            formDiv.classList.remove("hidden");
            uploadDiv.style.maxHeight = uploadDiv.scrollHeight + "px";

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
                });
        } else {
            folderSelector.removec;
            formBtn.style.transform = "rotate(0deg)";
            uploadDiv.style.maxHeight = "6rem";
            folderSelector.innerHTML = ""; // Clear the folder options
            folderSelector.innerHTML =
                '<option value="Select Folder" selected>Select Folder</option>'; // Reset to default option
        }
        formExpanded = !formExpanded;
    });

    // ✅ Add event listener for folder selection
    folderSelector.addEventListener("change", (e) => {
        selectFolder.value = e.target.value;
    });

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

    const uploadForm = document.getElementById("uploadForm");
    uploadForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(uploadForm);
        let folderID = null;

        const selectedFolder =
            folderSelector.options[folderSelector.selectedIndex];

        if (folderSelector.value !== "Select Folder") {
            folderID = selectedFolder.id;
            formData.set("folder_id", folderID);
        }

        if (stored_name.innerText === " ") {
            fileName.innerText = fileInput.files[0].name;
        }

        try {
            fetch("http://127.0.0.1:8000/upload", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: formData,
            })
                .then((response) => {
                    if (!response.ok) {
                        console.error(
                            "Error uploading file:",
                            response.statusText
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    window.messageToUser(
                        true,
                        "✅ File uploaded successfully!"
                    );
                    // Reset the form
                    uploadForm.reset();
                    fileName.innerText = " ";
                    fileSize.innerText = " ";
                    hiddenElements.forEach((el) => el.classList.add("hidden"));
                    dropzone.classList.remove("hidden");
                    formDiv.classList.add("hidden");
                });
        } catch (error) {
            console.error("Error:", error);
        }
    });

    const sort = document.querySelector("#sort");
    const filter = document.querySelector("#filter");
    const search = document.querySelector("#search");
    const fileSection = document.querySelector("#fileSection");


    // ✅ Add event listener for search input with debounce
    let debounceTimer;
    if (input && form) {
        input.addEventListener("input", function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchFiles();
            }, 300);
        });
    }

    // ✅ fetch files from js instead of blade for better performance and to avoid reloading the page
    function fetchFiles() {
        const SelectedFolder = window.getSelectedFolder();
        console.log("Selected folder:", SelectedFolder);
        try {
            fetch("http://127.0.0.1:8000/home/getFiles", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    sort: sort.value,
                    type: filter.value,
                    folder_id: SelectedFolder,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    console.log("Files fetched successfully");
                    return response.json();
                })
                .then((data) => {
                    data.forEach((file) => {
                        const fileDiv = document.createElement("div");
                        fileDiv.id = file.id;
                        
                        fileDiv.className = "bg-white shadow-lg relative rounded-xl p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition fade-in";
                        // Check if image file
                        let mediaHTML = "";
                        if (["png", "jpg", "jpeg"].includes(file.extension)) {
                            mediaHTML = `<img src="/storage/${file.path}" alt="${file.original_name}" class="w-full h-32 object-cover rounded-lg mb-4">`;
                        } else {
                            mediaHTML = `
                                        <svg class="self-center" width="128" height="128" viewBox="0 0 128 128" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="128" height="128" rx="16" fill="url(#gradient)" />
                                        <path d="M76 32H44L32 44V96H96V32H76Z" fill="white" />
                                        <path d="M76 32V44H88L76 32Z" fill="#E3E3E3" />
                                        <path d="M40 56H88M40 68H88M40 80H72" stroke="#5E5E5E" stroke-width="4" stroke-linecap="round" />
                                        <defs>
                                            <linearGradient id="gradient" x1="64" y1="0" x2="64" y2="128" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#2196F3" />
                                            <stop offset="1" stop-color="#1976D2" />
                                            </linearGradient>
                                        </defs>
                                        </svg>`;
                        }

                        const sizeKB = (file.size / 1024).toFixed(2);

                        // File card inner HTML
                        fileDiv.innerHTML = `
                                        ${mediaHTML}

                                        <div>
                                        <h3 class="text-gray-700 font-semibold truncate">${
                                            file.stored_name
                                        }</h3>
                                        <p class="text-sm text-gray-500">Size: ${sizeKB} KB</p>
                                        <p class="text-sm text-gray-400">Uploaded: ${
                                            file.human_date || "Just now"
                                        }</p>
                                        </div>

                                        <div class="mt-4 flex justify-between items-center">
                                        <div class="relative inline-block text-left">
                                            <button class="dropdownBtn inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 focus:outline-none">
                                            Download
                                            </button>

                                            <div class="dropdownMenu hidden absolute z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                            <div class="py-1">
                                                <a href="/download/${
                                                    file.id
                                                }/false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                Download
                                                </a>
                                                <button onclick="requestURL(${
                                                    file.id
                                                })" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                Download Link
                                                </button>
                                            </div>
                                            </div>
                                        </div>

                                        <button

                                        <form class="deleteForm">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="${
                                                document.querySelector("meta[name=csrf-token]").content
                                            }">
                                            <button id="${file.id}" type="submit" class=" deleteBtn text-red-500 hover:text-red-700 text-sm">
                                            Delete
                                            </button>
                                        </form>
                                        </div>
                                    `;

                        
                        fileSection.appendChild(fileDiv);

                        setTimeout(() => {
                        fileDiv.classList.add("show");
                        }, 10);
                    });



                    document.querySelectorAll(".dropdownBtn").forEach((btn) => {
                        btn.addEventListener("click", (e) => {
                            const currentMenu = btn.nextElementSibling; // the .dropdownMenu

                            // Toggle this menu
                            const isHidden = currentMenu.classList.contains("hidden");

                            // Hide all dropdowns first
                            document.querySelectorAll(".dropdownMenu").forEach((menu) => {
                                if (menu !== currentMenu) {
                                    menu.classList.add("hidden");
                                }
                            });

                            // Then toggle current one (based on its previous state)
                            if (isHidden) {
                                currentMenu.classList.remove("hidden");
                            } else {
                                currentMenu.classList.add("hidden");
                            }

                            // Stop click from bubbling to document click handler (if you add one later)
                            e.stopPropagation();
                        });
                    });
                    document.querySelectorAll(".deleteBtn").forEach((btn) => {
                        btn.addEventListener("click", (e) => {
                            const fileDivs = Array.from(fileSection.children);
                            for (const fileDiv of fileDivs) {
                                if (fileDiv.id === btn.id) {
                                    fileDiv.classList.remove("show");
                                    fileDiv.classList.add("fade-out");

                                    setTimeout(() => {
                                        fileDiv.remove();
                                        
                                    }, 500);
                                    
                                    fetch(`http://127.0.0.1:8000/delete/${btn.id}`)
                                    // Adjust the timeout to match your CSS transition duration
                                }
                            }

                        });
                    });
                });
        } catch (error) {
            console.error("Error fetching files:", error);
        }
    }
    fetchFiles(); // Initial fetch on page load
});
