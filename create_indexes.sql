CREATE INDEX idx_orders_created_at ON orders(created_at);
CREATE INDEX idx_orders_customer_id_hash ON orders USING HASH (customer_id);
CREATE INDEX idx_orders_amount_status ON orders(amount, status);