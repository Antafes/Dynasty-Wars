{include file="header.tpl"}
			<div class="heading">
				{$welcome_message} {$nick}
			</div>
			{if $messages}
			<div class="text_center_under">
				{$messages_info}
			</div>
			{/if}
			{if $missionary && !$own_uid}
			<div class="text_center_under">
				{$missionary_info}
				<a href="{$missinary_accept_link}">{$missionary_accept}</a>
				<a href="{$missinary_decline_link}">{$missionary_decline}</a>
			</div>
			{/if}
{include file="footer.tpl"}