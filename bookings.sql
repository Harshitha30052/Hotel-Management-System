CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),         -- store the username directly
    check_in DATE,
    check_out DATE,
    guests INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);