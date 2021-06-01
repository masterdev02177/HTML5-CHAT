<?php
	if (empty($_GET['lang'])) {
		$currentLng = 'English';
	} else {
		switch ($_GET['lang']) {
			case 'de_DE':
				$currentLng = 'Deutsch';
				break;

			case 'es_ES':
				$currentLng = 'Español';
				break;

			case 'fr_FR':
				$currentLng = 'Français';
				break;

			case 'ru_RU':
				$currentLng = 'Русский';
				break;

			default:
				$currentLng = 'English';
			break;
		}
	}
?>
<div class="dropdown pull-left lngMenu">
  	<button class="btn header-btn dropdown-toggle" type="button" data-toggle="dropdown">
		<?= $currentLng; ?>
  		<span class="caret"></span>
  	</button>
  	<ul class="dropdown-menu">
  		<li>
  			<a href="?lang=fr_FR">
  				<img class="flag" src="../img/fr.svg" alt="version française">
  				Français
  			</a>
  		</li>
    	<li>
    		<a href="?lang=eng_ENG">
    			<img class="flag" src="../img/en.svg" alt="english version">
    			English
    		</a>
    	</li>
    	<li>
    		<a href="?lang=es_ES">
    			<img class="flag" src="../img/es.svg" alt="Versión española">
    			Español
    		</a>
		</li>
    	<li>
    		<a href="?lang=de_DE">
    			<img class="flag" src="../img/de.svg" alt="Deutsch Version">
    			Deutsch
    		</a>
    	</li>
    	<li>
    		<a href="?lang=ru_RU">
    			<img class="flag" src="../img/ru.svg" alt="Russian Version">
    			Русский
    		</a>
    	</li>
  	</ul>
</div>
