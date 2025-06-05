/*  --------  ДАННЫЕ КАТАЛОГА  -------- */
const products = [
    {
      id:'crm-pro',
      name:'CRM‑PRO',
      category:'CRM/ERP',
      short:'Корпоративная CRM‑система для среднего и крупного бизнеса.',
      thumb:'images/crm-thumb.jpg',
      full:'images/crm-full.jpg',
      url:'product/crm-pro.html'
    },
    {
      id:'mobile-suite',
      name:'Mobile‑Suite',
      category:'Мобильные приложения',
      short:'Нативные iOS/Android приложения «под ключ».',
      thumb:'images/mobile-thumb.jpg',
      full:'images/mobile-full.jpg',
      url:'product/mobile-suite.html'
    },
    {
      id:'cloud-stack',
      name:'Cloud‑Stack',
      category:'Облачные сервисы',
      short:'Оркестрация Docker/K8s для хостинга SaaS‑платформ.',
      thumb:'images/cloud-full-thumb.jpg',
      full:'images/cloud-full.jpg',
      url:'product/cloud-stack.html'
    },
    {
      id:'portal-x',
      name:'Portal‑X',
      category:'Корпоративные порталы',
      short:'Гибкий интранет‑портал с SSO и каталогом сервисов.',
      thumb:'images/portal-thumb.jpg',
      full:'images/portal-full.jpg',
      url:'product/portal-x.html'
    }
  ];
  
  const CATEGORIES = ['Все','Корпоративные порталы','CRM/ERP','Мобильные приложения','Облачные сервисы'];
  
  /* --------  РЕНДЕР  -------- */
  function renderControls(){
    const ctrls = document.querySelector('.catalog-controls');
    if(!ctrls) return;
  
    // поиск
    const search = document.createElement('input');
    search.type='search'; search.placeholder='поиск по названию…';
    search.id='search';
  
    // категории
    const select = document.createElement('select');
    select.id='category';
    CATEGORIES.forEach(c=>{
      const opt = document.createElement('option');
      opt.value=c; opt.textContent=c; select.append(opt);
    });
  
    ctrls.append(search,select);
  }
  
  function renderCards(list){
    const wrap = document.getElementById('catalog-list');
    wrap.innerHTML='';
    if(list.length===0){
      wrap.innerHTML='<p>Ничего не найдено…</p>'; return;
    }
  
    list.forEach(p=>{
      const card = document.createElement('div');
      card.className='card';
      card.innerHTML=`
        <img src="${p.thumb}" alt="${p.name}">
        <h4>${p.name}</h4>
        <p>${p.short}</p>
        <a href="${p.url}">Подробнее →</a>
      `;
      wrap.append(card);
    });
  }
  
  async function applyFilter(){
    const q = document.getElementById('search').value.trim();
    const cat = document.getElementById('category').value;

    if(q===''){
      const filtered = products.filter(p=>{
        return cat==='Все' || p.category===cat;
      });
      renderCards(filtered);
      return;
    }

    try{
      const resp = await fetch(`search.php?format=json&q=${encodeURIComponent(q)}`);
      if(!resp.ok) throw new Error('server');
      const data = await resp.json();
      let list = data.map(r=>{
        const local = products.find(p=>p.name===r.name) || {};
        return {
          name:r.name,
          short:r.desc || local.short || '',
          thumb:local.thumb || 'images/crm-thumb.jpg',
          url:r.url,
          category:local.category || ''
        };
      });
      if(cat!=='Все') list = list.filter(p=>p.category===cat);
      renderCards(list);
    }catch(e){
      renderCards([]);
    }
  }
  
  /* --------  ИНИЦИАЛИЗАЦИЯ -------- */
  document.addEventListener('DOMContentLoaded',()=>{
    if(!document.getElementById('catalog-list')) return;
    renderControls();
    renderCards(products);
  
    document.getElementById('search').addEventListener('input',applyFilter);
    document.getElementById('category').addEventListener('change',applyFilter);
  });
  