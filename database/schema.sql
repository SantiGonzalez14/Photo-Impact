CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(45) NOT NULL,
    lname VARCHAR(60) NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE,
    phone_number VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

CREATE TABLE quotes (
    quote_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,

    quote_value DECIMAL(10,2) NOT NULL,

    delivery_type ENUM('digital', 'physical'),

    number_of_pictures INT UNSIGNED,

    type_of_event ENUM(
        'wedding',
        'quince',
        'corporate',
        'photoshoot'
    ),

    event_date DATE,

    event_location VARCHAR(100),

    special_requests TEXT,

    quote_status ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',

    is_archived BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_quotes_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
);

CREATE TABLE bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    quote_id INT UNSIGNED NOT NULL UNIQUE,

    booking_date DATE NOT NULL,
    
    event_date DATE NOT NULL,

    booking_status ENUM(
        'scheduled',
        'completed',
        'cancelled'
    ) DEFAULT 'scheduled',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_bookings_quote
        FOREIGN KEY (quote_id)
        REFERENCES quotes(quote_id)
);

CREATE TABLE contact (

    contact_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(70) NOT NULL,

    message TEXT NOT NULL,

    user_id INT UNSIGNED NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_contact_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)

);

CREATE TABLE booking_reschedule_requests (
    request_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    booking_id INT UNSIGNED NOT NULL,

    current_event_date DATE NOT NULL,
    requested_event_date DATE NOT NULL,

    reschedule_reason TEXT,

    request_status ENUM(
        'pending',
        'approved',
        'rejected',
        'cancelled'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_booking_id
            FOREIGN KEY (booking_id)
            REFERENCES bookings(booking_id)
);

CREATE TABLE reviews ( 
    review_id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT UNSIGNED NOT NULL, 
    name VARCHAR(100) NOT NULL, 
    email VARCHAR(100) NOT NULL, 
    rating INT NOT NULL, 
    review TEXT NOT NULL, 
    admin_response TEXT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_user_id
            FOREIGN KEY (user_id)
            REFERENCES users(user_id)
); 