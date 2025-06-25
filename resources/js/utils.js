window.messageToUser = function (show, message) {
    const AlpineData = document.querySelector(["[x-data]"]);

    Alpine.$data(AlpineData).message = message;
    Alpine.$data(AlpineData).show = show;
};

window.getSelectedFolder = function () {
    const folderList = document.querySelector("#folderList");
    const folders = Array.from(folderList.children);
    for (const folder of folders) {
        if (folder.classList.contains("activeFolder")) {
            return folder.id;
        }
    }
    return null; // No folder selected
};

window.fetchFiles = function (loadedFilesId, newFileAdded = false) {
    const SelectedFolder = window.getSelectedFolder();
    if (sort.value !== "" || filter.value !== "" || search.value !== "" || SelectedFolder !== null) {
        loadedFilesId = [];
        fileSection.innerHTML = ""; // Clear existing files
    }
    
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
                search: search.value,
                folder_id: SelectedFolder,
                loadedfilesId: loadedFilesId,
                newFileAdded: newFileAdded,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }

                return response.json();
            })
            .then((data) => {
                if (!newFileAdded) {
                    data.forEach((file) => {
                        loadedFilesId.push(file.id);

                        const fileDiv = document.createElement("div");
                        fileDiv.id = file.id;

                        fileDiv.className =
                            "bg-white shadow-lg relative z-10 rounded-xl p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition fade-in";
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

                        const sizeKB = ` ${(file.size / 1024 / 1025).toFixed(
                            2
                        )} MB`;

                        // File card inner HTML
                        fileDiv.innerHTML = `
                                        ${mediaHTML}

                                        <div>
                                        <h3 class="text-gray-700 font-semibold truncate">${
                                            file.stored_name
                                        }</h3>
                                        <p class="text-sm text-gray-500">Size: ${sizeKB} </p>
                                        <p class="text-sm text-gray-400">Uploaded: ${
                                            file.human_date || "Just now"
                                        }</p>
                                        </div>

                                        <div class="mt-4 flex justify-between items-center relative z-20">
                                        <div class="relative inline-block text-left z-30">
                                            <button id="${
                                                file.id
                                            }" class="dropdownBtn inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 focus:outline-none">
                                            Download
                                            </button>

                                            <div id="menu${
                                                file.id
                                            }" class="dropdownMenu hidden absolute z-50 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
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
                                                document.querySelector(
                                                    "meta[name=csrf-token]"
                                                ).content
                                            }">
                                            <button id="${
                                                file.id
                                            }" type="submit" class=" deleteBtn text-red-500 hover:text-red-700 text-sm">
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
                } else {
                    loadedFilesId.push(data.id);

                    const fileDiv = document.createElement("div");
                    fileDiv.id = data.id;

                    fileDiv.className =
                        "bg-white shadow-lg relative z-10 rounded-xl p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition fade-in";
                    // Check if image file
                    let mediaHTML = "";
                    if (["png", "jpg", "jpeg"].includes(data.extension)) {
                        mediaHTML = `<img src="/storage/${data.path}" alt="${data.original_name}" class="w-full h-32 object-cover rounded-lg mb-4">`;
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

                    const sizeKB = (data.size / 1024).toFixed(2);

                    // File card inner HTML
                    fileDiv.innerHTML = `
                                        ${mediaHTML}

                                        <div>
                                        <h3 class="text-gray-700 font-semibold truncate">${
                                            data.stored_name
                                        }</h3>
                                        <p class="text-sm text-gray-500">Size: ${sizeKB} </p>
                                        <p class="text-sm text-gray-400">Uploaded: ${
                                            data.human_date || "Just now"
                                        }</p>
                                        </div>

                                        <div class="mt-4 flex justify-between items-center relative z-20">
                                        <div class="relative inline-block text-left z-30">
                                            <button id="${
                                                data.id
                                            }" class="dropdownBtn inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 focus:outline-none">
                                            Download
                                            </button>

                                            <div id="menu${
                                                data.id
                                            }" class="dropdownMenu hidden absolute z-50 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                            <div class="py-1">
                                                <a href="/download/${
                                                    data.id
                                                }/false" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                Download
                                                </a>
                                                <button onclick="requestURL(${
                                                    data.id
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
                                                document.querySelector(
                                                    "meta[name=csrf-token]"
                                                ).content
                                            }">
                                            <button id="${
                                                data.id
                                            }" type="submit" class=" deleteBtn text-red-500 hover:text-red-700 text-sm">
                                            Delete
                                            </button>
                                        </form>
                                        </div>
                                    `;

                    // Append the new fileDiv to the fileSection on top
                    fileSection.insertBefore(fileDiv, fileSection.firstChild);
                    document
                        .querySelector(`#${CSS.escape(data.id)}`)
                        .addEventListener("click", (e) => {
                            window.menuListners(e.target);
                        });

                    setTimeout(() => {
                        fileDiv.classList.add("show");
                    }, 10);
                }

                if (!newFileAdded) {
                    document.querySelectorAll(".dropdownBtn").forEach((btn) => {
                        menuListners(btn);
                    });
                }
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
                                deleteFile(btn.id);
                                break; // Stop the loop after finding the matching fileDiv
                                // Adjust the timeout to match your CSS transition duration
                            }
                        }
                    });
                });
            });
    } catch (error) {
        console.error("Error fetching files:", error);
    }
};

window.deleteFile = function (fileId) {
    try {
        fetch("http://127.0.0.1:8000/delete", {
            method: "post",
            headers: {
                "Content-Type": "application/json", // <- important for JSON body
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                id: fileId,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    console.error("Error deleting file:", response.message);
                }
                return response.json();
            })
            .then((data) => {
                window.messageToUser(true, "✅ File deleted successfully!");
                // 👇 Optional: animate and remove the fileDiv
                const fileDiv = btn.closest(".file-card"); // adjust selector
                if (fileDiv) {
                    fileDiv.classList.remove("fade-in", "show");
                    fileDiv.classList.add("fade-out");

                    fileDiv.addEventListener("transitionend", () => {
                        fileDiv.remove();
                    });
                }
            });
    } catch (error) {
        console.log("Error deleting file:", error.message);
    }
};

window.menuListners = function (btn) {
    btn.addEventListener("click", (e) => {
        const card = document.querySelector(`#${CSS.escape(btn.id)}`);
        card.style.zIndex = "50"; // Ensure dropdowns are above other content

        const currentMenu = btn.nextElementSibling; // the .dropdownMenu

        // Toggle this menu
        const isHidden = currentMenu.classList.contains("hidden");

        // Hide all dropdowns first
        document.querySelectorAll(".dropdownMenu").forEach((menu) => {
            if (menu !== currentMenu && !menu.classList.contains("hidden")) {
                menu.classList.add("hidden");
            }
        });

        // Then toggle current one (based on its previous state)
        currentMenu.classList.toggle("hidden", !isHidden);

        // Stop click from bubbling to document click handler (if you add one later)
        e.stopPropagation();
    });
};

window.syncFolderSelection = function () {
    console.log("Syncing folder selection...");
    
    let options = Array.from(folderSelector.children);
    const selectedFolder = window.getSelectedFolder();
 
   
    options.forEach((option) => {
    
        if (option.id === selectedFolder) {
            option.selected = true;
            selectFolder.value = folderSelector.value ;
        }
    });
}
