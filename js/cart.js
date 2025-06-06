function toNum(str) {
  const num = Number(str.replace(/ /g, ''));
  return num;
}
function toCurrency(num) {
  const format = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0,
  }).format(num);
  return format;
}

class Product {
  constructor(card) {
    this.imageSrc = card.querySelector('.card__image img')?.src || card.querySelector('img').src;
    this.name = card.querySelector('.card__title')?.innerText || card.querySelector('h4').innerText;
    this.price = card.querySelector('.card__price--common')?.innerText || card.querySelector('.price')?.innerText || '0';
    this.priceDiscount = card.querySelector('.card__price--discount')?.innerText || this.price;
  }
}

class Cart {
  constructor() {
    this.products = [];
  }
  get count() {
    return this.products.length;
  }
  addProduct(product) {
    this.products.push(product);
  }
  removeProduct(index) {
    this.products.splice(index, 1);
  }
  get cost() {
    const prices = this.products.map(p => toNum(p.price));
    return prices.reduce((acc, num) => acc + num, 0);
  }
  get costDiscount() {
    const prices = this.products.map(p => toNum(p.priceDiscount));
    return prices.reduce((acc, num) => acc + num, 0);
  }
  get discount() {
    return this.cost - this.costDiscount;
  }
}

const cartNum = document.querySelector('#cart_num');
const cartBtn = document.querySelector('#cart');
const popup = document.querySelector('.popup');
const popupClose = document.querySelector('#popup_close');
const body = document.body;
const popupProductList = document.querySelector('#popup_product_list');
const popupCost = document.querySelector('#popup_cost');
const popupDiscount = document.querySelector('#popup_discount');
const popupCostDiscount = document.querySelector('#popup_cost_discount');

const myCart = new Cart();
if (localStorage.getItem('cart') == null) {
  localStorage.setItem('cart', JSON.stringify(myCart));
}
const savedCart = JSON.parse(localStorage.getItem('cart'));
myCart.products = savedCart.products || [];
if (cartNum) cartNum.textContent = myCart.count;

if (cartBtn) {
  cartBtn.addEventListener('click', e => {
    e.preventDefault();
    popupContainerFill();
    popup.classList.add('popup--open');
    body.classList.add('lock');
  });
}
if (popupClose) {
  popupClose.addEventListener('click', e => {
    e.preventDefault();
    popup.classList.remove('popup--open');
    body.classList.remove('lock');
  });
}

// use event delegation so dynamically created cards work as well
document.addEventListener('click', e => {
  const addBtn = e.target.closest('.card__add');
  if (!addBtn) return;
  e.preventDefault();
  const card = addBtn.closest('.card');
  if (!card) return;
  const product = new Product(card);
  const saved = JSON.parse(localStorage.getItem('cart'));
  myCart.products = saved.products || [];
  myCart.addProduct(product);
  localStorage.setItem('cart', JSON.stringify(myCart));
  if (cartNum) cartNum.textContent = myCart.count;
  if (popup && popup.classList.contains('popup--open')) {
    popupContainerFill();
  }
});


function popupContainerFill() {
  if (!popupProductList) return;
  popupProductList.innerHTML = '';
  const saved = JSON.parse(localStorage.getItem('cart'));
  myCart.products = saved.products || [];
  const productsHTML = myCart.products.map((product, index) => {
    const productItem = document.createElement('div');
    productItem.classList.add('popup__product');
    const productWrap1 = document.createElement('div');
    productWrap1.classList.add('popup__product-wrap');
    const productWrap2 = document.createElement('div');
    productWrap2.classList.add('popup__product-wrap');
    const productImage = document.createElement('img');
    productImage.classList.add('popup__product-image');
    productImage.setAttribute('src', product.imageSrc);
    const productTitle = document.createElement('h2');
    productTitle.classList.add('popup__product-title');
    productTitle.innerHTML = product.name;
    const productPrice = document.createElement('div');
    productPrice.classList.add('popup__product-price');
    productPrice.innerHTML = toCurrency(toNum(product.priceDiscount));
    const productDelete = document.createElement('button');
    productDelete.classList.add('popup__product-delete');
    productDelete.innerHTML = '✕';
    productDelete.addEventListener('click', () => {
      myCart.removeProduct(index);
      localStorage.setItem('cart', JSON.stringify(myCart));
      popupContainerFill();
      if (cartNum) cartNum.textContent = myCart.count;
    });
    productWrap1.appendChild(productImage);
    productWrap1.appendChild(productTitle);
    productWrap2.appendChild(productPrice);
    productWrap2.appendChild(productDelete);
    productItem.appendChild(productWrap1);
    productItem.appendChild(productWrap2);
    return productItem;
  });
  productsHTML.forEach(el => popupProductList.appendChild(el));
  if (popupCost) popupCost.value = toCurrency(myCart.cost);
  if (popupDiscount) popupDiscount.value = toCurrency(myCart.discount);
  if (popupCostDiscount) popupCostDiscount.value = toCurrency(myCart.costDiscount);
}
