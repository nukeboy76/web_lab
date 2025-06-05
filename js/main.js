// ======= мини‑логин без сервера =======
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
      loginForm.addEventListener('submit', e => {
        e.preventDefault();
        const user = e.target.login.value.trim();
        alert(`Привет, ${user || 'гость'}! (демо‑авторизация)`);     // имитация входа
        e.target.reset();
      });
    }
  
    // ======= подсветка активного пункта верхнего меню =======
    const current = location.pathname.split('/').pop();
    document.querySelectorAll('nav.top a').forEach(a=>{
      if (a.getAttribute('href') === current) a.style.textDecoration='underline';
    });
  });
  