<?php
$globalUnreadMessageCount = (int) ($globalUnreadMessageCount ?? 0);
$isAuthenticated = !empty($_SESSION['user_id']);
$currentUsername = trim((string) ($_SESSION['username'] ?? ''));
$messageCountSuffix = $globalUnreadMessageCount > 1 ? 's' : '';
?>

<nav id="main-navigation" aria-label="Navigation principale">
	<a id="nav-brand-link" href="/?action=home">
		<img id="nav-brand-logo" src="/assets/logo.svg" alt="Tom Troc">
	</a>

	<div id="nav-primary-links">
		<a id="nav-home-link" href="/?action=home">Accueil</a>
		<a id="nav-books-link" href="/?action=books">Nos livres a l'echange</a>

		<?php if ($isAuthenticated): ?>
			<a id="nav-messages-link" href="/?action=messages" aria-describedby="navbar-message-badge">
				<img id="nav-messages-icon" src="/assets/Icon-messagerie.svg" alt="" aria-hidden="true">
				<span id="nav-messages-text">Messagerie</span>
				<span
					id="navbar-message-badge"
					<?= $globalUnreadMessageCount === 0 ? 'hidden' : '' ?>
					aria-hidden="<?= $globalUnreadMessageCount === 0 ? 'true' : 'false' ?>"
					aria-label="<?= $globalUnreadMessageCount ?> message<?= $messageCountSuffix ?> non lu<?= $messageCountSuffix ?>"
				>
					<?= $globalUnreadMessageCount ?>
				</span>
			</a>

			<a id="nav-account-link" href="/?action=my-account">
				<img id="nav-account-icon" src="/assets/Icon-mon-compte.svg" alt="" aria-hidden="true">
				<span id="nav-account-text">Mon compte</span>
			</a>

			<?php if ($currentUsername !== ''): ?>
				<span id="nav-current-username" aria-label="Utilisateur connecte">
					<?= htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8') ?>
				</span>
			<?php endif; ?>
		<?php else: ?>
			<a id="nav-login-link" href="/?action=login">Connexion</a>
			<a id="nav-signup-link" href="/?action=signup">Inscription</a>
		<?php endif; ?>
	</div>
</nav>