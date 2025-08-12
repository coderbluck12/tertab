-- Fix References Institution ID Constraint Issue
-- Run this SQL script in your MySQL database before running migrations

-- Step 1: Check current state
SELECT 'Current References Data:' as info;
SELECT 
    COUNT(*) as total_references,
    COUNT(institution_id) as with_institution_id,
    COUNT(*) - COUNT(institution_id) as null_institution_id
FROM `references`;

-- Step 2: Show invalid institution_id values
SELECT 'Invalid Institution IDs:' as info;
SELECT DISTINCT r.institution_id 
FROM `references` r 
LEFT JOIN `institutions` i ON r.institution_id = i.id 
WHERE r.institution_id IS NOT NULL AND i.id IS NULL;

-- Step 3: Clean up invalid institution_id values (set them to NULL)
UPDATE `references` 
SET `institution_id` = NULL 
WHERE `institution_id` IS NOT NULL 
AND `institution_id` NOT IN (SELECT `id` FROM `institutions`);

-- Step 4: Try to populate institution_id from lecturer's institution data
UPDATE `references` r
INNER JOIN `users` u ON r.lecturer_id = u.id
INNER JOIN `institution_attendeds` ia ON u.id = ia.user_id
SET r.institution_id = ia.institution_id
WHERE r.institution_id IS NULL
AND ia.institution_id IS NOT NULL;

-- Step 5: Drop existing foreign key constraint if it exists
-- (This will fail silently if constraint doesn't exist)
SET FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `references` DROP FOREIGN KEY `references_institution_id_foreign`;
SET FOREIGN_KEY_CHECKS = 1;

-- Step 6: Verify data is clean
SELECT 'Final State Check:' as info;
SELECT 
    COUNT(*) as total_references,
    COUNT(institution_id) as with_valid_institution_id,
    COUNT(*) - COUNT(institution_id) as null_institution_id
FROM `references`;

-- Step 7: Check for any remaining invalid references
SELECT 'Remaining Invalid References:' as info;
SELECT COUNT(*) as invalid_count
FROM `references` r 
LEFT JOIN `institutions` i ON r.institution_id = i.id 
WHERE r.institution_id IS NOT NULL AND i.id IS NULL;
