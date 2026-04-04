import Dexie from 'dexie'

export const db = new Dexie('POSDatabase')

db.version(1).stores({
  // From your Product model
  products: 'id, name, code, category_id, brand_id, unit_id, selling_price',

  // From your Customer model
  customers: 'id, name, contact, customer_type',

  // Payment methods
  payment_methods: 'id, name',

  // Current logged in outlet + user info
  session: 'key',

  // Offline sale queue — maps to your Sale + SaleItem models
  pending_sales: '++id, status, outlet_id, created_at',
})
