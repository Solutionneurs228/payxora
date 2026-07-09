-- CORRECTION DIRECTE BDD - Exécuter dans phpMyAdmin ou MySQL CLI

-- 1. Modifier la colonne buyer_id pour accepter NULL
ALTER TABLE `transactions` MODIFY COLUMN `buyer_id` BIGINT UNSIGNED NULL;

-- 2. Vérifier que la modification a été appliquée
DESCRIBE `transactions`;

-- 3. Si la contrainte foreign key bloque, la supprimer d'abord :
-- ALTER TABLE `transactions` DROP FOREIGN KEY `transactions_buyer_id_foreign`;
-- ALTER TABLE `transactions` MODIFY COLUMN `buyer_id` BIGINT UNSIGNED NULL;
-- ALTER TABLE `transactions` ADD CONSTRAINT `transactions_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
