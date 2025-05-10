document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const results = document.getElementById('results');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const query = document.getElementById('query').value.trim();

        if (query.length < 3) {
            results.innerHTML = '<p>Введите минимум 3 символа</p>';
            return;
        }

        try {
            const res = await fetch(`/test/src/find.php?query=${encodeURIComponent(query)}`);
            const data = await res.json();

            if (!data.length) {
                results.innerHTML = '<p>Ничего не найдено</p>';
                return;
            }

            results.innerHTML = data.map(item => `
        <div class="search-result">
          <h3>${item.title}</h3>
          <p>${item.body.replace(query, `<b>${query}</b>`)}</p>
        </div>
      `).join('');
        } catch (error) {
            results.innerHTML = '<p>Произошла ошибка при запросе</p>';
            console.error('Ошибка запроса:', error);
        }
    });
});
