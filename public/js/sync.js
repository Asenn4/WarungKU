// Sync products from server to IndexedDB
async function syncProductsFromServer() {
    try {
        const response = await fetch('/api/products', {
            headers: {
                'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const products = await response.json();
            await saveProductsToDB(products);
            console.log('Products synced to IndexedDB:', products.length);
        }
    } catch (error) {
        console.error('Failed to sync products:', error);
    }
}

// Sync offline transactions to server
async function syncOfflineTransactions() {
    if (!navigator.onLine) {
        console.log('Cannot sync: offline');
        return;
    }

    try {
        const unsyncedTransactions = await getUnsyncedTransactions();
        
        if (unsyncedTransactions.length === 0) {
            console.log('No transactions to sync');
            return;
        }

        console.log(`Syncing ${unsyncedTransactions.length} offline transactions...`);

        for (const transaction of unsyncedTransactions) {
            try {
                const response = await fetch('/api/sync-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cart: transaction.cart,
                        total: transaction.total,
                        cashier: transaction.cashier
                    })
                });

                if (response.ok) {
                    await markTransactionSynced(transaction.id);
                    console.log('Transaction synced:', transaction.id);
                    
                    // Show notification
                    showSyncNotification('success');
                } else {
                    console.error('Failed to sync transaction:', transaction.id);
                }
            } catch (error) {
                console.error('Error syncing transaction:', error);
            }
        }

        // Refresh products after sync
        await syncProductsFromServer();
        
    } catch (error) {
        console.error('Failed to sync offline transactions:', error);
    }
}

// Show sync notification
function showSyncNotification(type) {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = type === 'success' 
        ? '✓ Transaksi berhasil disinkronkan' 
        : '✗ Gagal sinkronisasi';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Process offline transaction
async function processOfflineTransaction(cart, total, cashier) {
    try {
        // Save to outbox
        await saveOfflineTransaction({
            cart: cart,
            total: total,
            cashier: cashier
        });

        // Update stock in IndexedDB
        for (const item of cart) {
            await updateProductStockInDB(item.id, item.qty);
        }

        console.log('Offline transaction saved successfully');
        return true;
    } catch (error) {
        console.error('Failed to save offline transaction:', error);
        return false;
    }
}

// Auto-sync when coming back online
window.addEventListener('online', async () => {
    console.log('Back online - starting auto-sync...');
    await syncOfflineTransactions();
});

// Periodic sync check (every 30 seconds)
setInterval(async () => {
    if (navigator.onLine) {
        await syncOfflineTransactions();
    }
}, 30000);

// Initial sync check
setTimeout(async () => {
    if (navigator.onLine) {
        await syncOfflineTransactions();
    }
}, 2000);