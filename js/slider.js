let slideIndex = 1;

function showSlides(n) {
  const slides = document.getElementsByClassName('slide-item');
  if (slides.length === 0) return;
  if (n > slides.length) { slideIndex = 1; }
  if (n < 1) { slideIndex = slides.length; }
  for (const slide of slides) {
    slide.style.display = 'none';
  }
  slides[slideIndex - 1].style.display = 'block';
}

function nextSlide() {
  showSlides(slideIndex += 1);
}

function previousSlide() {
  showSlides(slideIndex -= 1);
}

document.addEventListener('DOMContentLoaded', () => showSlides(slideIndex));
