document.addEventListener('DOMContentLoaded', function() {
    let loading = false;
    let page = 1; 
    const initialRecords = 20; 
    const batchSize = 10; 

    
    loadBooks(page, initialRecords).then(() => {
        page++; 
    });

    window.addEventListener('scroll', function() {
        if (loading) return;

        const {scrollTop, scrollHeight, clientHeight} = document.documentElement;

        
        if (scrollTop + clientHeight >= scrollHeight - 5) {
            loading = true;
            loadBooks(page, batchSize).then(() => {
                page++; 
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
                
                renderBooks(data.data);
            } else {
                console.log('No more books to load.');
            }
        } catch (error) {
            console.error('Error loading books:', error);
        }
    }
});