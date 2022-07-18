INSERT INTO `users` (
`id`,
`name`,
`email`,
`status`,
`parent_id`,
`permissions`,
`role_id`,
`email_verified_at`,
 `password`,
 `token`,
 `verification_code`,
 `remember_token`,
 `last_seen_at`,
 `created_at`,
 `updated_at`
 )
 VALUES
 (
 NULL,
 'Super Admin',
 'support@pkteam.com',
 '1',
 NULL,
 NULL,
 '3',
 NOW(),
 '$2y$10$AlBKmhRXHvzgedPMrEwwr.ZRtfSw/aOLVntCMR/pM9Dl5DAJLOywe',
 NULL,
 'ovwgRav1OlHK774pP2tuv3j0cBSQ4wklNrm8Sa0P6p9KuoZHdfOcwuDfsSlue6LlDfHFKvRy4qTm3ZNKJaFu4pdkUoCN61RGHbiGaLtZmw4sGByu4FtTttSxurTgaD4w', 'VsK1kw3XgwuSmXnOxG4KYmiQmwa1hBJNUWJnQoTRfoPJdSzpZEUnNgDtMg6X',
 NOW(),
 NOW(),
 NOW());
