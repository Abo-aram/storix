 function adaptiveSize(Element, isExpanded ) {
        if (isExpanded) {
            Element.style.maxHeight = '8rem'; // Collapse
            
        
        } else {
            Element.style.maxHeight = folderList.scrollHeight + 'px'; // Expand

        }
        isExpanded = !isExpanded;
}