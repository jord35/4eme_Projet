SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS conversation_participants;
DROP TABLE IF EXISTS conversations;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS pictures;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE pictures (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NULL,
    alt_text VARCHAR(255) NULL,
    original_path VARCHAR(255) NOT NULL,
    webp_path VARCHAR(255) NULL,
    webp_320_path VARCHAR(255) NULL,
    webp_640_path VARCHAR(255) NULL,
    webp_1260_path VARCHAR(255) NULL,
    original_filename VARCHAR(255) NULL,
    mime_type VARCHAR(100) NOT NULL,
    width INT UNSIGNED NULL,
    height INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pictures_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    profile_picture_id INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    INDEX idx_users_profile_picture_id (profile_picture_id),
    INDEX idx_users_created_at (created_at),
    CONSTRAINT fk_users_profile_picture
        FOREIGN KEY (profile_picture_id) REFERENCES pictures(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    owner_user_id INT UNSIGNED NOT NULL,
    cover_picture_id INT UNSIGNED NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    INDEX idx_books_owner_user_id (owner_user_id),
    INDEX idx_books_cover_picture_id (cover_picture_id),
    INDEX idx_books_created_at (created_at),
    INDEX idx_books_is_available (is_available),
    CONSTRAINT fk_books_owner
        FOREIGN KEY (owner_user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_books_cover_picture
        FOREIGN KEY (cover_picture_id) REFERENCES pictures(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE conversations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    INDEX idx_conversations_updated_at (updated_at),
    INDEX idx_conversations_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE conversation_participants (
    conversation_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    last_read_at DATETIME NULL DEFAULT NULL,
    joined_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (conversation_id, user_id),
    INDEX idx_conversation_participants_user_id (user_id),
    INDEX idx_conversation_participants_last_read_at (last_read_at),
    CONSTRAINT fk_conversation_participants_conversation
        FOREIGN KEY (conversation_id) REFERENCES conversations(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_conversation_participants_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT UNSIGNED NOT NULL,
    sender_user_id INT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_messages_conversation_id (conversation_id),
    INDEX idx_messages_sender_user_id (sender_user_id),
    INDEX idx_messages_created_at (created_at),
    INDEX idx_messages_conversation_created_at (conversation_id, created_at),
    CONSTRAINT fk_messages_conversation
        FOREIGN KEY (conversation_id) REFERENCES conversations(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_messages_sender
        FOREIGN KEY (sender_user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

