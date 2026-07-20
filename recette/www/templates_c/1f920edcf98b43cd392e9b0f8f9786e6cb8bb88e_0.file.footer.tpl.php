<?php
/* Smarty version 3.1.32, created on 2018-08-28 07:26:50
  from 'C:\wamp64\www\bootstrap\administrateur\templates\footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b84f93a6bd772_33840658',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f920edcf98b43cd392e9b0f8f9786e6cb8bb88e' => 
    array (
      0 => 'C:\\wamp64\\www\\bootstrap\\administrateur\\templates\\footer.tpl',
      1 => 1391695010,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b84f93a6bd772_33840658 (Smarty_Internal_Template $_smarty_tpl) {
?>    <div class="separateur-horizontal-trait-mauve"></div>
        <footer>
            <div id="adresse">
                AGRIPPA SA<br />
                290, rue Ferdinand Perrier / BP 169<br />
                69800 Saint Priest<br />
                Tél : +33 (0)4 72 79 56 10 / Fax : +33 (0)4 72 79 56 19<br />
                Email : sales@agrippa-sa.com            
            </div>
            <div id="menu-bas-col-a">
                <a class="menu-bas-lien" href="a-propos.php">A PROPOS</a><br />
                <a class="menu-bas-lien" href="equipe.php">EQUIPE</a><br /> 
                <a class="menu-bas-lien" href="partenaires.php">PARTENAIRES</a><br />
            </div>              
            <div id="menu-bas-col-b">
                <a class="menu-bas-lien" href="produits.php">PRODUITS</a><br />
                <a class="menu-bas-lien" href="occasion.php">OCCASIONS</a><br />
                <a class="menu-bas-lien" href="location.php">LOCATIONS</a><br />
                <a class="menu-bas-lien" href="service-apres-vente.php">SAV</a><br />
                <a class="menu-bas-lien" href="actualite.php">ACTUALITES</a><br />
            </div>
            <div id="menu-bas-col-c">
                <a class="menu-bas-lien" href="contact.php">CONTACT</a><br />
                <a class="menu-bas-lien" href="glossaire.php">GLOSSAIRE</a><br />
                <a class="menu-bas-lien" href="plan-du-site.php">PLAN DU SITE</a><br />
                <a class="menu-bas-lien" href="mentions-legales.php">INFOS LEGALES</a><br />
            </div>
        </footer>
    </div>
    
<?php if ($_smarty_tpl->tpl_vars['menu']->value == "accueil") {?>        
    <?php echo '<script'; ?>
 src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/jquery.infinitecarousel.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/index.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
    $(function(){
        $('#banniere-image').infiniteCarousel({
            displayTime: 6000,
        });
    });
    <?php echo '</script'; ?>
>
<?php }
if ($_smarty_tpl->tpl_vars['menu']->value == "devis" || $_smarty_tpl->tpl_vars['menu']->value == "contact") {?>        
    <?php echo '<script'; ?>
 src="js/devis-contact.js"><?php echo '</script'; ?>
>
<?php }?>

<?php echo '<script'; ?>
>
    <?php echo '<script'; ?>
>
    /* pour assurer l'affichage de HTML5 sur IE<9 */
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="http://html5shiv.googlecode.com/svn/trunk/html5.js"><?php echo '</script'; ?>
>
    <![endif]-->


  

// Ce script vise à décaller le chargement des fichiers Javascript non nécessaires dès le début du chargement de la page    
 // Add a script element as a child of the body
 
/* function downloadJSAtOnload() {
 var element = document.createElement("script");
 element.src = "deferredfunctions.js";
 document.body.appendChild(element);
 }

 // Check for browser support of event handling capability
 if (window.addEventListener)
 window.addEventListener("load", downloadJSAtOnload, false);
 else if (window.attachEvent)
 window.attachEvent("onload", downloadJSAtOnload);
 else window.onload = downloadJSAtOnload; */

<?php echo '</script'; ?>
>    
</body>
</html><?php }
}
