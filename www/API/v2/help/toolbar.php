 <div id="header" class="desktop">
    <div id="header_content">
        <img src="/img/logo/logo-api.png"/>
        <div id="header_menu">
            <table>
                <tr>
                    <td><a href="http://<?php echo($http_host . "API/help/V$v/"); ?>">API documentation</a></td>
                    <td><a href="http://<?php echo($http_host . "API/help/V$v/sync/"); ?>">Sync</a></td>
                    <td><a href="http://<?php echo($http_host . "API/help/V$v/comment/"); ?>">Comment</a></td>
                    <td><a href="http://<?php echo($http_host); ?>/">Main page</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="header_m" class="mobile">
    <img src="/img/logo/logo-api.png" onClick='toggleMobileMenu();' id='mobile_logo'/>
    <div id="header_menu_m">
        <div id='header_m_title' onClick='openMobileMenu();' class='pointer'><span><img src='http://<?php echo $http_host; ?>/img/misc/slid-menu.png'/>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cur_section; ?></span></div>
        <div class='header_m_link'><a href="http://<?php echo($http_host . "API/help/V$v/"); ?>">API documentation</a></div>
        <div class='header_m_link'><a href="http://<?php echo($http_host . "API/help/V$v/sync/"); ?>">Sync</a></div>
        <div class='header_m_link'><a href="http://<?php echo($http_host . "API/help/V$v/comment/"); ?>">Comment</a></div>
        <div class='header_m_link'><a href="http://<?php echo($http_host); ?>/">Main page</a></div>
        <div id='header_m_slider' onClick='closeMobileMenu();' class='pointer'><span><img src='http://<?php echo $http_host; ?>/img/misc/slid-top.png'/></span></div>
    </div><br/><br/>
</div>

