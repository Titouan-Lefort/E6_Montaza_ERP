# Tests Unitaires pour les Commandes

## 📋 Résumé des tests créés

### 1. **CdeTest.php** - Tests du modèle Cde (Commande)
**Localisation:** `tests/Unit/Models/CdeTest.php`  
**Nombre de tests:** 20+

#### Couverture des tests:

**Relations:**
- ✅ `test_cde_appartient_a_un_user()` - Une commande appartient à un utilisateur
- ✅ `test_cde_appartient_a_une_entite()` - Une commande appartient à une entité
- ✅ `test_cde_a_un_statut()` - Une commande a un statut DDP
- ✅ `test_cde_appartient_a_un_ddp()` - Une commande peut appartenir à un DDP
- ✅ `test_cde_appartient_a_une_affaire()` - Une commande peut appartenir à une affaire
- ✅ `test_cde_a_plusieurs_lignes()` - Une commande a plusieurs lignes
- ✅ `test_cde_recupere_societe_contacts_via_entite()` - Relations complexes hasManyThrough
- ✅ `test_cde_a_plusieurs_mouvements_stock()` - Relations avec mouvements de stock

**Méthodes et accesseurs:**
- ✅ `test_has_societe_contact_retourne_true_si_contacts_existent()` - Vérification de contacts
- ✅ `test_has_societe_contact_retourne_false_si_aucun_contact()` - Cas sans contacts
- ✅ `test_get_etablissement_attribute_retourne_etablissement_de_entite()` - Accesseur établissement
- ✅ `test_get_societe_attribute_retourne_societe_via_entite()` - Accesseur société

**Auto-stockage (logique métier critique):**
- ✅ `test_auto_stock_cree_mouvements_quand_statut_terminee()` - Statut 3 → auto-stock
- ✅ `test_auto_stock_utilise_date_livraison_reelle_pour_mouvement()` - Date correcte
- ✅ `test_auto_stock_ne_stocke_pas_lignes_deja_stockees()` - Prévention doublons
- ✅ `test_auto_stock_ne_stocke_pas_lignes_sans_matiere()` - Validation matière
- ✅ `test_auto_stock_ne_stocke_pas_lignes_sans_date_livraison()` - Validation date

**Affaires et totaux:**
- ✅ `test_mise_a_jour_affaire_total_quand_cde_sauvegardee()` - Total actualisé
- ✅ `test_mise_a_jour_affaire_total_quand_cde_supprimee()` - Total recalculé

### 2. **CdeLigneTest.php** - Tests du modèle CdeLigne (Ligne de commande)
**Localisation:** `tests/Unit/Models/CdeLigneTest.php`  
**Nombre de tests:** 18

#### Couverture des tests:

**Relations:**
- ✅ `test_cde_ligne_appartient_a_une_cde()` - Une ligne appartient à une commande
- ✅ `test_cde_ligne_peut_avoir_une_matiere()` - Relation optionnelle avec matière
- ✅ `test_cde_ligne_peut_avoir_une_unite()` - Relation avec unité de mesure
- ✅ `test_cde_ligne_peut_avoir_un_statut()` - Relation avec statut DDP
- ✅ `test_cde_ligne_peut_avoir_un_type_expedition()` - Type d'expédition
- ✅ `test_cde_ligne_peut_avoir_plusieurs_mouvements_stock()` - Relations multiples

**Champs et calculs:**
- ✅ `test_cde_ligne_peut_etre_marquee_comme_stockee()` - Flag is_stocke
- ✅ `test_cde_ligne_peut_etre_marquee_comme_non_livree()` - Flag non_livre
- ✅ `test_cde_ligne_a_des_dates_de_livraison()` - Dates prévue et réelle
- ✅ `test_cde_ligne_calcule_prix_total()` - Calcul quantité × prix unitaire
- ✅ `test_cde_ligne_a_des_references()` - Références interne et fournisseur

**Organisation et tri:**
- ✅ `test_cde_ligne_peut_etre_une_sous_ligne()` - Flag sous_ligne
- ✅ `test_cde_ligne_est_triee_par_poste()` - Tri par numéro de poste

**Cas limites:**
- ✅ `test_cde_ligne_peut_ne_pas_avoir_de_matiere()` - Matière optionnelle
- ✅ `test_cde_ligne_peut_avoir_prix_unitaire_zero()` - Prix à zéro valide
- ✅ `test_cde_ligne_peut_avoir_quantite_decimale()` - Quantités décimales

### 3. **Factories créées**

#### **MouvementStockFactory.php** (NOUVEAU)
**Localisation:** `database/factories/MouvementStockFactory.php`

**Méthodes:**
- `definition()` - Génération de mouvement de stock générique
- `entree()` - State pour mouvement d'entrée
- `sortie()` - State pour mouvement de sortie
- `pourCdeLigne($ligne)` - State pour mouvement lié à une ligne de commande

**Exemple d'utilisation:**
```php
// Mouvement d'entrée pour une ligne spécifique
MouvementStock::factory()
    ->pourCdeLigne($cdeLigne)
    ->create();

// Mouvement de sortie simple
MouvementStock::factory()
    ->sortie()
    ->create();
```

#### **CdeLigneFactory.php** (AMÉLIORÉ)
**Champs ajoutés:**
- `date_livraison_reelle` → null par défaut
- `is_stocke` → false par défaut
- `non_livre` → false par défaut
- `sous_ligne` → false par défaut

---

## 🚀 Exécution des tests

### Option 1: Fichiers batch (Double-cliquer)
1. **TEST_CDE.bat** - Tests du modèle Cde uniquement
2. **TEST_CDELIGNES.bat** - Tests du modèle CdeLigne uniquement
3. **TEST_PERSONNEL.bat** - Tests du Personnel
4. **TEST_ALL.bat** - Tous les tests de l'application

### Option 2: Ligne de commande via SSH Vagrant

```bash
# 1. Connectez-vous à Vagrant
vagrant ssh

# 2. Naviguez vers le projet
cd /home/vagrant/code/montaza

# 3. Exécutez les tests
php artisan test --filter=CdeTest                # Tests Cde uniquement
php artisan test --filter=CdeLigneTest           # Tests CdeLigne uniquement
php artisan test --filter="CdeTest|CdeLigneTest" # Les deux ensembles
php artisan test                                  # Tous les tests
```

### Option 3: Tests spécifiques

```bash
# Test unitaire particulier
php artisan test --filter=test_auto_stock_cree_mouvements_quand_statut_terminee

# Tests avec couverture de code
php artisan test --coverage

# Tests avec détails
php artisan test --verbose
```

---

## 🔧 Résolution de problèmes Vagrant

Si les fichiers .bat ne fonctionnent pas:

1. **Vérifiez Homestead.yaml:**
   ```yaml
   authorize: ~/.ssh/id_rsa.pub
   keys:
       - ~/.ssh/id_rsa
   ```

2. **Vérifiez que Vagrant tourne:**
   ```bash
   vagrant status
   vagrant up  # Si nécessaire
   ```

3. **Testez la connexion SSH:**
   ```bash
   vagrant ssh
   ```

---

## 📊 Couverture des tests

### Module Commandes (Cde + CdeLigne)
- **Total de tests:** 38+
- **Modèles couverts:** Cde, CdeLigne, MouvementStock
- **Relations testées:** 12+ types de relations
- **Logique métier:** Auto-stockage, calculs, validations
- **Cas limites:** Null handling, doublons, dates manquantes

### Points clés testés:
✅ Toutes les relations belongsTo, hasMany, hasManyThrough  
✅ Auto-stockage lors du passage au statut "Terminée" (3)  
✅ Prévention des doublons de stockage  
✅ Validation des données (matière, dates)  
✅ Calculs de prix et quantités  
✅ Mise à jour des totaux d'affaires  
✅ Gestion des sous-lignes  
✅ Tri et organisation des lignes  

---

## 📝 Prochaines étapes suggérées

1. **Exécuter les tests:** Utilisez un fichier .bat ou connectez-vous via SSH
2. **Vérifier la couverture:** `php artisan test --coverage`
3. **Tests d'intégration:** Créer des tests Feature pour le workflow complet
4. **Documentation:** Ajouter des docblocks aux méthodes de tests si nécessaire

---

## 💡 Notes importantes

- **RefreshDatabase:** Tous les tests utilisent ce trait pour isoler les données
- **Factories:** Utilisation extensive pour générer des données de test réalistes
- **Edge cases:** Tests spécifiques pour les cas limites critiques (auto-stockage)
- **Relations complexes:** Tests pour hasManyThrough et belongsToMany avec pivots
