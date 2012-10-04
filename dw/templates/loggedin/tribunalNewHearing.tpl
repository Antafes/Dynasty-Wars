<div class="newHearing" id="new_hearing">
	<div class="subheading">{$lang.new_hearing}</div>
	{is_array var=$errors assign='errorsCheck'}
	{if $smarty.get.successful}
		<div class="info">{$lang.created}</div>
	{elseif $errorsCheck}
		<div class="info">
			{foreach from=$errors item='error' key='errorKey'}
				{$lang.$errorKey}<br />
			{/foreach}
		</div>
	{/if}
	<form method="post" action="index.php?chose=tribunal&amp;sub=newhearing" name="form_new_hearing">
		<div class="row">
			<div class="nh_column left">{$lang.accused_text}:</div>
			<div class="nh_column right">
				<input type="text" name="accused" value="{$smarty.post.accused}" />
			</div>
		</div>
		<div class="row">
			<div class="nh_column left">{$lang.cause_text}:</div>
			<div class="nh_column right">
				<select name="causes">
					<option value="0">&nbsp;</option>
					{foreach from=$causes item='cause'}
						<option value="{$cause.tcid}"{if $cause.tcid == $smarty.post.causes} selected="selected"{/if}>{$cause.cause}</option>
					{/foreach}
				</select>
				<textarea name="cause_description" cols="30" rows="6" style="margin-top: 2px;">{$smarty.post.cause_description}</textarea>
			</div>
		</div>
		<div class="row">
			<div class="nh_column left">{$lang.arguments_text}:</div>
			<div class="nh_column right">
				<select multiple="multiple" size="6" name="arguments[]" class="multi_select">
					{foreach from=$messages item='message'}
						{is_array var=$smarty.post.arguments assign='argumentsCheck'}
						{in_array var=$smarty.post.arguments value=$message.msgid assign='argumentsInArray'}
						<option value="{$message.msgid}"{if $argumentsCheck && $argumentsInArray} selected="selected"{/if}>{$message.title}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="nh_column both">
				<input type="submit" name="nh_sub" value="{$lang.new_hearing}" />
			</div>
		</div>
	</form>
</div>