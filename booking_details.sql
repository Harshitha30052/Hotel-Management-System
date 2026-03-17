CREATE TABLE IF NOT EXISTS bookings_hotel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_name VARCHAR(100) NOT NULL,
    room_type VARCHAR(100) NOT NULL,
    room_price VARCHAR(50) NOT NULL,
    username VARCHAR(100),
    status ENUM('active','canceled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
