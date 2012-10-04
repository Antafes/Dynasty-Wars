{include file='header.tpl'}
<script type="text/javascript" language="javascript" src="lib/js/markall.js"></script>
<div class="heading">{$lang.messages}</div>
<div class="messageContent">
	{if !$smarty.get.mmode}
		<div class="messageMenu">
			<a href="index.php?chose=messages&amp;mmode=new">{$lang.writeMessage}</a><br />
			<a href="index.php?chose=messages&amp;mmode=newall">{$lang.writeToAll}</a><br />
			<a href="index.php?chose=messages&amp;mmode=received">{$lang.received} ({$messageCount.unreadMessages}/{$messageCount.totalMessages})</a><br />
			<a href="index.php?chose=messages&amp;mmode=event">{$lang.events} ({$eventMessageCount.unreadMessages}/{$eventMessageCount.totalMessages})</a><br />
			<a href="index.php?chose=messages&amp;mmode=sent">{$lang.sent}</a><br />
			<a href="index.php?chose=messages&amp;mmode=archive">{$lang.archive}</a>
		</div>
		<div class="info">{$lang.messageInfo}</div>
	{elseif $smarty.get.mmode == 'new' || $smarty.get.mmode == 'aw'}
		<div class="subheading">{$lang.writeMessage}</div>
		{if $infoMessage}
			<div class="infoMessage">{$infoMessage}</div>
		{/if}
		{include file='write_message.tpl'}
		<div class="backlink">
			<a href="index.php?chose=messages{if $smarty.get.msgid}&amp;mmode=received&amp;msgid={$smarty.get.msgid}{/if}">{$lang.back}</a>
		</div>
	{elseif $smarty.get.mmode == 'newall'}
		{if $smarty.session.user->getGameRank()}
			<div class="subheading">{$lang.writeToAll}</div>
			{include file='write_message.tpl'}
			<div class="backlink">
				<a href="index.php?chose=messages">{$lang.back}</a>
			</div>
		{else}
			<div class="infoMessage">{$lang.noAccess}</div>
		{/if}
	{elseif $smarty.get.mmode == 'received'}
		{if !$smarty.get.msgid}
			{include file='messagesReceived.tpl'}
		{else}
			{include file='messageRead.tpl'}
		{/if}
	{elseif $smarty.get.mmode == 'event'}
		{if !$smarty.get.msgid}
			{include file='messagesEventsReceived.tpl'}
		{else}
			{include file='messageEventRead.tpl'}
		{/if}
	{elseif $smarty.get.mmode == 'sent'}
		{if !$smarty.get.msgid}
			{include file='messagesSent.tpl'}
		{else}
			{include file='messageSentRead.tpl'}
		{/if}
	{elseif $smarty.get.mmode == 'archive'}
		{if !$smarty.get.msgid}
			{include file='messagesArchived.tpl'}
		{else}
			{include file='messageArchivedRead.tpl'}
		{/if}
	{/if}
</div>
{include file='footer.tpl'}