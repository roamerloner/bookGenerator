document.addEventListener('DOMContentLoaded', function() {
    let loading = false;
    let page = 1; // Track the current page/batch of books
    const initialRecords = 20; // Initial number of records to load
    const batchSize = 10; // Number of records to load on each scroll

    // Load the initial batch of books
    loadBooks(page, initialRecords).then(() => {
        page++; // Increment page after loading the initial batch
    });

    window.addEventListener('scroll', function() {
        if (loading) return;

        const {scrollTop, scrollHeight, clientHeight} = document.documentElement;

        // Trigger loading when the user is near the bottom of the page
        if (scrollTop + clientHeight >= scrollHeight - 5) {
            loading = true;
            loadBooks(page, batchSize).then(() => {
                page++; // Increment page after loading the next batch
                loading = false;
            });
        }
    });

    /**
     * Load books from the server.
     * @param {number} page - The page/batch number to load.
     * @param {number} batchSize - The number of records to load.
     */
    async function loadBooks(page, batchSize) {
        try {
            const response = await fetch(`ajax/get-books.php?page=${page}&batchSize=${batchSize}`);
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                // Append the new books to the table
                renderBooks(data.data);
            } else {
                console.log('No more books to load.');
            }
        } catch (error) {
            console.error('Error loading books:', error);
        }
    }
});