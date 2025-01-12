-- Select database
USE code-lab-db;

-- Create tables

CREATE TABLE IF NOT EXISTS User (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    status VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create tables

INSERT INTO User(id, username, email, password) VALUES
('df692f78-b179-4183-97e5-87f97024ffba', 'hisham.huneidie', 'hisham@example.com', 'hashed_password_123', 'ACTIVE'),
('a3c46212-bb70-43ac-982d-d6dbe447f648', 'faisal.ramones', 'faisal@example.com', 'hashed_password_456', 'PAUSED');