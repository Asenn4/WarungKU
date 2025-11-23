const DB_NAME = 'POS_Warung';
const DB_VERSION = 1;
let db;

// Initialize IndexedDB
function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => {
            db = request.result;
            resolve(db);
        };

        request.onupgradeneeded = (event) => {
            db = event.target.result;

            // Products Store
            if (!db.objectStoreNames.contains('products')) {
                const productsStore = db.createObjectStore('products', { keyPath: 'id' });
                productsStore.createIndex('name', 'name', { unique: false });
                productsStore.createIndex('sku', 'sku', { unique: false });
            }

            // Transactions Outbox (for offline transactions)
            if (!db.objectStoreNames.contains('transactions_outbox')) {
                const outboxStore = db.createObjectStore('transactions_outbox', { 
                    keyPath: 'id', 
                    autoIncrement: true 
                });
                outboxStore.createIndex('synced', 'synced', { unique: false });
                outboxStore.createIndex('created_at', 'created_at', { unique: false });
            }
        };
    });
}

// Save products to IndexedDB
async function saveProductsToDB(products) {
    const transaction = db.transaction(['products'], 'readwrite');
    const store = transaction.objectStore('products');

    // Clear existing products
    await store.clear();

    // Add new products
    for (const product of products) {
        await store.add(product);
    }

    return transaction.complete;
}

// Get all products from IndexedDB
async function getProductsFromDB() {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['products'], 'readonly');
        const store = transaction.objectStore('products');
        const request = store.getAll();

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Search products in IndexedDB
async function searchProductsInDB(query) {
    const products = await getProductsFromDB();
    const lowerQuery = query.toLowerCase();
    
    return products.filter(product => 
        product.name.toLowerCase().includes(lowerQuery) ||
        (product.sku && product.sku.toLowerCase().includes(lowerQuery))
    );
}

// Save offline transaction
async function saveOfflineTransaction(transactionData) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['transactions_outbox'], 'readwrite');
        const store = transaction.objectStore('transactions_outbox');
        
        const data = {
            ...transactionData,
            synced: false,
            created_at: new Date().toISOString()
        };

        const request = store.add(data);

        request.onsuccess = () => {
            console.log('Transaction saved offline:', request.result);
            resolve(request.result);
        };
        request.onerror = () => reject(request.error);
    });
}

// Get unsynced transactions
async function getUnsyncedTransactions() {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['transactions_outbox'], 'readonly');
        const store = transaction.objectStore('transactions_outbox');
        const index = store.index('synced');
        const request = index.getAll(false);

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Mark transaction as synced
async function markTransactionSynced(id) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['transactions_outbox'], 'readwrite');
        const store = transaction.objectStore('transactions_outbox');
        const request = store.get(id);

        request.onsuccess = () => {
            const data = request.result;
            data.synced = true;
            data.synced_at = new Date().toISOString();

            const updateRequest = store.put(data);
            updateRequest.onsuccess = () => resolve();
            updateRequest.onerror = () => reject(updateRequest.error);
        };
        request.onerror = () => reject(request.error);
    });
}

// Update product stock in IndexedDB
async function updateProductStockInDB(productId, quantity) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['products'], 'readwrite');
        const store = transaction.objectStore('products');
        const request = store.get(productId);

        request.onsuccess = () => {
            const product = request.result;
            if (product) {
                product.stock -= quantity;
                const updateRequest = store.put(product);
                updateRequest.onsuccess = () => resolve(product);
                updateRequest.onerror = () => reject(updateRequest.error);
            } else {
                reject(new Error('Product not found'));
            }
        };
        request.onerror = () => reject(request.error);
    });
}

// Initialize DB on page load
document.addEventListener('DOMContentLoaded', async () => {
    try {
        await initDB();
        console.log('IndexedDB initialized successfully');
        
        // Sync products from server if online
        if (navigator.onLine) {
            await syncProductsFromServer();
        }
    } catch (error) {
        console.error('Failed to initialize IndexedDB:', error);
    }
});