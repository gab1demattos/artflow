CREATE TABLE users {
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type TEXT NOT NULL CHECK (user_type IN ('regular', 'admin')), -- a regular user can act as a client or freelancer without needing to chnage accounts
    name TEXT NOT NULL,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT NOT NULL
};

CREATE TABLE services {
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    category_id INTEGER NOT NULL,
    price REAL NOT NULL,
    delivery_time DATE NOT NULL, -- or INTEGER ?? e.g. 5 days
    images TEXT,  -- comma-separsted 
    videos TEXT,    -- paths to files
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
};

CREATE TABLE transactions {
    id INT AUTO_INCREMENT PRIMARY KEY,
    freelancer_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    status TEXT NOT NULL CHECK (status IN ('in progress', 'completed', 'cancelled')),
    amount REAL NOT NULL,
    FOREIGN KEY (freelancer_id) REFERENCES users(id),
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
};

CREATE TABLE messages {
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
};

CREATE TABLE reviews {
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,    --  before a user being able to leave a review we need to check if the service's status is 'completed'
    rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
};

CREATE TABLE categories {
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_type TEXT NOT NULL
}