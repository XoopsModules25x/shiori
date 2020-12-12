README FIRST
-----------------------

The Shiori module is a module that allows users to create personal bookmarks books and to bookmark any page in the site.
It was made for the purpose of not losing sight of the page of reading, the bulletin board which checks everyday, the article which might be useful someday.

Operating environment
=====================
PHP Version	7.2 or higher
mbstring Required
XOOPS 2.5.11+
Character Code	UTF-8(recommended)

Characteristic
===============
You can create a bookmark book for each user
Bookmark registered with one click
Pagination inside and outside the site can be bookmarked

Installation method
=====================
Extract the compressed file and copy shiori under html / modules /.
Please perform installation with "module management".
Please grant access authority to the group that allows bookmarks in "Group management".
Please set to display "bookmark" block on all pages in "Block Management".

One click bookmark
====================
One click bookmark is a function that you can bookmark that page to Shiori just by clicking the star icon. With this function, users can bookmark pages with less work and improve usability.

Example of setting in the user menu
===================================
The following is an example of setting the button of the one-click bookmark in the user menu block (legacy_block_usermenu.html). The part highlighted with orange is the part added for one click bookmark.

<table cellspacing="0">
  <tr>
    <td id="usermenu">
      <a class="menuTop" href="<{$xoops_url}>/user.php"><{$smarty.const._MB_LEGACY_VACNT}></a>
      <a href="<{$xoops_url}>/edituser.php"><{$smarty.const._MB_LEGACY_EACNT}></a>
      <a href="<{$xoops_url}>/notifications.php"><{$smarty.const._MB_LEGACY_NOTIF}></a>
      <a href="<{$xoops_url}>/modules/shiori/"><img src="<{$xoops_url}>/modules/shiori/images/unbookmarked.png" id="shiori_bookmark_star" style="float:right;display:none;" rel="<{$xoops_url}>" />Bookmark</a>
      <a href="<{$xoops_url}>/user.php?op=logout"><{$smarty.const._MB_LEGACY_LOUT}></a>      <{if $block.flagShowInbox}>
        <{if $block.new_messages > 0}>
          <a class="highlight" href="<{$block.inbox_url}>"><{$smarty.const._MB_LEGACY_INBOX}> (<span style="color:#ff0000; font-weight: bold;"><{$block.new_messages}></span>)</a>
        <{else}>
          <a href="<{$block.inbox_url}>"><{$smarty.const._MB_LEGACY_INBOX}></a>
        <{/if}>
      <{/if}>
      <{if $block.show_adminlink}>
        <a href="<{$xoops_url}>/admin.php"><{$smarty.const._MB_LEGACY_ADMENU}></a>
      <{/if}>
    </td>
  </tr>
</table>
When used with XOOPS 2.x
When using with XOOPS 2, in addition to above, please set to display "JavaScript read" block on "all pages" in block management. Please set access right according to group using Shiori.

The "Load JavaScript" block is for enabling jQuery plugin automatically. Please do not use on XOOPS Cube.

How to uninstall
================
Please change the Shiori module to inactive in "module management", then uninstall it.
Please remove shiori from html / modules /.

Change log
==============
Ver	Date	Category	Note
1.00	2009.11.01	---	Initial release
1.01	2009.11.03	---
Fixed javascript error when jQuery was not loaded (umoto private message)
Fixed that one click bookmark garbled when it was not UTF - 8 (umoto private message)
Partial correspondence to XOOPS 2 (template manager not supported)
1.02	2009.11.10	---
"Bookmark Statistics" page added to management screen
If jQuery is not loaded, change to load jQuery enclosed with module (Users no longer need to edit theme.)

Technical information
====================
Language file
n shiori, except for modinfo.php, I make a language file in XML. Regardless of the character code of XOOPS, all language files are UTF-8.

When shiori is called, the XML language file is compiled into PHP and saved as a cache. The saved location is either XOOPS_ROOT_PATH. '/ Cache' or XOOPS_TRUST_PATH. '/ Cache'.

A file called shiori_ {language code} _ {character code}. php is created. (Such as shiori_ja_utf - 8.php) This cache file will not be updated unless it is deleted.

Developer
==========
Suin (http://suin.asia)
