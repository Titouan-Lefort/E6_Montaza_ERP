-- ==============================================================================
-- MIGRATION SIMPLE ET SÛRE : Ajouter affaire_id aux devis
-- ==============================================================================
-- À exécuter dans pgAdmin ou tout autre client PostgreSQL
-- Base de données : montaza
-- ==============================================================================

-- Étape 1 : Ajouter la colonne (nullable = sûr, pas de données perdues)
ALTER TABLE devis_tuyauteries
ADD COLUMN IF NOT EXISTS affaire_id BIGINT NULL;

-- Étape 2 : Ajouter la clé étrangère
ALTER TABLE devis_tuyauteries
ADD CONSTRAINT IF NOT EXISTS devis_tuyauteries_affaire_id_foreign
FOREIGN KEY (affaire_id)
REFERENCES affaires(id)
ON DELETE SET NULL;

-- Étape 3 : Vérification
SELECT
    '✓ Migration terminée avec succès !' as status,
    column_name,
    data_type,
    is_nullable,
    column_default
FROM information_schema.columns
WHERE table_name = 'devis_tuyauteries'
AND column_name = 'affaire_id';

-- ==============================================================================
-- RÉSULTAT ATTENDU :
-- status: ✓ Migration terminée avec succès !
-- column_name: affaire_id
-- data_type: bigint
-- is_nullable: YES
-- ==============================================================================
