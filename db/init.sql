CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, url, description) VALUES
('CRM‑PRO', 'product/crm-pro.html', 'Корпоративная CRM‑система для бизнеса'),
('Mobile‑Suite', 'product/mobile-suite.html', 'Нативные мобильные приложения'),
('Cloud‑Stack', 'product/cloud-stack.html', 'Оркестрация Docker/K8s'),
('Portal‑X', 'product/portal-x.html', 'Корпоративный портал');
