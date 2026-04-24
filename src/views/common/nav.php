<?php
$globalUnreadMessageCount = (int) ($globalUnreadMessageCount ?? 0);
$isAuthenticated = !empty($_SESSION['user_id']);
$currentUsername = trim((string) ($_SESSION['username'] ?? ''));
$messageCountSuffix = $globalUnreadMessageCount > 1 ? 's' : '';
?>

<header class="site-header">
	<div class="site-frame site-frame--header">
		<a class="site-header__brand" href="/?action=home">
			<img class="site-header__logo" src="/assets/logo.svg" alt="Tom Troc">
		</a>

		<nav class="site-header__nav" aria-label="Principale">
			<ul class="site-header__list">
				<li class="site-header__item">
					<a class="site-header__link" href="/?action=home">Accueil</a>
				</li>

				<li class="site-header__item">
					<a class="site-header__link" href="/?action=books">Nos livres à l'échange</a>
				</li>

				<?php if ($isAuthenticated): ?>
					<li class="site-header__item">
						<a
							class="site-header__link site-header__link--icon"
							href="/?action=messages"
							<?= $globalUnreadMessageCount > 0 ? 'aria-describedby="navbar-message-badge"' : '' ?>>
							<img
								class="site-header__icon"
								src="/assets/Icon-messagerie.svg"
								alt=""
								aria-hidden="true">
							<span class="site-header__label">Messagerie</span>

							<?php if ($globalUnreadMessageCount > 0): ?>
								<span
									class="site-header__badge"
									id="navbar-message-badge"
									aria-label="<?= $globalUnreadMessageCount ?> message<?= $messageCountSuffix ?> non lu<?= $messageCountSuffix ?>">
									<?= $globalUnreadMessageCount ?>
								</span>
							<?php endif; ?>
						</a>
					</li>

					<li class="site-header__item">
						<a class="site-header__link site-header__link--icon" href="/?action=my-account">
							<img
								class="site-header__icon"
								src="/assets/Icon-mon-compte.svg"
								alt=""
								aria-hidden="true">
							<span class="site-header__label">Mon compte</span>
						</a>
					</li>

					<?php if ($currentUsername !== ''): ?>
						<li class="site-header__item">
							<span class="site-header__username" id="site-header-username" aria-label="Utilisateur connecté">
								<?= htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8') ?>
							</span>
						</li>
					<?php endif; ?>
				<?php else: ?>
					<li class="site-header__item">
						<a class="site-header__link" href="/?action=login">Connexion</a>
					</li>

					<li class="site-header__item">
						<a class="site-header__link" href="/?action=signup">Inscription</a>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</header>