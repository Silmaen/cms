# Bugs connus (repérés, non corrigés)

> Recensement issu de l'extraction de la couche métier puis d'un audit complet du code
> (novembre 2026). **Rien n'est corrigé ici** : chaque correction est un changement de
> comportement à traiter séparément, avec un test de non-régression dédié.
>
> Sévérité : 🔴 casse une fonctionnalité ou faille grave · 🟠 comportement faux/silencieux ·
> 🟡 latent / à confirmer. « Vérifié » = rejoué sur le Docker local (MySQL 8.4,
> `ONLY_FULL_GROUP_BY`, `display_errors` actif). Les numéros de ligne datent de l'audit.
>
> Beaucoup de bugs « 🟡 avertissement PHP » deviennent 🔴 en local : avec `display_errors`
> actif, l'avertissement sort avant le `header()` ou le `$pdf->Output()`, ce qui casse la
> redirection ou le PDF. Sur OVH (erreurs masquées), ils restent souvent invisibles.

## Résumé

| Gravité | Bug | Thème |
|:-:|---|---|
| 🔴 | Activer / Archiver / Supprimer depuis une liste met `id_etat` à NULL | Transverses |
| 🔴 | Suppression d'un groupe d'utilisateurs : action jamais appliquée | Transverses |
| 🔴 | Écrans PDF et fiches accessibles sans connexion (données personnelles) | Transverses |
| 🔴 | Tri d'une liste sans préférence enregistrée — colonne d'un autre menu | Transverses |
| 🔴 | `copier-valider` d'un utilisateur — INSERT invalide | Administration |
| 🔴 | `index-test.php` plante toujours | Administration |
| 🔴 | `modifier-rc` — alias SQL sans jointure | Réservations |
| 🔴 | Copier puis « Valider et envoyer » : confirmation de la réservation d'origine | Réservations |
| 🔴 | Fiche adhérent — dons de l'historique toujours à 0 (MySQL 8) | Adhérents (clients) |
| 🔴 | Suppression de pièce jointe : injection SQL et effacement de fichier arbitraire | Articles et pièces jointes |
| 🔴 | Téléversement sans contrôle : un `.php` peut être envoyé puis exécuté | Articles et pièces jointes |
| 🔴 | Dupliquer un inventaire produit un inventaire invisible | Inventaires |
| 🔴 | « Réservations du jour » (manuelle et cron) : PDF jamais généré | Impressions PDF |
| 🔴 | Impressions « sans adhésion » / « sans dons » — `GROUP BY` incomplet | Impressions PDF |
| 🔴 | « État des réservations par article » : avertissements et libellé faux | Impressions PDF |
| 🔴 | Fiche des retours : variable mal orthographiée, pagination fausse | Impressions PDF |
| 🔴 | Aucune ligne trouvée : « TCPDF ERROR: Wrong page number » | Impressions PDF |
| 🟠 | Droit d'écriture hérité du dernier menu visité (élévation de privilège) | Transverses |
| 🟠 | Injection SQL par le tri, le menu et les filtres de liste | Transverses |
| 🟠 | XSS : aucune variable échappée dans les templates | Transverses |
| 🟠 | Aucune protection CSRF | Transverses |
| 🟠 | Groupe utilisateur hors 1/2/3 : toutes les listes plantent | Transverses |
| 🟠 | `GestionDate()` : date mal formée = erreur fatale | Transverses |
| 🟠 | Un compte supprimé ou archivé peut toujours se connecter | Authentification et session |
| 🟠 | Session jamais régénérée, cookie non protégé | Authentification et session |
| 🟠 | Lien de réinitialisation du mot de passe permanent et devinable | Authentification et session |
| 🟠 | `mdp_perdu.php` envoie un mail même pour un e-mail inconnu | Authentification et session |
| 🟠 | Liens envoyés par e-mail figés sur la prod, paramètres non encodés | Authentification et session |
| 🟠 | Stock « Dispo » faux en mode Modifier (chevauchement incomplet) | Réservations |
| 🟠 | Stock faux en mode Copier | Réservations |
| 🟠 | Aucun contrôle serveur des quantités ni des dates | Réservations |
| 🟠 | Adhésion affichée pour la mauvaise année en Copier et RC | Réservations |
| 🟠 | Précédence AND/OR dans le filtre des associations | Réservations |
| 🟠 | Filtre « 6 mois » cassé | Réservations |
| 🟠 | Ajout : la date du don saisie est ignorée | Réservations |
| 🟠 | Dons des années « à créer » calculés sur l'exercice courant | Adhérents (clients) |
| 🟠 | Adhésion fantôme à 0 € créée pour l'année précédente | Adhérents (clients) |
| 🟠 | Dupliquer un client : `id_etat=0` et pas de `cle_client` | Adhérents (clients) |
| 🟠 | Fiche adhérent PDF incomplète | Adhérents (clients) |
| 🟠 | Noms de fichiers mal découpés, fichiers partagés | Articles et pièces jointes |
| 🟠 | Dupliquer un article : `id_etat=0` | Articles et pièces jointes |
| 🟠 | Liste des articles vide si aucun inventaire n'existe | Articles et pièces jointes |
| 🟠 | Création sans reprise des quantités, copie du mauvais inventaire | Inventaires |
| 🟠 | Créer un inventaire réactive les inventaires supprimés | Inventaires |
| 🟠 | Modifier un inventaire efface les lignes des articles archivés depuis | Inventaires |
| 🟠 | Test d'erreur sur la mauvaise variable | Inventaires |
| 🟠 | Pages parasites quasi vides près du bas de page | Impressions PDF |
| 🟠 | Synthèses des dons : clients archivés/supprimés exclus | Impressions PDF |
| 🟠 | Fiche de départ passée non réimprimable | Impressions PDF |
| 🟠 | État par article : articles non réservés absents | Impressions PDF |
| 🟠 | Années fiscales figées dans le formulaire | Impressions PDF |
| 🟡 | Pagination : page 0/négative, table vide, `items_par_page` forgé | Transverses |
| 🟡 | `$action_selectionne` / paramètres lus sans `isset` | Transverses |
| 🟡 | DELETE physique + réinsertion sans transaction | Transverses |
| 🟡 | Fuseau horaire non fixé | Transverses |
| 🟡 | `GestionIdentification()` lit `$_POST` sans `isset()` | Authentification et session |
| 🟡 | `mdp_reinitialiser` : échecs silencieux et HTML cassé | Authentification et session |
| 🟡 | Échec de connexion BDD : identifiant MySQL affiché | Authentification et session |
| 🟡 | Formulaire de groupe : avertissements et droits manquants | Administration |
| 🟡 | Migration en échec : toutes les redirections cassées | Administration |
| 🟡 | Stock disponible — `quantite_rendue` toujours nulle | Réservations |
| 🟡 | Pas d'inventaire validé = liste d'articles vide sans message | Réservations |
| 🟡 | Fiches PDF de réservation vides | Réservations |
| 🟡 | `reservation_confirmation.php` / `confirmation_reservation.php` | Réservations |
| 🟡 | Filtres des réservations — libellés non cliquables | Réservations |
| 🟡 | JavaScript des réservations | Réservations |
| 🟡 | Stock réservé des articles : réservations supprimées comptées | Articles et pièces jointes |
| 🟡 | Mise en page et contenu des PDF | Impressions PDF |
| 🟡 | Cron toujours détecté comme prod | Environnement et dépôt |
| 🟡 | Docker : base et phpMyAdmin exposés sur le réseau local | Environnement et dépôt |
| 🟡 | Fichiers versionnés à tort | Environnement et dépôt |

## Transverses (touchent plusieurs écrans)

### 🔴 Activer / Archiver / Supprimer depuis une liste met `id_etat` à NULL

`www/clients_liste.php:85`, `articles_liste.php:86`, `inventaires_liste.php:85`,
`reservations_liste.php:189`, `admin_utilisateurs_liste.php:88`.

`GestionSuppression(..., $_GET["id_xxx"], '', $_GET["action"])` : l'action est passée en
6ᵉ argument (`$nom_table_bis`) au lieu du 5ᵉ (`$action`). `$id_etat` n'est jamais défini et
l'UPDATE écrit `id_etat=NULL` : la ligne disparaît de toutes les listes (filtres
`id_etat<=X`), sans message, et ne peut plus être réactivée depuis l'interface. Avec
« Suppr. & Email », le mail d'annulation part quand même. **Vérifié** : 22 réservations à
`id_etat` NULL en base locale (juillet → octobre 2026). Correctif : passer l'action en
5ᵉ argument, puis remettre à niveau les lignes NULL.

### 🔴 Suppression d'un groupe d'utilisateurs : action jamais appliquée

`www/admin_utilisateurs_groupes_liste.php:85` : `GestionSuppression(..., "admin_menus_groupes")`
passe un nom de table comme `$action`. De plus `admin_utilisateurs_groupes` n'a pas de
colonne `id_etat` (à confirmer en base réelle) : l'UPDATE échoue.

### 🔴 Écrans PDF et fiches accessibles sans connexion (données personnelles)

Aucun appel à `gestionIdentification()` dans les 9 `www/impressions_*.php`,
`fiche_client.php`, `fiche_reservation.php`, `fiche_inventaire.php`,
`fiche_inventaire-modeportrait.php`. **Vérifié** : un curl sans cookie récupère les PDF
(noms, adresses, téléphones, e-mails, dons) ; les identifiants et les dates s'énumèrent.
Le lien e-mail vers `fiche_reservation.php` porte `cle_client` mais ne la vérifie pas
(`:58`). Les éditions financières ne sont masquées que dans la vue
(`impressions.tpl:41,133,233,279,328`), sans contrôle de groupe côté PHP. Les liens du cron
(`reservations_maj_automatique.php:58,61`) visent ces écrans publics. Correctif : garde
standard, contrôle de groupe ≤ 1 sur les éditions financières, jeton signé pour les liens
envoyés par e-mail.

### 🟠 Droit d'écriture hérité du dernier menu visité (élévation de privilège)

`GestionMenusDroits()` (`fonctions_general.php:33-43`) n'est appelée que dans les
`*_liste.php` et ne remet pas `$_SESSION["droit"]` à 0 quand aucune ligne n'existe. Les
formulaires et `fichier_*.php` testent seulement `$_SESSION["droit"]==1`. Un utilisateur
ayant l'écriture sur un seul menu peut écrire partout, y compris POSTer
`admin_utilisateurs_formulaire.php` avec `id_utilisateur_groupe=1` et **devenir
administrateur**. `admin_utilisateurs_liste.php` est ouvert à tout connecté (seul le lien
du menu est masqué, `header.tpl:96`). Sans droit, un `modifier-valider` avec `envoyer=oui`
envoie quand même le mail de confirmation (`reservations_formulaire.php:719-777`).

### 🟠 Injection SQL par le tri, le menu et les filtres de liste

- `reservations_liste.php:230-231`, `:236-237` : `$_GET["id_client"]` / `$_GET["id_article"]`
  concaténés tels quels dans le SQL (contredit `securite-sql.md`).
- `GestionTri` (`fonctions_general.php:184-213`) : `colonne` réinjectée brute quand aucune
  ligne ne correspond ; `sens_tri` stocké tel quel en base puis concaténé dans `ORDER BY`
  (`metier/clients.php:39`, `articles.php:18`, `inventaires.php:42`, `utilisateurs.php:15,34`,
  `reservations_liste.php:230-242`) : injection **persistante**, et une valeur invalide casse
  définitivement la liste de l'utilisateur.
- `admin_*_liste.php:17` : `$_GET["id_admin_menu"]` copié en session puis concaténé
  (`fonctions_general.php:35,201,213`).
- `articles_liste.php:93-99` / `metier/articles.php:40` : `id_inventaire` concaténé.

Correctif : requêtes préparées, `(int)`, listes blanches colonne / `ASC|DESC`.

### 🟠 XSS : aucune variable échappée dans les templates

`escape_html` n'est pas activé (`config_general.php`) et aucun `|escape` dans les `.tpl`.
XSS **stockée** via nom/association/commentaire (`clients_liste.tpl:158-161`,
`clients_formulaire.tpl:50,108`, `reservations_liste.tpl:188,190`,
`reservations_formulaire.tpl:62,83,226,272-340`, mails HTML `reservations_formulaire.php:761`).
XSS **réfléchie** : `admin_utilisateurs_liste.php:77` (`"Email envoyé à ".$_GET["email"]`,
affiché par `header.tpl:137`) et `mdp_reinitialiser.tpl:14-15` (page publique). Dans
`articles_formulaire.tpl:92,113`, un nom de fichier avec apostrophe casse le JS de l'uploader.
Correctif : `$smarty->escape_html = true` en vérifiant les variables HTML volontaires
(`$picto_etat`).

### 🟠 Aucune protection CSRF

Les mutations passent par des liens GET (`*_liste.php?action=supprimer&id_…`,
`reservation_confirmation.php`, déconnexion) et aucun formulaire POST n'a de jeton.
`SameSite=Lax` laisse passer les GET de navigation : un lien piégé cliqué par un
utilisateur connecté suffit à archiver/supprimer ou envoyer un mail.

### 🔴 Tri d'une liste sans préférence enregistrée — colonne d'un autre menu

`GestionTri()` (`fonctions_general.php:172-226`) : `$colonne_default` est reçu mais jamais
utilisé. Sans ligne `tri_utilisateur=1` pour le menu affiché (utilisateur récent, ou après
une URL de tri invalide qui remet tout à 0), `$_SESSION["colonne"]` garde la colonne du menu
précédent : requête en échec (« Unknown column 'nom_utilisateur' »), liste vide.

### 🟠 Groupe utilisateur hors 1/2/3 : toutes les listes plantent

`GestionUtilisateursEtats()` (`fonctions_general.php:229-235`) n'a pas de valeur par défaut :
pour un groupe créé via l'écran Groupes, `WHERE id_etat<=` vide → erreur SQL sur les listes
utilisateurs, clients, réservations, articles, inventaires.

### 🟡 Pagination : page 0/négative, table vide, `items_par_page` forgé

- `metier/commun.php:24-32`, `reservations_liste.php:159-171` : `?page=0`, page négative ou
  `nombre_de_pages=0` (table vide) → `LIMIT` négatif, erreur SQL, liste vide.
- `GestionPagination` (`fonctions_general.php:109`) compte toute la table, lignes supprimées
  et filtres ignorés : pages vides en fin de liste. Les `$sql_pagination` construits dans
  `reservations_liste.php:231,237,243` ne servent jamais.
- `items_par_page` posté sans contrôle dans `GestionTri` (`:189-197`) : 0 →
  `DivisionByZeroError` fatale à chaque visite (`:127`) ; négatif → erreur SQL.
- Tri « association » (`metier/clients.php:21,30`) : deux requêtes `LIMIT` concaténées, la
  page affiche jusqu'à 2× trop de lignes.

### 🟠 `GestionDate()` : date mal formée = erreur fatale

`fonctions_general.php:89-96` : `->format()` sur le `false` de `createFromFormat()`.
**Vérifié** (`impressions_reservations_jour.php` avec `2026-11-05`). Les champs de date
sont de simples `type="text"` (`reservations_formulaire.tpl:151,170,177,196`) : taper
`05/11/2026` fait planter l'enregistrement. Même fragilité : `reservations_formulaire.php:57-61`
(`explode` sans contrôle).

### 🟡 `$action_selectionne` / paramètres lus sans `isset`

`clients_formulaire.php:407`, `articles_formulaire.php:295`, `inventaires_formulaire.php:58,302`,
`reservations_formulaire.php:719`, et toutes les impressions (`$_GET`/`$_POST` sans test,
**vérifié** sur `impressions_automatique_reservations_jour.php:101`). Dans les branches
`*-valider` sans droit, rien n'est enregistré mais la redirection a lieu sans message.

### 🟡 DELETE physique + réinsertion sans transaction

`metier/reservations.php:76` (`reservations_articles`), `metier/inventaires.php:163`
(`inventaires_articles`, voir aussi la section Inventaires), `fichier_supprimer.php:88`
(`fichiers`). Tables MyISAM : un échec en cours de boucle perd les lignes, et les INSERT ne
sont pas testés.

### 🟡 Fuseau horaire non fixé

Aucun `date_default_timezone_set` ; PHP tourne en UTC dans le Docker (vérifié). Entre 0 h et
2 h (Paris), `date()` donne la veille : `annee_courante`, dates par défaut, cron
(`reservations_maj_automatique.php:26`). Valeur OVH à confirmer.

## Authentification et session

### 🟠 Un compte supprimé ou archivé peut toujours se connecter

`fonctions_general.php:50` : le `WHERE` de connexion ne teste pas `t1.id_etat`.

### 🟠 Session jamais régénérée, cookie non protégé

Aucun `session_regenerate_id` après connexion (`fonctions_general.php:66-76`) ; la
déconnexion (`index.php:15-17`) ne supprime pas le cookie ; `use_strict_mode`,
`cookie_httponly` et `cookie_secure` non activés (`config_general.php:4-5`) → fixation de
session, cookie lisible en cas de XSS.

### 🟠 Lien de réinitialisation du mot de passe permanent et devinable

`mdp_reinitialiser.php:13-22` : le jeton est `cle_utilisateur = uniqid()` créé une fois à la
création du compte (`admin_utilisateurs_formulaire.php:134`) — basé sur l'horloge, sans
expiration, jamais renouvelé, sans limite de tentatives.

### 🟠 `mdp_perdu.php` envoie un mail même pour un e-mail inconnu

`mdp_perdu.php:7-44` : aucune garde, `GestionEnvoiMail` part vers l'adresse saisie (y compris
en GET, sans authentification) avec des variables non définies. Avec les erreurs affichées,
les avertissements révèlent si le compte existe.

### 🟠 Liens envoyés par e-mail figés sur la prod, paramètres non encodés

`mdp_perdu.php:26` (`http://www.cdf-genay.com/...&email=` brut), `admin_utilisateurs_liste.tpl:166`,
`reservations_maj_automatique.php:58` (http) et `:61` (https). Depuis recette/test, le lien
pointe vers la prod (si la base recette est une copie, il réinitialise le mot de passe de
prod — à confirmer). Un e-mail contenant `+` devient un espace : réinitialisation en échec
silencieux.

### 🟡 `GestionIdentification()` lit `$_POST` sans `isset()`

`fonctions_general.php:52`. Sans session, avec `display_errors` actif, les avertissements
précèdent le `header("Location:index.php")` : page blanche au lieu de la connexion.

### 🟡 `mdp_reinitialiser` : échecs silencieux et HTML cassé

`mdp_reinitialiser.php:6-7,13-30` : le formulaire poste sans la query-string, donc
`$_GET["cle"]`/`["email"]` absents au POST ; clé fausse → aucun message. Politique de mot de
passe uniquement côté client (`pattern`). `mdp_reinitialiser.tpl:56` : `<div … style="…"`
sans `>`, le message de succès est absorbé.

### 🟡 Échec de connexion BDD : identifiant MySQL affiché

`config_general.php:41` : `printf(... $e->getMessage())` publie utilisateur et hôte MySQL sur
une page publique.

## Administration

### 🔴 `copier-valider` d'un utilisateur — INSERT invalide

`admin_utilisateurs_formulaire.php:155` : `... :id_utilisateur_groupe, , :date_creation ...`
— le `, ,` rend le SQL invalide, la duplication échoue toujours. Même corrigé, l'INSERT ne
renseigne ni `id_etat` (utilisateur invisible) ni `cle_utilisateur` (réinitialisation
impossible) : réutiliser `UtilisateurAjouter()`.

### 🔴 `index-test.php` plante toujours

`www/index-test.php:19` : `display('index-test.tpl')` alors que le fichier est
`index.test.tpl` → SmartyException (trace et chemins affichés si erreurs visibles). Page
publique qui détruit aussi la session de qui l'ouvre.

### 🟡 Formulaire de groupe : avertissements et droits manquants

`admin_utilisateurs_groupes_formulaire.php:144-145,172-173,197-198` : `$tab_info[0]` sur le
champ caché `ordre` vide → « Uninitialized string offset », qui casse le `header()` (`:216`)
en local. `modifier-valider` ne fait que des UPDATE : un menu ajouté après la création du
groupe ne reçoit jamais de droit.

### 🟡 Migration en échec : toutes les redirections cassées

`metier/schema.php:97-111` : l'`echo "Schéma : échec…"` sort à chaque requête avant tout
`header()`, la migration est retentée à chaque requête (jusqu'à 10 s d'attente sur
`GET_LOCK`, résultat non testé). Droits `CREATE` de l'utilisateur OVH à confirmer.

## Réservations

### 🔴 `modifier-rc` — alias SQL sans jointure

`reservations_formulaire.php:458` : `t6.libelle_statut` sélectionné sans `LEFT JOIN
clients_statuts AS t6`. **Vérifié** : « Unknown column » ; la liste d'articles du mode
Réservation Classique ne s'affiche jamais.

### 🔴 Copier puis « Valider et envoyer » : confirmation de la réservation d'origine

`reservations_formulaire.php:725-726` : `execute([":id_reservation"=>…])` sur une requête
sans paramètre → **vérifié** HY093. `$_POST["id_reservation"]` garde l'id de la source : le
client reçoit le lien et les dates de l'**ancienne** réservation ; l'`echo` d'erreur casse
ensuite le `header()` (`:774`). La logique « dernière réservation active de la table » est
de toute façon fausse : utiliser `$id_reservation_selectionne`.

### 🟠 Stock « Dispo » faux en mode Modifier (chevauchement incomplet)

`reservations_formulaire.php:391` : `(depart<=D AND retour>D) OR (depart>=D AND retour<=D)` —
la 2ᵉ branche n'est vraie que si départ = retour = D. Les réservations qui commencent
pendant la période ne sont pas comptées : stock surestimé, surbooking possible. Les modes RC
(`:537,602`) et l'affichage après ajout (`:847`) utilisent `depart<R AND retour>D`.

### 🟠 Stock faux en mode Copier

`reservations_formulaire.php:258-271` : ajoute la `quantite_reservee` de la réservation
source alors que la source n'est pas comptée sur les nouvelles dates : stock fictif.

### 🟠 Aucun contrôle serveur des quantités ni des dates

`reservations_formulaire.php:669-672,688-691,709-712` : ni `qte ≤ dispo`, ni `qte ≥ 0`, ni
`départ < retour`. Seul le JS protège. En RC les dates restent modifiables alors que le
stock affiché correspond aux anciennes ; deux saisies simultanées peuvent réserver le même
matériel.

### 🟠 Adhésion affichée pour la mauvaise année en Copier et RC

`reservations_formulaire.php:227,515` : `date("Y")` au lieu de `$annee_courante` (exercice
octobre→septembre, utilisé en Modifier `:368`). De janvier à septembre, adhésion affichée à 0.

### 🟠 Précédence AND/OR dans le filtre des associations

`reservations_formulaire.php:934,950` : `id_etat='1' AND id_statut_client=3 OR … =4 OR … =5`
inclut les clients de statut 4/5 (et 1/2 dans la seconde) quel que soit leur état.

### 🟠 Filtre « 6 mois » cassé

`reservations_liste.php:106` (groupe 3) : `date_depart>=05-05-2026` sans guillemets, évalué
en arithmétique → **vérifié** 0 ligne au lieu de 19. `:110` (groupe 2) : aucun filtre de
date, « 6 mois » affiche tout.

### 🟠 Ajout : la date du don saisie est ignorée

`reservations_formulaire.php:661` : la date du don enregistrée est `date_retour`, alors que
le formulaire affiche un champ « Date du don » modifiable. Intention à confirmer.

### 🟡 Stock disponible — `quantite_rendue` toujours nulle

`reservations_formulaire.php:423` : `$row_rendu["quantite_rendue"] ?? 0` — clé inexistante,
le terme vaut toujours 0 ; la boucle `:416-418` garde la dernière ligne au lieu de sommer.
À confirmer métier.

### 🟡 Pas d'inventaire validé = liste d'articles vide sans message

`reservations_formulaire.php:104-114` : `$id_inventaire_selectionne` défini seulement s'il
existe un inventaire `statut=2, etat=1`.

### 🟡 Fiches PDF de réservation vides

`fiche_reservation.php:168-174`, `fiche_reservation_client.php:190` : `AddPage()` et l'en-tête
ne sont générés que dans la boucle des articles : réservation sans article ou id invalide →
PDF blanc (et variables non définies).

### 🟡 `reservation_confirmation.php` / `confirmation_reservation.php`

`$_GET["email"]` jamais fourni par le lien de la liste (avertissement avant `header()`) ; id
inconnu → `mail()` vers un destinataire vide sans redirection ; la branche `else` redirige
vers `admin_utilisateurs_liste.php` (copier-coller). `confirmation_reservation.php:26` lit
`email_utilisateur` absent du SELECT ; fichier apparemment plus appelé.

### 🟡 Filtres des réservations — libellés non cliquables

`reservations_liste.tpl:20,24,28,32` : `for="filtre_reservations"` alors que les radios sont
`filtre_reservations_1/_3/_4/_5`.

### 🟡 JavaScript des réservations

- `scripts.js:146-155` : `#id_client` et `#id_client_association` se déclenchent l'un
  l'autre via `.val(v).change()` → « Maximum call stack size exceeded » à chaque sélection.
- `scripts.js:85-91,124-129` : `nb_jours` non défini si le départ tapé au clavier n'est ni un
  lundi ni un vendredi → la date de retour n'est pas recalculée.
- `reservations_formulaire.tpl:371,645` : `<a href="" onclick="Envoyer();">` sans
  `preventDefault`, la navigation peut concurrencer le submit (à confirmer selon navigateur).

## Adhérents (clients)

### 🔴 Fiche adhérent — dons de l'historique toujours à 0 (MySQL 8)

`clients_formulaire.php:226,267` : `GROUP BY t1.annee_reservation DESC`, syntaxe supprimée en
MySQL 8.0.13 : la colonne « Dons € » reste à 0.

### 🟠 Dons des années « à créer » calculés sur l'exercice courant

`clients_formulaire.php:214-228` : dans la boucle, les bornes d'exercice restent celles de
l'année courante (fixées `:176-187`). Masqué par le bug précédent : une fois celui-ci
corrigé, chaque année manquante affichera les dons de l'année courante. Le `GROUP BY` peut
aussi renvoyer plusieurs lignes dont seule la dernière est gardée (`:234-238`), et les bornes
portent un suffixe `' <br />'` (`:179-180,258-259`).

### 🟠 Adhésion fantôme à 0 € créée pour l'année précédente

`clients_formulaire.php:376-399` : pour un client sans adhésion récente, la ligne N-1 à
`id_adhesion=0` déclenche `ClientAdhesionAjouter(..., montant 0)` à chaque enregistrement.
Les lignes d'indice ≥ 2 sont ignorées. Impact selon que « adhérent » repose sur l'existence
de la ligne ou sur `montant>0` (à confirmer).

### 🟠 Dupliquer un client : `id_etat=0` et pas de `cle_client`

`metier/clients.php:115` (`ClientCopier`) : client affiché « Supprimé », exclu des
réservations (`reservations_formulaire.php:914`), lien de confirmation client inopérant
(`fiche_reservation_client.php:54`).

### 🟠 Fiche adhérent PDF incomplète

`fiche_client.php:96` : les années avec adhésion mais sans don n'apparaissent pas ;
`:299` `montant_adhesion` non défini pour une année sans adhésion (casse le PDF en local) ;
id absent → variables non définies (`:210-211`). `:317-318` : `strtr()` octet par octet sur
de l'UTF-8 abîme le nom du fichier pour les noms accentués.

## Articles et pièces jointes

### 🔴 Suppression de pièce jointe : injection SQL et effacement de fichier arbitraire

`fichier_supprimer.php:71` : `WHERE (id_fichier='".$_POST["key"]."')`, puis
`unlink("fichiers/".$row["nom_fichier"].".".$row["extension"])` (`:81-82`). Un `UNION SELECT`
permet d'effacer n'importe quel fichier du serveur (ex. `config_secrets.php`) ; il faut le
droit 1, hérité d'un autre menu (voir Transverses). DELETE physique `:88`.

### 🔴 Téléversement sans contrôle : un `.php` peut être envoyé puis exécuté

`fichier_ajouter.php:20-22` : nom d'origine conservé, ni liste blanche d'extensions, ni
limite de taille, ni `.htaccess` dans `www/fichiers/` (également versionné et servi). Retour
de `move_uploaded_file` non testé. RCE à confirmer sur OVH.

### 🟠 Noms de fichiers mal découpés, fichiers partagés

`fichier_ajouter.php:24-26` : `explode(".")[0]/[1]` — `photo.2024.jpg` est enregistré
`photo`+`2024` (aperçu et suppression cassés) ; nom sans point → avertissement ; deux
articles avec un même nom partagent le fichier (en supprimer un efface l'autre). Si `unlink`
échoue, deux JSON sont renvoyés (`fichier_supprimer.php:84,97`).

### 🟠 Dupliquer un article : `id_etat=0`

`metier/articles.php:51` (`ArticleCopier`) : l'article copié est exclu des inventaires
(`WHERE t1.id_etat='1'`). En mode copier, l'uploader de l'article **source** est affiché
(`articles_formulaire.php:207`, `articles_formulaire.tpl:73`) : les fichiers ajoutés
s'attachent à la source.

### 🟠 Liste des articles vide si aucun inventaire n'existe

`articles_liste.php:93-99`, `metier/articles.php:40` : `id_inventaire=` vide dans le SQL →
chaque article échoue et est retiré, N messages « LISTE 2 ».

### 🟡 Stock réservé des articles : réservations supprimées comptées

`metier/articles.php:62` : pas de filtre `t2.id_etat=1`, bornes strictes excluant les
départs/retours du jour. Sans effet visible aujourd'hui (`quantite_actuelle` non affiché).

## Inventaires

### 🔴 Dupliquer un inventaire produit un inventaire invisible

`metier/inventaires.php:112` (`InventaireCopier`) : `id_etat` omis (défaut NULL), la copie
n'apparaît jamais ; `InventaireMajEtatAnciens` (`inventaires_formulaire.php:275`) archive en
plus les précédents → plus aucun inventaire actif, `fiche_inventaire.php?type=vide` (`:49`)
ne trouve rien.

### 🟠 Création sans reprise des quantités, copie du mauvais inventaire

`inventaires_formulaire.php:58` : `!="modifier" AND =="copier"` équivaut à `=="copier"`. En
« ajouter », `quantite_precedent` vaut 0 partout ; en « copier » depuis la ligne X, l'id est
remplacé par le dernier inventaire actif.

### 🟠 Créer un inventaire réactive les inventaires supprimés

`metier/inventaires.php:151` : `UPDATE … SET id_etat='2' WHERE date_inventaire<…` sans
`AND id_etat=1` : les inventaires supprimés antérieurs redeviennent archivés et visibles.

### 🟠 Modifier un inventaire efface les lignes des articles archivés depuis

`inventaires_formulaire.php:289` / `metier/inventaires.php:163` : DELETE puis réinsertion des
seules lignes postées (articles `id_etat='1'`, `:203`) : l'historique de stock des articles
archivés est perdu ; sans transaction, un échec vide l'inventaire.

### 🟠 Test d'erreur sur la mauvaise variable

`inventaires_formulaire.php:98,156,212`, `fiche_inventaire.php:106`,
`fiche_inventaire-modeportrait.php:106` : `if(!$sql_exec)` teste la requête précédente, une
panne de la sous-requête passe inaperçue.

## Impressions PDF

### 🔴 « Réservations du jour » (manuelle et cron) : PDF jamais généré

`impressions_reservations_jour.php:148`, `impressions_automatique_reservations_jour.php:146` :
`SELECT COUNT(t1.id_article), t1.quantite_reservee` sans `GROUP BY` → erreur 1140 en
`ONLY_FULL_GROUP_BY` dès qu'il y a une réservation (**vérifié**). Sur OVH, selon le `sql_mode`.

### 🔴 Impressions « sans adhésion » / « sans dons » — `GROUP BY` incomplet

`impressions_reservations_sans_adhesions.php:96-97`, `impressions_reservations_sans_dons.php:97-98` :
colonnes hors `GROUP BY` → **vérifié** « Pb d'accès à la table clients » + « TCPDF ERROR ».

### 🔴 « État des réservations par article » : avertissements et libellé faux

`impressions_articles_jour.php:168` : `$row["association"]` jamais sélectionné (`:110`) → le
libellé est toujours « nom prénom » ; `:157,160` : totaux non initialisés. **Vérifié** : ~800
avertissements puis « TCPDF ERROR » en local.

### 🔴 Fiche des retours : variable mal orthographiée, pagination fausse

`impressions_reservations_retour.php:151` : `$compteur_resevations` (au lieu de
`$compteur_reservation`) → avertissement par ligne (pas de PDF en local), titre redessiné à
chaque ligne sur OVH. `:205` : `$compteur_page = 0` dans le `foreach` → pas d'en-tête de
colonnes à partir de la page 2.

### 🔴 Aucune ligne trouvée : « TCPDF ERROR: Wrong page number »

`impressions_adhesions_exercice.php:174-180`, `impressions_reglements_recus.php:219-221`,
`impressions_reglements_detail.php:213-218` : totaux écrits sans `AddPage()` préalable
(**vérifié** sur un exercice sans adhésion ou une période sans don). Aucun contrôle début ≤ fin.

### 🟠 Pages parasites quasi vides près du bas de page

Mesuré sur TCPDF 6.11.4 : une `MultiCell` passée sous y≈268 (portrait) / y≈179 (paysage)
déclenche un saut de page **par cellule**. Touchés : `impressions_reservations_retour.php`
(dès la 22ᵉ réservation du jour), `impressions_reservations_sans_dons.php` (lignes 34-35 de
chaque page), `impressions_adhesions_exercice.php` (34ᵉ ligne des pages ≥ 2).

### 🟠 Synthèses des dons : clients archivés/supprimés exclus

`impressions_reglements_recus.php:104`, `impressions_reglements_detail.php:104` :
`t1.id_etat=1` porte sur la table `clients`. En local, 9 dons (385 €) manquent au total.
À confirmer métier.

### 🟠 Fiche de départ passée non réimprimable

`impressions_reservations_jour.php:102,126` (et cron), `impressions_articles_jour.php:110` :
filtre `id_etat=1`, or le cron archive toute réservation dont le retour est passé ; une
réservation d'un jour disparaît de la fiche du jour après le cron. À confirmer métier.

### 🟠 État par article : articles non réservés absents

`impressions_articles_jour.php:110` : conditions sur les tables jointes dans le `WHERE`, les
`LEFT JOIN` deviennent internes ; la branche « en stock non réservé » (`:209-224`) ne sert
quasiment jamais.

### 🟠 Années fiscales figées dans le formulaire

`impressions.tpl:243-251,289-297,338-346` : options 2018→2026, `selected` sur 2024 : en
novembre 2026 l'exercice par défaut a deux ans de retard, 2027 n'est pas sélectionnable.

### 🟡 Mise en page et contenu des PDF

- Bloc « Créé le / Imprimé le » hors de la page (x=198, largeur 85 sur A4 portrait) :
  `impressions_adhesions_exercice.php:150`, `…sans_adhesions.php:151`, `…sans_dons.php:153`,
  `impressions_reglements_recus.php:171`, `impressions_reglements_detail.php:168`.
- PDF blanc sans message quand aucun résultat : `impressions_articles_jour.php`,
  `…sans_adhesions.php`, `…sans_dons.php`.
- Fiche du jour : page blanche intercalée pour un multiple de 20 articles
  (`impressions_reservations_jour.php:299-304`, cron `:298-303`) ; commentaire long qui
  chevauche le message sur les dons (`:245` / `:251`).
- Texte saisi injecté en mode HTML dans `MultiCell` (désignation, nom, commentaire) : un `<`
  ou `&` tronque le texte.
- `…sans_dons.php:98` : `HAVING SUM(don)=0` ne correspond pas au titre (« au moins une
  réservation sans don ») et exclut les `NULL` ; `…sans_adhesions.php:97` : `NOT IN` vidé
  par un seul `NULL`. À confirmer métier.
- Libellés : « Nb7 » (`…sans_dons.php:145`), « Détail des donc »
  (`impressions_reglements_detail.php:209`), « 31/09 » (`impressions_adhesions_exercice.php:180`) ;
  ids dupliqués dans `impressions.tpl:242,337` (`annee_fiscale_reference_adhesion`, `action`,
  `btnSubmit`).

## Environnement et dépôt

### 🟡 Cron toujours détecté comme prod

`cgi-bin/config/environnement.php:42-44` : en CLI sans `HTTP_HOST`, l'environnement vaut
`prod`, y compris sur recette/test. Sans effet aujourd'hui hormis le thème.

### 🟡 Docker : base et phpMyAdmin exposés sur le réseau local

`docker-compose.yml` : ports `3307` et `8082` sur toutes les interfaces, `root/root`, base
locale contenant des données personnelles. Préfixer par `127.0.0.1:`.

### 🟡 Fichiers versionnés à tort

`www/fichiers/*` (dont « assurance Barnum.pdf »), `vendor/smarty/libs - Verknüpfung.lnk`,
`www/assets/vendor/fontawesome/css/all520 - Copie.css`. Les `.htaccess` deny de
`templates_c` et `cache` sont inactifs en Docker (`AllowOverride None`).

