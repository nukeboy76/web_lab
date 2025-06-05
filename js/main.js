// ======= авторизация через сервер =======
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');

    async function fetchState() {
        try {
            const res = await fetch('auth_state.php');
            if (!res.ok) return null;
            return await res.json();
        } catch (e) {
            return null;
        }
    }

    async function showUser(name) {
        if (!loginForm) return;
        loginForm.style.display = 'none';
        let info = document.getElementById('user-info');
        if (!info) {
            info = document.createElement('div');
            info.id = 'user-info';
            loginForm.after(info);
        } else {
            info.innerHTML = '';
        }
        const icon = document.createElement('span');
        icon.className = 'user-icon';
        icon.textContent = '\u{1F464}';
        const logout = document.createElement('button');
        logout.id = 'logout-btn';
        logout.textContent = 'выход';

        logout.addEventListener('click', async () => {
            await fetch('logout.php');

            info.remove();
            loginForm.style.display = '';
        });
        info.appendChild(icon);
        info.appendChild(logout);
    }

    if (loginForm) {
        fetchState().then(state => {
            if (state && state.loggedIn) showUser(state.name);
        });

        loginForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(loginForm);
            const res = await fetch('login.php', { method: 'POST', body: fd });
            if (res.ok) {
                const data = await res.json();
                showUser(data.name);
                loginForm.reset();
            } else {
                alert('Неверный логин или пароль');
            }
        });
    }

    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(contactForm);
            const res = await fetch(contactForm.action, { method: 'POST', body: fd });
            if (res.ok) {
                contactForm.innerHTML = '<p>Спасибо, ваше сообщение отправлено.</p>';
            } else {
                const data = await res.json().catch(() => null);
                alert(data && data.error ? data.error : 'Ошибка отправки');
            }
        });
    }

    const guestForm = document.getElementById('guestbook-form');
    const guestList = document.getElementById('guestbook-list');

    function escapeHtml(str) {
        return str.replace(/[&<>]/g, t => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[t]));
    }

    async function loadReviews() {
        if (!guestList) return;
        const res = await fetch('guestbook.php?format=json');
        if (!res.ok) return;
        const data = await res.json();
        guestList.innerHTML = '';
        for (const e of data) {
            const p = document.createElement('p');
            const stars = '★'.repeat(e.rating) + '☆'.repeat(5 - e.rating);
            p.innerHTML = `<b>${escapeHtml(e.name)}</b> (${escapeHtml(e.city)}) — ${escapeHtml(e.product)} — ${stars}:<br>${escapeHtml(e.comment)}`;
            guestList.appendChild(p);
            guestList.appendChild(document.createElement('hr'));
        }
    }

    if (guestForm) {
        loadReviews();
        guestForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(guestForm);
            const res = await fetch(guestForm.action, { method: 'POST', body: fd });
            if (res.ok) {
                guestForm.reset();
                loadReviews();
            } else {
                const data = await res.json().catch(() => null);
                alert(data && data.error ? data.error : 'Ошибка отправки');
            }
        });
    }
  
    // ======= подсветка активного пункта верхнего меню =======
    const current = location.pathname.split('/').pop();
    document.querySelectorAll('nav.top a').forEach(a=>{
      if (a.getAttribute('href') === current) a.style.textDecoration='underline';
    });
  });
 
