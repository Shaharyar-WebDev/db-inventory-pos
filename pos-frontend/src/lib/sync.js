import api from './api'
import { db } from './db'

// Called once after login — pulls all master data into IndexedDB
export async function bootstrap() {
  try {
    const { data } = await api.get('/bootstrap')

    // Save everything to IndexedDB
    await db.products.clear()
    await db.products.bulkPut(data.products)

    await db.customers.clear()
    await db.customers.bulkPut(data.customers)

    await db.payment_methods.clear()
    await db.payment_methods.bulkPut(data.payment_methods)

    return true
  } catch (error) {
    console.error('Bootstrap failed:', error)
    return false
  }
}

// Called when cashier completes a sale
export async function queueSale(salePayload) {
  // Always save to IndexedDB first
  const id = await db.pending_sales.add({
    ...salePayload,
    status: 'pending',
    created_at: new Date().toISOString(),
  })

  // If online, try to sync immediately
  if (navigator.onLine) {
    await syncPendingSales()
  }

  return id
}

// Picks up all pending sales and sends to Laravel
export async function syncPendingSales() {
  const pending = await db.pending_sales
    .where('status')
    .equals('pending')
    .toArray()

  if (pending.length === 0) return

  for (const sale of pending) {
    try {
      await api.post('/sales', sale)

      // Mark as synced
      await db.pending_sales.update(sale.id, { status: 'synced' })

    } catch (error) {
      // If server error (not network) — mark as failed
      if (error.response) {
        await db.pending_sales.update(sale.id, { status: 'failed' })
      }
      // If network error — leave as pending, will retry next time
    }
  }
}
