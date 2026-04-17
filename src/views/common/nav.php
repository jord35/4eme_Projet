
	<nav class="site-nav" aria-label="Navigation principale">
		<a class="site-nav__brand" href="/?action=home">Mon site</a>

		<div class="site-nav__links">
			<a class="site-nav__link" href="/?action=messages">
				Messages
				<?php if ($globalUnreadMessageCount > 0): ?>
					<span
						id="navbar-message-badge"
						class="site-nav__badge"
						aria-label="<?= $globalUnreadMessageCount ?> message<?= $globalUnreadMessageCount > 1 ? 's' : '' ?> non lu<?= $globalUnreadMessageCount > 1 ? 's' : '' ?>"
					>
						<?= $globalUnreadMessageCount ?>
					</span>
				<?php else: ?>
					<span
						id="navbar-message-badge"
						class="site-nav__badge is-hidden"
						aria-hidden="true"
					>
						0
					</span>
				<?php endif; ?>
			</a>
		</div>
	</nav>

