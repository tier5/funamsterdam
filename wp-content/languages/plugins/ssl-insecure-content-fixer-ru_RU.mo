��    H      \  a   �         1   !     S  n   h  �   �  i   p  g   �  j   B  #   �     �     �  5   �  �   3	  �   �	  #   A
      e
  l   �
     �
  T     ;   Y     �  Y   �  S   �  #   L     p     �     �  I   �  6     5   F  4   |     �     �  Q   �  R   4     �  [   �  a   �  ^   `  �   �  ;   T  ^   �  .   �  +     0   J  q   {  s   �     a          �     �     �     �  !   	     +     G     e  9   �  =   �  ^   �  0   [     �  w   �  W   #  1   {  P   �  8   �  Q   7  *   �  %   �  #   �  1   �  }  0  `   �  @     �   P  �   �  �   �  }   �  �     F   �     �  C     `   F  �   �  �   d  W   a  M   �  �      $   �   q   �   t   [!     �!  �   �!  y   g"  7   �"     #     3#     M#  J   b#  C   �#  4   �#  K   &$     r$     �$  u   �$  $   
%     /%  ~   >%  p   �%  �   .&  �   �&  K   �'  �   �'  ,   �(  +   �(  7   �(  }   )  w   �)     *     /*  +   L*     x*     �*     �*     �*     �*     �*     �*  K   +  _   Z+  b   �+  K   ,     i,  �   �,  h   G-  "   �-  a   �-  :   5.  x   p.  /   �.  /   /  6   I/  <   �/     "           5         ;   1   (   ?      !      '       :   G              	   4                3   H   C   ,   A                        9   7   #      >      %   
       F             B   +   -   8   2       &   6      /             @                      =      <       0   $          D           E          *       )   .                                  Clean up WordPress website HTTPS insecure content Fix insecure content If you know of a way to detect HTTPS on your server, please <a href="%s" target="_blank">tell me about it</a>. It looks like your server is behind Amazon CloudFront, not configured to send HTTP_X_FORWARDED_PROTO. The recommended setting for HTTPS detection is %s. It looks like your server is behind Windows Azure ARR. The recommended setting for HTTPS detection is %s. It looks like your server is behind a reverse proxy. The recommended setting for HTTPS detection is %s. It looks like your server uses Cloudflare Flexible SSL. The recommended setting for HTTPS detection is %s. Multisite network settings updated. Running tests... SSL Insecure Content Fixer SSL Insecure Content Fixer multisite network settings SSL Insecure Content Fixer requires <a target="_blank" href="%1$s">PCRE</a> version %2$s or higher; your website has PCRE version %3$s SSL Insecure Content Fixer requires these missing PHP extensions. Please contact your website host to have these extensions installed. SSL Insecure Content Fixer settings SSL Insecure Content Fixer tests Select the level of fixing. Try the Simple level first, it has the least impact on your website performance. Tests completed. These settings affect all sites on this network that have not been set individually. This page checks to see whether WordPress can detect HTTPS. WebAware Your server can detect HTTPS normally. The recommended setting for HTTPS detection is %s. Your server cannot detect HTTPS. The recommended setting for HTTPS detection is %s. Your server environment shows this: fix level settingsCapture fix level settingsCapture All fix level settingsContent fix level settingsEverything on the page, from the header to the footer: fix level settingsEverything that Content does, plus: fix level settingsEverything that Simple does, plus: fix level settingsNo insecure content will be fixed fix level settingsOff fix level settingsSimple fix level settingsThe biggest potential to break things, but sometimes necessary fix level settingsThe fastest method with the least impact on website performance fix level settingsWidgets fix level settingscapture the whole page and fix scripts, stylesheets, and other resources fix level settingsdata returned from <code>wp_upload_dir()</code> (e.g. for some CAPTCHA images) fix level settingsexcludes AJAX calls, which can cause compatibility and performance problems fix level settingsimages and other media loaded by calling <code>wp_get_attachment_image()</code>, <code>wp_get_attachment_image_src()</code>, etc. fix level settingsimages loaded by the plugin Image Widget fix level settingsincludes AJAX calls, which can cause compatibility and performance problems fix level settingsresources in "Text" widgets fix level settingsresources in any widgets fix level settingsresources in the page content fix level settingsscripts registered using <code>wp_register_script()</code> or <code>wp_enqueue_script()</code> fix level settingsstylesheets registered using <code>wp_register_style()</code> or <code>wp_enqueue_style()</code> https://shop.webaware.com.au/ https://ssl.webaware.net.au/ menu linkSSL Insecure Content menu linkSSL Tests plugin details linksDonate plugin details linksGet help plugin details linksInstructions plugin details linksRating plugin details linksSettings plugin details linksTranslate plugin fix settingsFixes for specific plugins and themes plugin fix settingsSelect only the fixes your website needs. plugin fix settingsWooCommerce  + Google Chrome HTTP_HTTPS bug (fixed in WooCommerce v2.3.13) proxy settings* detected as recommended setting proxy settingsHTTPS detection proxy settingsHTTP_CF_VISITOR (Cloudflare Flexible SSL); deprecated, since Cloudflare sends HTTP_X_FORWARDED_PROTO now proxy settingsHTTP_CLOUDFRONT_FORWARDED_PROTO (Amazon CloudFront HTTPS cached content) proxy settingsHTTP_X_ARR_SSL (Windows Azure ARR) proxy settingsHTTP_X_FORWARDED_PROTO (e.g. load balancer, reverse proxy, NginX) proxy settingsHTTP_X_FORWARDED_SSL (e.g. reverse proxy) proxy settingsSelect how WordPress should detect that a page is loaded via HTTPS proxy settingsstandard WordPress function proxy settingsunable to detect HTTPS settings errorFix level is invalid settings errorHTTPS detection setting is invalid PO-Revision-Date: 2017-05-15 00:34:15+0000
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);
X-Generator: GlotPress/2.4.0-alpha
Language: ru
Project-Id-Version: Plugins - SSL Insecure Content Fixer - Stable (latest release)
 Очистка небезопасного содержимого HTTPS на сайте WordPress Исправить небезопасное содержимое Если Вы знаете как определить HTTPS на Вашем сервере, <a href="%s" target="_blank">пожалуйста сообщите мне</a>. Похоже что ваш сайт использует Amazon CloudFront несконфигурированый на использование HTTP_X_FORWARDED_PROTO. Рекомендуется определение HTTPS методом %s.  Похоже ваш сервер находится за  Windows Azure ARR. Рекомендуемая настройка для определения HTTPS - %s. Похоже у Вас реверс-прокси. Рекомендуется определение HTTPS методом %s.  Похоже что используется CloudFlare Flexible SSL. Рекомендуется определение HTTPS методом %s.  Настройки сети мультисайта обновлены. Проверка... Фильтр небезопасного содержимого SSL  Фильтр небезопасного содержимого SSL для мультисайта SSL Insecure Content Fixer требует <a target="_blank" href="%1$s">PCRE</a> версии %2$s или выше; у вас на сайте установлена PCRE версии %3$s SSL Insecure Content Fixer требует этих расширений PHP, но они отсутствуют. Обратитесь в техподдержку вашего хостинга чтобы установить эти расширения. Настройки фильтра небезопасного содержимого SSL Тест фильтра небезопасного содержимого SSL Выбрать уровень исправления. Попробуйте сначала простой, он менее влияет на производительность сайта. Проверка завершена. Эти настройки влияют на все сайты сети без отдельных настроек На этой странице можно проверить как WordPress может определить HTTPS. WebAware Ваш сервер нормально определяет HTTPS. Рекомендуется определение HTTPS методом %s. Сервер не определяет HTTPS. Рекомендуется определение HTTPS методом %s.  Окружение сервера показывает: Режим захвата Захватить всё Содержимое Всё на странице, от заголовка до подвала: Все что делает режим Содержмое, плюс: Все что делает Простой, плюс: Небезопасное содержимое не исправляется Выключено Простой Самая высокая возможность что-то поломать, но иногда необходимо Самый быстрый метод Виджеты захватить полную страницу, исправить скрипты, стили и другие ресурсы данные возвращаемые <code>wp_upload_dir()</code> (например некоторые CAPTCHA) исключает вызовы AJAX, которые могут вызвать проблемы с совместимостью и производительностью Изображения и другие медиафайлы загружаемые вызовом <code>wp_get_attachment_image()</code>, <code>wp_get_attachment_image_src()</code>, итд. Изображения загружаемые плагином Image Widget включает вызовы AJAX, которые могут вызвать проблемы с совместимостью и производительностью ресурсы виджетов "Текст" ресурсы в любом виджете ресурсы в содержимом страницы скрипты зарегистрированные <code>wp_register_script()</code> или <code>wp_enqueue_script()</code> стили зарегистрированные <code>wp_register_style()</code> или <code>wp_enqueue_style()</code> https://shop.webaware.com.au/ https://ssl.webaware.net.au/ Небезопасный контент SSL Проверка SSL Пожертвовать Получить помощь Инструкции Рейтинг Настройки Перевести Исправления для отдельных плагинов и тем Выберите только те исправления, которые нужны сайту WooCommerce  + Google Chrome HTTP_HTTPS ошибки (исправлены в WooCommerce v2.3.13) * обнаружено как рекомендуемая настройка Определение HTTPS HTTP_CF_VISITOR (Cloudflare Flexible SSL); устарело и не рекомандуется, так как CloudFlare сейчас отсылает заголовок HTTP_X_FORWARDED_PROTO HTTP_CLOUDFRONT_FORWARDED_PROTO (Amazon CloudFront HTTPS кешированное содержимоеt) HTTP_X_ARR_SSL (Windows Azure ARR) HTTP_X_FORWARDED_PROTO (например балансировщики, реверс-прокси) HTTP_X_FORWARDED_SSL (напр. реверс-прокси) Выберите как WordPress будет определять, что страница загружена по HTTPS стандартная функция WordPress невозможно определить HTTPS Уровень исправления неверный Определение HTTPS настроек неверно 