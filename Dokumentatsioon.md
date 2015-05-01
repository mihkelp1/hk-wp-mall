### Alustuseks ###

_Antud juhend eeldab Wordpress platvormiga eelnevat kogemust._

  1. Paigalda Wordpress ja loo võrgustik - [Create a network](http://codex.wordpress.org/Create_A_Network)
  1. Paigalda avalehel toodud lisamoodulid
  1. Loo alam lehekülg/blog/site "en" - http://www.hk.tlu.ee/en/
  1. Impordi XML formaadis sisu või taasta sisu MySQL tõmmisest.
  1. Korda seadistusi/sisu importi http:/www.hk.tlu.ee/en - alamlehe jaoks.

### Üldised Wordpressi seadistused ###
  * Seadista üldinfo kodulehe kohta asukohas "Seaded -> Üldine" - Lühikirjelduse/description välja kasutatakse <description meta tag-is>.

**Kirjutus (Writing) seadistused**

![http://hk-wp-mall.googlecode.com/files/Screen%20shot%202012-10-16%20at%2011.15.12.png](http://hk-wp-mall.googlecode.com/files/Screen%20shot%202012-10-16%20at%2011.15.12.png)

**Meedia (Media) seadistused**

![http://hk-wp-mall.googlecode.com/files/Screen%20shot%202012-10-16%20at%2011.19.12.png](http://hk-wp-mall.googlecode.com/files/Screen%20shot%202012-10-16%20at%2011.19.12.png)


### Media Upload Types moodul ###

On vajalik, et saaks lubada doc, ppt, docx jt. faililaiendite ülelaadimine meedia teegi kaudu. Antud moodul **lisab funktsioone "Seaded->Meedia"** lehele. Vajalikud faililaiendid, mis tuleb lubada:

```
doc	application/msword
xlt	application/vnd.ms-excel
xls	application/vnd.ms-excel
ppt	application/vnd.ms-powerpoint
pot	application/vnd.ms-powerpoint
pptx	application/vnd.openxmlformats-officedocument.presentationml.presentation
potx	application/vnd.openxmlformats-officedocument.presentationml.template
		

```

### People Lists moodul ###

**Vajalikud lisa väljad profiilis:**

  * Telefon
  * GSM
  * Tiitel (ehk positsioon)

```
%people_lists_telefon%
%people_lists_gsm%
%people_lists_tiitel%
```

**Template sisu**

```
<div class ='user-thumbnail'>%thumbnail%</div> 
<div class ='user-info user-name'>%firstname% %lastname%</div> 
<div class ='user-info user-title' style="margin-bottom: 10px">%people_lists_tiitel%</div> 
<div class='user-info'>Telefon: %people_lists_telefon%</div>
<div class='user-info'>GSM: %people_lists_gsm%</div>
<div class ='user-info'>E-post: <a hhref="mailto:%email%" title="%email%">%email%</a></div> 		

<div class='user-info qr'>
<img class='qr-image' src="http://chart.apis.google.com/chart?cht=qr&chs=96x96&chld=L|0&choe=UTF-8&chl=BEGIN%3AVCARD%0AN%3A%firstname%+%lastname%%0ATEL;WORK%3A%people_lists_telefon%%0ATEL;CELL%3A%people_lists_gsm%%0AEMAIL;WORK%3A%email%%0AEND%3AVCARD" />
</div>
```