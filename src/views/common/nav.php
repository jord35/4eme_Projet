<?php
$globalUnreadMessageCount = (int) ($globalUnreadMessageCount ?? 0);
$isAuthenticated = !empty($_SESSION['user_id']);
$currentUsername = trim((string) ($_SESSION['username'] ?? ''));
$messageCountSuffix = $globalUnreadMessageCount > 1 ? 's' : '';
?>

<header id="site-header">
	<a id="nav-brand" href="/?action=home">
		<img id="nav-brand-logo" src="/assets/logo.svg" alt="Tom Troc">
	</a>

	<nav id="main-navigation" aria-label="Principale">
		<ul id="nav-list">
			<li class="nav-item">
				<a id="nav-home-link" href="/?action=home">Accueil</a>
			</li>

			<li class="nav-item">
				<a id="nav-books-link" href="/?action=books">Nos livres à l'échange</a>
			</li>

			<?php if ($isAuthenticated): ?>
				<li class="nav-item">
					<a
						id="nav-messages-link"
						href="/?action=messages"
						<?= $globalUnreadMessageCount > 0 ? 'aria-describedby="navbar-message-badge"' : '' ?>>
						<img
							id="nav-messages-icon"
							src="/assets/Icon-messagerie.svg"
							alt=""
							aria-hidden="true">
						<span id="nav-messages-text">Messagerie</span>

						<?php if ($globalUnreadMessageCount > 0): ?>
							<span
								id="navbar-message-badge"
								aria-label="<?= $globalUnreadMessageCount ?> message<?= $messageCountSuffix ?> non lu<?= $messageCountSuffix ?>">
								<?= $globalUnreadMessageCount ?>
							</span>
						<?php endif; ?>
					</a>
				</li>

				<li class="nav-item">
					<a id="nav-account-link" href="/?action=my-account">
						<img
							id="nav-account-icon"
							src="/assets/Icon-mon-compte.svg"
							alt=""
							aria-hidden="true">
						<span id="nav-account-text">Mon compte</span>
					</a>
				</li>

				<?php if ($currentUsername !== ''): ?>
					<li class="nav-item">
						<span id="nav-current-username" aria-label="Utilisateur connecté">
							<?= htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8') ?>
						</span>
					</li>
				<?php endif; ?>
			<?php else: ?>
				<li class="nav-item">
					<a id="nav-login-link" href="/?action=login">Connexion</a>
				</li>

				<li class="nav-item">
					<a id="nav-signup-link" href="/?action=signup">Inscription</a>
				</li>
			<?php endif; ?>
		</ul>
	</nav>
</header>