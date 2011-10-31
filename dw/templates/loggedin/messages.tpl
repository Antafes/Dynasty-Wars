{include file='header.tpl'}
<script type="text/javascript" language="javascript" src="lib/js/markall.js"></script>
<div class="heading">{$lang.messages|htmlentities}</div>
{if !$smarty.get.mmode}
	<div class="messageMenu">
		<a href="index.php?chose=messages&amp;mmode=new">{$lang.writeMessage|htmlentities}</a><br />
		<a href="index.php?chose=messages&amp;mmode=newall">{$lang.writeToAll|htmlentities}</a><br />
		<a href="index.php?chose=messages&amp;mmode=received">{$lang.received|htmlentities} ({$messageCount.unreadMessages}/{$messageCount.readMessages})</a><br />
		<a href="index.php?chose=messages&amp;mmode=event">{$lang.events|htmlentities} ({$eventMessageCount.unreadMessages}/{$eventMessageCount.readMessages})</a><br />
		<a href="index.php?chose=messages&amp;mmode=sent">{$lang.sent|htmlentities}</a><br />
		<a href="index.php?chose=messages&amp;mmode=archive">{$lang.archive|htmlentities}</a>
	</div>
	<div class="info">{$lang.messageInfo|htmlentities}</div>
{elseif $smarty.get.mmode == 'received'}
	{include file='messagesReceived.tpl'}
{elseif $smarty.get.mmode == 'new' || $smarty.get.mmode == 'aw'}
	<div class="subheading">{$lang.writeMessage|htmlentities}</div>
	{if $infoMessage}
		<div class="infoMessage">{$infoMessage|htmlentities}</div>
	{/if}
	{include file='write_message.tpl'}
	<div class="backlink">
		<a href="index.php?chose=messages">{$lang.back|htmlentities}</a>
	</div>
{elseif $smarty.get.mmode == 'newall'}
	{if $smarty.session.user->getGameRank()}
		<div class="subheading">{$lang.writeToAll|htmlentities}</div>
		{include file='write_message.tpl'}
		<div class="backlink">
			<a href="index.php?chose=messages">{$lang.back|htmlentities}</a>
		</div>
	{else}
		<div class="infoMessage">{$lang.noAccess|htmlentities}</div>
	{/if}
{/if}
{include file='footer.tpl'}