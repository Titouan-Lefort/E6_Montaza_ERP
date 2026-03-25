# Schéma de Liaison : Devis Tuyauterie ↔ Affaires

## Vue d'ensemble de la relation

```
┌─────────────────────────────────────┐
│         AFFAIRES                     │
│  (Projets / Chantiers)              │
├─────────────────────────────────────┤
│ • id (PK)                           │
│ • code (ex: 26-001)                 │
│ • nom                               │
│ • statut                            │
│ • budget                            │
│ • total_ht                          │
│ • date_debut                        │
│ • date_fin_prevue                   │
└──────────────┬──────────────────────┘
               │
               │ Une affaire peut avoir plusieurs :
               ├─────────────────────────────────────┐
               │                                     │
               │                                     │
       ┌───────▼────────┐                   ┌───────▼────────┐
       │  COMMANDES     │                   │  DEVIS         │
       │  (Cde)         │                   │  TUYAUTERIE    │
       ├────────────────┤                   ├────────────────┤
       │ • id           │                   │ • id           │
       │ • affaire_id   │◄──────┐           │ • affaire_id   │
       │ • code         │       │           │ • reference    │
       │ • total_ht     │       │           │ • total_ttc    │
       └────────────────┘       │           └────────────────┘
                                │
                                │
                       ┌────────▼────────┐
                       │  DEMANDES DE    │
                       │  PRIX (DDP)     │
                       ├─────────────────┤
                       │ • id            │
                       │ • affaire_id    │
                       │ • code          │
                       └─────────────────┘
```

## Structure détaillée : Table DEVIS_TUYAUTERIES

```sql
CREATE TABLE devis_tuyauteries (
    id                    BIGSERIAL PRIMARY KEY,
    
    -- NOUVELLE COLONNE (ajoutée par la migration)
    affaire_id            BIGINT NULL,  ◄── Lien vers affaires
    
    -- Informations projet
    reference_projet      VARCHAR(255),
    lieu_intervention     VARCHAR(255),
    
    -- Client
    societe_id            BIGINT NULL,
    societe_contact_id    BIGINT NULL,
    client_nom            VARCHAR(255),
    client_contact        VARCHAR(255),
    client_adresse        TEXT,
    
    -- Dates & Validité
    date_emission         DATE,
    duree_validite        INTEGER DEFAULT 30,
    
    -- Conditions
    options               JSON,
    conditions_paiement   VARCHAR(255),
    delais_execution      VARCHAR(255),
    
    -- Totaux
    total_ht              DECIMAL(15,2),
    total_tva             DECIMAL(15,2),
    total_ttc             DECIMAL(15,2),
    marge_globale         DECIMAL(15,2),
    
    -- État
    is_archived           BOOLEAN DEFAULT false,
    
    -- Timestamps
    created_at            TIMESTAMP,
    updated_at            TIMESTAMP,
    
    -- CLÉS ÉTRANGÈRES
    FOREIGN KEY (affaire_id) 
        REFERENCES affaires(id) 
        ON DELETE SET NULL,
    
    FOREIGN KEY (societe_id) 
        REFERENCES societes(id) 
        ON DELETE SET NULL
);
```

## Flux de données

```
1. CRÉATION D'UNE AFFAIRE
   ↓
   Affaire 26-001 "Rénovation Ligne Vapeur"
   
2. CRÉATION DE DEVIS LIÉS À L'AFFAIRE
   ↓
   ┌─────────────────────────────────────┐
   │ Devis 1: Préfabrication atelier     │
   │ affaire_id = 26-001                 │
   │ total_ttc = 25,000 €                │
   └─────────────────────────────────────┘
   
   ┌─────────────────────────────────────┐
   │ Devis 2: Installation sur site      │
   │ affaire_id = 26-001                 │
   │ total_ttc = 18,000 €                │
   └─────────────────────────────────────┘

3. CRÉATION DE COMMANDES FOURNISSEURS
   ↓
   ┌─────────────────────────────────────┐
   │ CDE-26-0045                         │
   │ affaire_id = 26-001                 │
   │ total_ht = 15,000 €                 │
   └─────────────────────────────────────┘

4. SUIVI GLOBAL DANS L'AFFAIRE
   ↓
   Vue consolidée:
   - Budget: 50,000 €
   - Devis émis: 43,000 € TTC
   - Commandes: 15,000 € HT
   - Matériel assigné: 3 items
```

## Requêtes utiles

### Voir tous les devis d'une affaire
```php
$affaire = Affaire::find(1);
$devis = $affaire->devisTuyauteries;
// ou
$devis = DevisTuyauterie::where('affaire_id', 1)->get();
```

### Voir l'affaire d'un devis
```php
$devis = DevisTuyauterie::find(1);
$affaire = $devis->affaire;
```

### Statistiques globales d'une affaire
```php
$affaire = Affaire::with(['cdes', 'devisTuyauteries', 'ddps'])->find(1);

$totalCommandes = $affaire->cdes->sum('total_ht');
$totalDevis = $affaire->devisTuyauteries->sum('total_ttc');
$nbDevis = $affaire->devisTuyauteries->count();
```

## Interface utilisateur

### 1. Page AFFAIRE (affaires/show.blade.php)
```
┌──────────────────────────────────────────────────┐
│ Affaire 26-001: Rénovation Ligne Vapeur         │
│ Budget: 50,000 € | Engagé: 15,000 €             │
├──────────────────────────────────────────────────┤
│                                                  │
│ COMMANDES (3)          DEVIS TUYAUTERIE (2)    │
│ • CDE-26-0045           • Préfabrication        │
│   15,000 €               25,000 € TTC          │
│                          08/02/2026             │
│ • CDE-26-0046                                  │
│   8,500 €              • Installation          │
│                          18,000 € TTC          │
│                          10/02/2026             │
└──────────────────────────────────────────────────┘
```

### 2. Liste DEVIS (devis_tuyauterie/index.blade.php)
```
┌────────────────────────────────────────────────────────┐
│ Référence        │ Affaire  │ Client     │ Total      │
├────────────────────────────────────────────────────────┤
│ Préfabrication   │ 26-001   │ ACME Corp  │ 25,000 €  │
│ Ligne 4          │          │            │           │
├────────────────────────────────────────────────────────┤
│ Installation     │ 26-001   │ ACME Corp  │ 18,000 €  │
│ Site A           │          │            │           │
└────────────────────────────────────────────────────────┘
```

### 3. Formulaire DEVIS (devis_tuyauterie/create.blade.php)
```
┌──────────────────────────────────────────────┐
│ Créer un devis de tuyauterie                 │
├──────────────────────────────────────────────┤
│                                              │
│ Affaire liée (optionnel)                    │
│ [▼ Sélectionner une affaire      ]          │
│    ├─ 26-001 - Rénovation Vapeur            │
│    ├─ 26-002 - Extension Usine              │
│    └─ 25-045 - Maintenance annuelle         │
│                                              │
│ Référence Projet                            │
│ [Préfabrication Ligne 4          ]          │
│                                              │
│ Client                                      │
│ [▼ ACME Corporation              ]          │
│                                              │
└──────────────────────────────────────────────┘
```

## Avantages de cette liaison

✅ **Traçabilité** : Voir tous les devis liés à un projet
✅ **Suivi financier** : Comparaison budget vs devis émis
✅ **Organisation** : Regroupement logique par chantier
✅ **Reporting** : Statistiques par affaire
✅ **Optionnel** : Les devis peuvent exister sans affaire (colonne nullable)

## Migration SQL exécutée

```sql
ALTER TABLE devis_tuyauteries 
ADD COLUMN affaire_id BIGINT NULL;

ALTER TABLE devis_tuyauteries 
ADD CONSTRAINT devis_tuyauteries_affaire_id_foreign 
FOREIGN KEY (affaire_id) 
REFERENCES affaires(id) 
ON DELETE SET NULL;
```

## Notes importantes

⚠️ **La colonne est NULLABLE** : Les devis existants ne sont pas affectés
⚠️ **ON DELETE SET NULL** : Si une affaire est supprimée, le devis reste mais `affaire_id` devient `null`
✅ **Réversible** : La migration peut être annulée avec `migrate:rollback`
