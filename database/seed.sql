INSERT INTO users 
(
    fname,
    lname,
    email,
    phone_number,
    password_hash,
    role
)
VALUES (
    'Admin',
    'User',
    'admin@photoimpact.com',
    '3055551234',
    '\$2y\$10\$KTVwLmb5y8/0p619NPDua.vzKHAdZNQid673EUGFCQA/S0p4kyioy',
    'admin'
),
(
    'User',
    'Test',
    'user@example.com',
    '3055552222',
    '$2y$10$jMG.BeOmad1K8o8ggBa8y.xU5FfXgpjlrkVy9Cij/6WTokKq72/12',
    'user'
);