-- Migration manuelle : Ajouter affaire_id à devis_tuyauteries (OBLIGATOIRE)
-- À exécuter dans votre base de données PostgreSQL

-- ATTENTION : Cette colonne est OBLIGATOIRE
-- Assurez-vous qu'il n'y a pas de devis existants ou associez-les à une affaire avant d'exécuter

-- Ajouter la colonne affaire_id (temporairement nullable)
ALTER TABLE devis_tuyauteries
ADD COLUMN IF NOT EXISTS affaire_id BIGINT NULL;

-- Si vous avez des devis existants, associez-les à une affaire par défaut
-- UPDATE devis_tuyauteries SET affaire_id = 1 WHERE affaire_id IS NULL;

-- Rendre la colonne obligatoire
ALTER TABLE devis_tuyauteries
ALTER COLUMN affaire_id SET NOT NULL;

-- Ajouter la clé étrangère avec cascade
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_constraint
        WHERE conname = 'devis_tuyauteries_affaire_id_foreign'
    ) THEN
        ALTER TABLE devis_tuyauteries
        ADD CONSTRAINT devis_tuyauteries_affaire_id_foreign
        FOREIGN KEY (affaire_id)
        REFERENCES affaires(id)
        ON DELETE CASCADE;
    END IF;
END $$;

-- Vérifier que la colonne a été créée
SELECT column_name, data_type, is_nullable
FROM information_schema.columns
WHERE table_name = 'devis_tuyauteries'
AND column_name = 'affaire_id';
