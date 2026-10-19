/**
 * Watermelon Lab — Public Menu Interactions
 */

(() => {
    const cfg = window.WL || {};
    const grid = document.getElementById('menuGrid');
    const empty = document.getElementById('emptyState');
    const searchInput = document.getElementById('menuSearch');
    const rail = document.getElementById('categoryRail');
    const backTop = document.getElementById('backTop');

    if (!grid || !searchInput || !rail) return;

    let activeCategory = 0;
    let debounceTimer = null;
    let requestId = 0;

    const escapeHtml = (value) => {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const cardHtml = (item) => `
        <article class="menu-card" id="item-${item.id}" data-id="${item.id}" data-category="${item.category_id}" data-name="${escapeHtml(item.name)}">
            <div class="menu-card__deco" aria-hidden="true">
                <span class="seed seed--1"></span>
                <span class="seed seed--2"></span>
                <span class="seed seed--3"></span>
            </div>
            <div class="menu-card__top">
                <span class="menu-card__cat">${escapeHtml(item.category)}</span>
                <span class="menu-card__price">${escapeHtml(item.price_formatted)}</span>
            </div>
            <h3 class="menu-card__name">${escapeHtml(item.name)}</h3>
            <p class="menu-card__desc">${escapeHtml(item.description)}</p>
        </article>
    `;

    const sectionHtml = (categoryId, categoryName, items) => `
        <section class="menu-cat-block" data-category="${categoryId}" id="category-${categoryId}">
            <div class="menu-cat-heading">
                <span class="menu-cat-heading__seed" aria-hidden="true"></span>
                <h3 class="menu-cat-heading__title">${escapeHtml(categoryName)}</h3>
                <span class="menu-cat-heading__line" aria-hidden="true"></span>
            </div>
            <div class="menu-grid">
                ${items.map(cardHtml).join('')}
            </div>
        </section>
    `;

    const groupByCategory = (items) => {
        const groups = [];
        const indexById = {};

        items.forEach((item) => {
            const id = Number(item.category_id);
            if (indexById[id] === undefined) {
                indexById[id] = groups.length;
                groups.push({
                    id,
                    name: item.category || '',
                    items: [],
                });
            }
            groups[indexById[id]].items.push(item);
        });

        return groups;
    };

    const setEmpty = (show) => {
        if (!empty) return;
        empty.hidden = !show;
        grid.hidden = show;
    };

    const renderItems = (items) => {
        if (!items.length) {
            grid.innerHTML = '';
            setEmpty(true);
            return;
        }

        setEmpty(false);
        grid.classList.add('is-updating');

        window.requestAnimationFrame(() => {
            const groups = groupByCategory(items);
            grid.innerHTML = groups
                .map((group) => sectionHtml(group.id, group.name, group.items))
                .join('');
            grid.classList.remove('is-updating');
        });
    };

    const fetchItems = async () => {
        const current = ++requestId;
        const params = new URLSearchParams();
        const q = searchInput.value.trim();

        if (q) params.set('q', q);
        if (activeCategory > 0) params.set('category_id', String(activeCategory));

        try {
            const res = await fetch(`${cfg.apiItems}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            if (current !== requestId) return data.items || [];
            renderItems(data.items || []);
            return data.items || [];
        } catch (err) {
            if (current !== requestId) return [];
            console.error(err);
            return [];
        }
    };

    const scheduleFetch = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchItems, 220);
    };

    rail.addEventListener('click', (event) => {
        const btn = event.target.closest('.cat-pill');
        if (!btn) return;

        rail.querySelectorAll('.cat-pill').forEach((el) => {
            el.classList.toggle('is-active', el === btn);
            el.setAttribute('aria-selected', el === btn ? 'true' : 'false');
        });

        activeCategory = Number(btn.dataset.category || 0);
        btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        scheduleFetch();
    });

    searchInput.addEventListener('input', scheduleFetch);

    // Soft reveal for cards already in DOM
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08 }
    );

    grid.querySelectorAll('.menu-card').forEach((card) => revealObserver.observe(card));

    if (backTop) {
        const onScroll = () => {
            backTop.classList.toggle('is-visible', window.scrollY > 420);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        backTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        onScroll();
    }

    // ---------- Personal assistant ----------
    const assistant = document.getElementById('assistant');
    const assistantToggle = document.getElementById('assistantToggle');
    const assistantPanel = document.getElementById('assistantPanel');
    const assistantMinimize = document.getElementById('assistantMinimize');
    const assistantShuffle = document.getElementById('assistantShuffle');
    const assistantShow = document.getElementById('assistantShow');
    const assistantCat = document.getElementById('assistantCat');
    const assistantName = document.getElementById('assistantName');
    const assistantDesc = document.getElementById('assistantDesc');
    const assistantPrice = document.getElementById('assistantPrice');
    const assistantPick = document.getElementById('assistantPick');

    if (assistant && assistantToggle && Array.isArray(cfg.items) && cfg.items.length) {
        let currentPick = null;
        let recentIds = [];

        const pickRandom = () => {
            const pool = cfg.items.filter((item) => !recentIds.includes(item.id));
            const source = pool.length ? pool : cfg.items;
            const item = source[Math.floor(Math.random() * source.length)];
            recentIds = [...recentIds.filter((id) => id !== item.id), item.id].slice(-6);
            return item;
        };

        const renderPick = (item) => {
            currentPick = item;
            assistantCat.textContent = item.category || '';
            assistantName.textContent = item.name || '';
            assistantDesc.textContent = item.description || '';
            assistantPrice.textContent = item.price_formatted || '';
            assistantPick.classList.remove('is-pop');
            void assistantPick.offsetWidth;
            assistantPick.classList.add('is-pop');
        };

        const setOpen = (open) => {
            assistant.classList.toggle('is-open', open);
            assistantPanel.hidden = !open;
            assistantToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            assistantToggle.setAttribute(
                'aria-label',
                open
                    ? assistantToggle.dataset.labelClose || 'Close'
                    : assistantToggle.dataset.labelOpen || 'Open'
            );
            sessionStorage.setItem('wl_assistant_open', open ? '1' : '0');
            if (open && !currentPick) {
                renderPick(pickRandom());
            }
        };

        assistantToggle.addEventListener('click', () => {
            setOpen(!assistant.classList.contains('is-open'));
        });

        if (assistantMinimize) {
            assistantMinimize.addEventListener('click', (event) => {
                event.stopPropagation();
                setOpen(false);
            });
        }

        assistantShuffle.addEventListener('click', () => {
            renderPick(pickRandom());
        });

        assistantShow.addEventListener('click', () => {
            if (!currentPick) return;

            const revealItem = () => {
                const card = document.getElementById(`item-${currentPick.id}`);
                if (!card) return false;
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card.classList.add('is-spotlight');
                window.setTimeout(() => card.classList.remove('is-spotlight'), 2200);
                return true;
            };

            // Reset filters so the item is visible in the grid
            if (activeCategory !== 0 || searchInput.value.trim() !== '') {
                activeCategory = 0;
                searchInput.value = '';
                rail.querySelectorAll('.cat-pill').forEach((el) => {
                    const isAll = Number(el.dataset.category || 0) === 0;
                    el.classList.toggle('is-active', isAll);
                    el.setAttribute('aria-selected', isAll ? 'true' : 'false');
                });
                fetchItems().then(() => {
                    window.setTimeout(revealItem, 80);
                });
            } else if (!revealItem()) {
                fetchItems().then(() => window.setTimeout(revealItem, 80));
            }
        });

        // Pop up automatically unless the user minimized it this session
        const wasMinimized = sessionStorage.getItem('wl_assistant_open') === '0';
        if (!wasMinimized) {
            window.setTimeout(() => setOpen(true), 600);
        }
    }
})();
