// ======= мини‑логин без сервера =======
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const storedUser = localStorage.getItem('user');

    function showUser(user) {
        if (!loginForm) return;
        loginForm.style.display = 'none';
        let info = document.getElementById('user-info');
        if (!info) {
            info = document.createElement('div');
            info.id = 'user-info';
            loginForm.after(info);
        }
        info.textContent = `Вы вошли как ${user}`;
    }

    if (loginForm) {
        if (storedUser) {
            showUser(storedUser);
        }
        loginForm.addEventListener('submit', e => {
            e.preventDefault();
            const user = e.target.login.value.trim() || 'гость';
            localStorage.setItem('user', user);
            showUser(user);
            e.target.reset();
        });
    }
  
    // ======= подсветка активного пункта верхнего меню =======
    const current = location.pathname.split('/').pop();
    document.querySelectorAll('nav.top a').forEach(a=>{
      if (a.getAttribute('href') === current) a.style.textDecoration='underline';
    });
  });
 
