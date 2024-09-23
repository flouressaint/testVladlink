CREATE TABLE
    categories (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        alias VARCHAR(255) DEFAULT NULL,
        parent_id INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE CASCADE
    );