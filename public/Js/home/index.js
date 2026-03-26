document.addEventListener('DOMContentLoaded', () => {
    const bookList = document.getElementById('book-list');

    if (!bookList) {
        return;
    }

    function getLastBookId() {
        const items = bookList.querySelectorAll('[data-book-id]');
        const lastItem = items[items.length - 1];

        if (!lastItem) {
            return 0;
        }

        return parseInt(lastItem.dataset.bookId, 10) || 0;
    }

    function createBookCard(book) {
        const article = document.createElement('article');
        article.className = 'book-card';
        article.dataset.bookId = book.id;

        const imagePath = book.webp_320_path || book.original_path || '';

        article.innerHTML = `
      <div class="book-card__image">
        <img src="${imagePath}" alt="${escapeHtml(book.title)}">
      </div>
      <div class="book-card__content">
        <h2>${escapeHtml(book.title)}</h2>
        <p>${escapeHtml(book.author_name)}</p>
        <p>Proposé par ${escapeHtml(book.username)}</p>
      </div>
    `;

        return article;
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function handleNewLatestBooks(data) {
        if (!data || !data.success || !Array.isArray(data.books)) {
            return;
        }

        if (data.books.length === 0) {
            return;
        }

        const emptyMessage = document.getElementById('no-books-message');
        if (emptyMessage) {
            emptyMessage.remove();
        }

        data.books.forEach((book) => {
            const exists = bookList.querySelector(`[data-book-id="${book.id}"]`);
            if (!exists) {
                bookList.appendChild(createBookCard(book));
            }
        });
    }

    async function fetchLatestBooks() {
        const lastId = getLastBookId();

        const response = await fetch(`/?page=home&ajax=latest-books&afterId=${lastId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la récupération des livres.');
        }

        const data = await response.json();
        handleNewLatestBooks(data);
    }

    createPolling({
        callback: fetchLatestBooks,
        delay: 10000
    });
});
