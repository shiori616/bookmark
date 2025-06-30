-- MySQLでテーブルを手動作成する場合のSQL
CREATE TABLE IF NOT EXISTS book_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title TEXT,
    authors TEXT,
    image_url TEXT,
    description TEXT,
    industry_identifiers JSON,
    isbn13 VARCHAR(20),
    isbn10 VARCHAR(15),
    button_type VARCHAR(20),
    click_datetime DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_button_type (button_type),
    INDEX idx_click_datetime (click_datetime),
    INDEX idx_isbn13 (isbn13),
    INDEX idx_isbn10 (isbn10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
