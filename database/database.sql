DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Exchange;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Category;

CREATE TABLE User (
    id INTEGER PRIMARY KEY,
    user_type TEXT NOT NULL CHECK (user_type IN ('regular', 'admin')), -- a regular user can act as a client or freelancer without needing to change accounts
    -- isClient INT NOT NULL CHECK (isClient IN (0,1)), -- if true (0), the user is a client
    -- isFreelancer BOOLEAN NOT NULL CHECK (isFreelancer IN (0,1)), -- if true (0), the user is a freelancer
    name TEXT NOT NULL,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL
);

CREATE TABLE Service (
    id INTEGER PRIMARY KEY,
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
);

CREATE TABLE Exchange (
    id INTEGER PRIMARY KEY,
    freelancer_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    status TEXT NOT NULL CHECK (status IN ('in progress', 'completed', 'cancelled')),
    amount REAL NOT NULL,
    FOREIGN KEY (freelancer_id) REFERENCES User(id),
    FOREIGN KEY (client_id) REFERENCES User(id),
    FOREIGN KEY (service_id) REFERENCES User(id)
);

CREATE TABLE Message (
    id INTEGER PRIMARY KEY,
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES User(id),
    FOREIGN KEY (receiver_id) REFERENCES User(id)
);

CREATE TABLE Review (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,    --  before a user being able to leave a review we need to check if the service's status is 'completed'
    rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (service_id) REFERENCES User(id)
);

CREATE TABLE Category (
    id INTEGER PRIMARY KEY,
    category_type TEXT NOT NULL
);