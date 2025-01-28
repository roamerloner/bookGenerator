document.addEventListener('DOMContentLoaded', function() {
    let loading = false;
    let startIndex = 0; 
    const initialRecords = 20;
    const batchSize = 10;

    loadBooks(startIndex, initialRecords).then(() => {
        startIndex += initialRecords; 
    });

    window.addEventListener('scroll', function() {
        if (loading) return;

        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;

        if (scrollTop + clientHeight >= scrollHeight - 5) {
            loading = true;
            loadBooks(startIndex, batchSize).then(() => {
                startIndex += batchSize; 
                loading = false;
            });
        }
    });

    async function loadBooks(startIndex, batchSize) {
        try {
            const response = await fetch(`ajax/get-books.php?startIndex=${startIndex}&batchSize=${batchSize}`);
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
