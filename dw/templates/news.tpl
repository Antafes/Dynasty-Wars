			<div class="news">
				<table class="no_content" cellspacing="0" cellpadding="0">
					<tr>
						<td class="news_top_left"></td>
						<td class="news_top">
							<span class="news1">{$title}</span> - {$entry.title}<br />
							<span class="span3">{$news_from}
								<a href="mailto:{$entry.email}">{$entry.nick}</a>
								({$entry.time})
							</span>
						</td>
						<td class="news_top_right"></td>
					</tr>
					<tr>
						<td class="news_left"></td>
						<td class="news_middle">
							{$entry.text}
							{if $entry.changed}
							<br /><br />
							<span class="news_edited">
								{$entry.changed}
							</span>
							{/if}
						</td>
						<td class="news_right"></td>
					</tr>
					<tr>
						<td class="news_bottom_left"></td>
						<td class="news_bottom"></td>
						<td class="news_bottom_right"></td>
					</tr>
				</table>
			</div>