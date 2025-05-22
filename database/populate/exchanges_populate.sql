-- Exchanges
INSERT INTO Exchange (
        freelancer_id,
        client_id,
        service_id,
        status,
        amount
    )
VALUES (2, 5, 1, 'completed', 75.00),
    (3, 6, 2, 'completed', 120.00),
    (4, 7, 3, 'in progress', 250.00),
    (5, 8, 4, 'in progress', 180.00),
    (6, 2, 5, 'completed', 95.00),
    (7, 3, 6, 'cancelled', 85.00);