import Dexie from 'dexie'

export const db = new Dexie('POSDatabase')

// db.version(1).stores({
//   // From your Product model
//   products: 'id, name, code, category_id, brand_id, unit_id, selling_price',

//   // From your Customer model
//   customers: 'id, name, contact, customer_type',

//   // Payment methods
//   payment_methods: 'id, name',

//   // Current logged in outlet + user info
//   session: 'key',

//   // Offline sale queue — maps to your Sale + SaleItem models
//   pending_sales: '++id, status, outlet_id, created_at',
// })


db.version(1).stores({
    // Indexed fields only — non-indexed data lives in the full object
    products: 'id, name, code, category_id, brand_id, unit_id, sub_unit_id, parent_product_id',
    customers: 'id, name, contact, customer_type',
    payment_methods: 'id, name',
    session: 'key',
    pending_sales: '++id, status, outlet_id, created_at',
    accounts: 'id, name, account_number',
})
