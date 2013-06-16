{include file="header.tpl"}
<script type="text/javascript" src="lib/js/market.js"></script>
<div class="market">
	<div class="heading">
		{$lang.market}
	</div>
	<div class="topmenu">
		<a href="index.php?chose=market&amp;sub=offers">{$lang.offers}</a> |
		<a href="index.php?chose=market&amp;sub=log">{$lang.log}</a>
	</div>
	{if !$smarty.get.sub || $smarty.get.sub == "offers"}
	<div class="topmenu">
		<a id="create_offer" href="javascript:;">{$lang.create}</a> |
		<a id="search" href="javascript:;">{$lang.search}</a>
	</div>
	<div id="create_offer_container"{if $smarty.get.show == 'search'} style="display: none;"{/if}>
		<form method="post" action="index.php?chose=market&amp;sub=offers">
			<div class="row">
				<div class="left">
					{$lang.offer}:
				</div>
				<div class="middle">
					{html_options name=s_resource options=$resourceList}
				</div>
				<div class="right">
					<input type="text" name="s_amount" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="left">
					{$lang.request}:
				</div>
				<div class="middle">
					{html_options name=e_resource options=$resourceList}
				</div>
				<div class="right">
					<input type="text" name="e_amount" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="both">
					<input type="submit" value="{$lang.create}" />
					<input type="hidden" name="type" value="offer_create" />
				</div>
			</div>
			{if $resultMessage}
			<div class="row">
				<div class="both">
					{$resultMessage}
				</div>
			</div>
			{/if}
		</form>
	</div>
	<div id="search_container"{if !$smarty.get.show == 'search'} style="display: none;"{/if}>
		<form method="get" action="index.php">
			<input type="hidden" name="chose" value="market" />
			<input type="hidden" name="sub" value="offers" />
			<input type="hidden" name="show" value="search" />
			<div class="row">
				<div class="left">
					{$lang.offer}:
				</div>
				<div class="middle">
					{html_options name=search_s_resource options=$resourceListSearch selected=$smarty.get.search_s_resource}
				</div>
				<div class="right">
					<div class="row">
						<div class="left">
							{$lang.from}:
						</div>
						<div class="right">
							<input type="text" name="search_s_rangestart" value="{$smarty.get.search_s_rangestart}" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="row">
						<div class="left">
							{$lang.to}:
						</div>
						<div class="right">
							<input type="text" name="search_s_rangeend" value="{$smarty.get.search_s_rangeend}" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="left">
					{$lang.request}:
				</div>
				<div class="middle">
					{html_options name=search_e_resource options=$resourceListSearch selected=$smarty.get.search_e_resource}
				</div>
				<div class="right">
					<div class="row">
						<div class="left">
							{$lang.from}:
						</div>
						<div class="right">
							<input type="text" name="search_e_rangestart" value="{$smarty.get.search_e_rangestart}" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="row">
						<div class="left">
							{$lang.to}:
						</div>
						<div class="right">
							<input type="text" name="search_e_rangeend" value="{$smarty.get.search_e_rangeend}" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="both">
					<input type="submit" value="{$lang.search}" />
					<input type="reset" value="{$lang.clear}" />
					<input type="hidden" name="type" value="search" />
				</div>
			</div>
			{if $resultMessage}
			<div class="row">
				<div class="both">
					{$resultMessage}
				</div>
			</div>
			{/if}
		</form>
	</div>
	<div class="offer_list">
		<table width="650" cellpadding="0" class="no_content">
			<tr>
				<td width="50" class="no_content">&nbsp;</td>
				<td width="100" class="table_tc">
					<strong>{$lang.seller}</strong>
				</td>
				<td width="75" class="table_tc">
					<strong>{$lang.offer}</strong>
				</td>
				<td width="75" class="table_tc">
					<strong>{$lang.amount}</strong>
				</td>
				<td width="100" class="table_tc">
					<strong>{$lang.request}</strong>
				</td>
				<td width="75" class="table_tc">
					<strong>{$lang.amount}</strong>
				</td>
				<td width="75" class="table_tc">
					<strong>{$lang.tax}</strong>
				</td>
				<td width="50" class="no_content">&nbsp;</td>
				<td width="50" class="no_content">&nbsp;</td>
			</tr>
			{foreach from=$offersArray item=offer}
			<tr>
				<td width="25" class="no_content">&nbsp;</td>
				<td width="100" class="table_tc">
					{$offer.seller}
				</td>
				<td width="75" class="table_tc">
					{$offer.soldResource}
				</td>
				<td width="75" class="table_tc">
					{$offer.soldAmount}
				</td>
				<td width="100" class="table_tc">
					{$offer.requestedResource}
				</td>
				<td width="75" class="table_tc">
					{$offer.requestedAmount}
				</td>
				<td width="75" class="table_tc">
					{$offer.tax}
				</td>
				<td width="50" class="table_tc">
					{if !$own_uid}
						{if !$offer.ownOffer}
						<a href="index.php?chose=market&amp;sub=offers&amp;action=buy&amp;mid={$offer.mid}">
							<img src="pictures/check.gif" title="{$buy}" alt="{$buy}" />
						</a>
						{else}
						<a href="index.php?chose=market&amp;sub=offers&amp;action=anul&amp;mid={$offer.mid}">
							<img src="pictures/cancel.gif" title="{$anull}" alt="{$anull}" />
						</a>
						{/if}
					{else}
						&nbsp;
					{/if}
				</td>
				<td width="50" class="no_content">&nbsp;</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="9" style="text-align: center;">{$lang.none_found}</td>
			</tr>
			{/foreach}
		</table>
	</div>
	{else}
	<div class="market_log">
		<div class="topmenu">
			<form method="post" action="index.php?chose=market&amp;sub=log">
				<div class="row">
					<div class="left">
						{$lang.filter}:
						{html_options name=filter options=$filterArray selected=$smarty.post.filter}
					</div>
					<div class="middle">
						{$lang.sortorder}:
						{html_options name=order options=$sortArray selected=$smarty.post.order}
					</div>
					<div class="right">
						<input type="submit" value="{$lang.change}" />
					</div>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<div class="list">
			<table width="650" class="no_content" border="0">
				<tr>
					<td width="100" class="table_tc">
						{$lang.seller}
					</td>
					<td width="100" class="table_tc">
						{$lang.buyer}
					</td>
					<td width="60" class="table_tc">
						{$lang.offer}
					</td>
					<td width="75" class="table_tc">
						{$lang.amount}
					</td>
					<td width="90" class="table_tc">
						{$lang.request}
					</td>
					<td width="75" class="table_tc">
						{$lang.amount}
					</td>
					<td width="75" class="table_tc">
						{$lang.tax}
					</td>
					<td width="75" class="table_tc">
						{$langdate}
					</td>
				</tr>
				{foreach from=$offersArray item=offer}
				<tr class="{$offer.class}" title="{$offer.title}">
					<td width="100" class="table_tc">
						{$offer.seller}
					</td>
					<td width="100" class="table_tc">
						{$offer.buyer}
					</td>
					<td width="60" class="table_tc">
						{$offer.offer}
					</td>
					<td width="75" class="table_tc">
						{$offer.offer_amount}
					</td>
					<td width="90" class="table_tc">
						{$offer.request}
					</td>
					<td width="75" class="table_tc">
						{$offer.request_amount}
					</td>
					<td width="75" class="table_tc">
						{$offer.tax}
					</td>
					<td width="75" class="table_tc">
						{$offer.date}
					</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="9" style="text-align: center;">{$lang.none_found}</td>
				</tr>
				{/foreach}
			</table>
		</div>
	</div>
	{/if}
</div>
{include file="footer.tpl"}