const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

document.querySelector('[data-menu-toggle]')?.addEventListener('click', (event) => {
    const nav = document.querySelector('#primary-nav');
    nav?.classList.toggle('is-open');
    event.currentTarget.setAttribute('aria-expanded', String(nav?.classList.contains('is-open')));
});

document.querySelector('[data-dropdown-trigger]')?.addEventListener('click', (event) => {
    const menu = document.querySelector('[data-dropdown]');
    const visible = menu?.hasAttribute('hidden');
    menu?.toggleAttribute('hidden', !visible);
    event.currentTarget.setAttribute('aria-expanded', String(visible));
});

document.querySelectorAll('.ll-flash [data-flash-close]').forEach((button) => button.addEventListener('click', () => button.closest('.ll-flash')?.remove()));

const input = document.querySelector('[data-live-search] input');
const results = document.querySelector('[data-search-results]');
let searchTimer;
input?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    const term = input.value.trim();
    if (!term) { results.hidden = true; results.replaceChildren(); return; }
    searchTimer = setTimeout(async () => {
        try {
            const response = await fetch(`${input.dataset.searchUrl}?q=${encodeURIComponent(term)}`, { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            results.replaceChildren(...payload.results.map((item) => {
                const link = document.createElement('a'); link.className = 'll-search-result'; link.href = item.url;
                link.innerHTML = `<strong></strong><small></small>`; link.querySelector('strong').textContent = item.title; link.querySelector('small').textContent = `${item.category} · ${item.author ?? 'Unknown'}`;
                return link;
            }));
            results.hidden = payload.results.length === 0;
        } catch { results.hidden = true; }
    }, 220);
});

document.querySelectorAll('[data-vote-form]').forEach((form) => form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submitter = event.submitter;
    try {
        const formData = new FormData(form); formData.set('direction', submitter.value);
        const response = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf }, body: formData });
        if (!response.ok) throw new Error();
        const data = await response.json();
        form.closest('[data-vote-group]').querySelector('[data-vote-score]').textContent = data.score;
        form.querySelectorAll('button').forEach((button) => button.classList.toggle('is-selected', data.my_vote === (button.value === 'up' ? 1 : -1)));
    } catch { form.submit(); }
}));
