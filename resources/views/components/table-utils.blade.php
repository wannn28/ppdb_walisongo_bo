@props(['sortVarName' => 'sortBy', 'directionVarName' => 'sortDirection'])

<script>
/**
 * Updates the sort indicators in the table headers
 * @param {string} sortBy - The column to sort by
 * @param {string} sortDirection - The sort direction ('asc' or 'desc')
 */
function updateSortIndicators(sortBy, sortDirection) {
    // Clear all sort indicators
    document.querySelectorAll('[id^="sort-"]').forEach(el => {
        el.innerHTML = '';
    });
    
    // Set indicator for current sort column
    const indicator = sortDirection === 'asc' ? '↑' : '↓';
    const element = document.getElementById(`sort-${sortBy}`);
    if (element) {
        element.innerHTML = indicator;
    }
}

/**
 * Handles sorting when a column header is clicked
 * @param {string} column - The column to sort by
 * @param {Function} loadDataFn - Function to call to reload data with new sort
 * @param {string} sortVarName - Variable name in parent scope for the sort column
 * @param {string} directionVarName - Variable name in parent scope for the sort direction
 */
function handleSortGeneric(column, loadDataFn, sortVarName = '{{ $sortVarName }}', directionVarName = '{{ $directionVarName }}') {
    // Use eval to get and set the variables from the parent scope
    // This is necessary because we can't directly access variables from the parent scope
    const currentSortBy = eval(sortVarName);
    const currentSortDirection = eval(directionVarName);
    
    // If sorting by the same column, toggle direction
    if (column === currentSortBy) {
        eval(`${directionVarName} = '${currentSortDirection === 'asc' ? 'desc' : 'asc'}'`);
    } else {
        // If sorting by a new column, set to ascending by default
        eval(`${sortVarName} = '${column}'`);
        eval(`${directionVarName} = 'asc'`);
    }
    
    // Update indicators and reload data
    updateSortIndicators(eval(sortVarName), eval(directionVarName));
    loadDataFn(1); // Reset to first page when sorting
}

/**
 * Updates pagination elements based on response data
 * @param {Object} pagination - Pagination data from API response
 * @param {Function} loadDataFn - Function to call when changing page
 */
function updatePaginationElements(pagination, loadDataFn) {
    // Default values in case pagination data is missing or malformed
    let currentPageValue = 1;
    let totalPagesValue = 1;
    let totalItemsValue = 0;
    let perPageValue = 10;
    
    // Only update values if pagination data exists and is valid
    if (pagination && typeof pagination === 'object') {
        currentPageValue = parseInt(pagination.page) || 1;
        totalPagesValue = parseInt(pagination.total_pages) || 1;
        totalItemsValue = parseInt(pagination.total_items) || 0;
        perPageValue = parseInt(pagination.per_page) || 10;
        
        // Update global variables if they exist
        if (typeof currentPage !== 'undefined') currentPage = currentPageValue;
        if (typeof totalPages !== 'undefined') totalPages = totalPagesValue;
        if (typeof perPage !== 'undefined') perPage = perPageValue;
    }
    
    // Calculate start and end values, protecting against NaN
    const start = totalItemsValue > 0 ? (currentPageValue - 1) * perPageValue + 1 : 0;
    const end = Math.min(start + perPageValue - 1, totalItemsValue);
    
    // Update DOM elements
    const startEl = document.getElementById('pagination-start');
    const endEl = document.getElementById('pagination-end');
    const totalEl = document.getElementById('pagination-total');
    
    if (startEl) startEl.textContent = start;
    if (endEl) endEl.textContent = end;
    if (totalEl) totalEl.textContent = totalItemsValue;
    
    // Update previous and next buttons state
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    if (prevBtn) prevBtn.disabled = currentPageValue <= 1;
    if (nextBtn) nextBtn.disabled = currentPageValue >= totalPagesValue;
}

/**
 * Utility debounce function for search inputs
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 */
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
</script> 