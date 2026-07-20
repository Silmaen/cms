    <div class="separateur-horizontal-trait-mauve"></div>
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
    
<!--{if $menu=="accueil"}-->        
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/jquery.infinitecarousel.js"></script>
    <script src="js/index.js"></script>
    <script>
    $(function(){
        $('#banniere-image').infiniteCarousel({
            displayTime: 6000,
        });
    });
    </script>
<!--{/if}-->
<!--{if $menu=="devis" or $menu=="contact"}-->        
    <script src="js/devis-contact.js"></script>
<!--{/if}-->

<script>
    <script>
    /* pour assurer l'affichage de HTML5 sur IE<9 */
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
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

</script>    
</body>
</html>