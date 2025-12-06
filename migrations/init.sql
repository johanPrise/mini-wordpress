CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    role VARCHAR(20) DEFAULT 'user',
    token VARCHAR(255),
    reset_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE pages (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);
