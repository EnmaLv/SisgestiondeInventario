document.addEventListener('DOMContentLoaded', function(){
  // AOS init
  if(window.AOS) AOS.init({duration:800,once:true});

  // Mobile menu toggle
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const nav = document.querySelector('.main-nav');
  mobileBtn && mobileBtn.addEventListener('click', ()=>{
    if(nav.style.display === 'block') nav.style.display = '';
    else nav.style.display = 'block';
  });

  // Initialize Swiper slider for testimonials
  if(window.Swiper){
    const swiper = new Swiper('.mySwiper', {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      speed: 700,
      centeredSlides: true,
      autoHeight: false,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      autoplay: { delay: 4500, disableOnInteraction: false },
    });
  }

  // Smooth scroll for all internal links (adjust for sticky header)
  (function(){
    const headerEl = document.querySelector('.site-header');
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link=>{
      link.addEventListener('click', function(e){
        const href = this.getAttribute('href');
        if(!href || href === '#') return;
        const targetId = href.slice(1);
        const target = document.getElementById(targetId);
        if(!target) return;
        e.preventDefault();
        const headerH = headerEl ? headerEl.offsetHeight : 0;
        const targetTop = target.getBoundingClientRect().top + window.pageYOffset - headerH - 12;
        window.scrollTo({ top: targetTop, behavior: 'smooth' });
        // close mobile nav when a link is clicked
        if(window.innerWidth < 900 && nav) nav.style.display = '';
      });
    });
  })();

  // Simple hero background lazy load: set background image from css attribute if not set inline
  const hero = document.querySelector('.hero');
  if(hero){
    // If hero has style attribute from blade with URL it will remain. Otherwise set a fallback image.
    const hasBg = hero.style.backgroundImage && hero.style.backgroundImage !== 'none';
    if(!hasBg){
      hero.style.backgroundImage = "url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1600&q=60')";
      hero.style.backgroundSize = 'cover';
      hero.style.backgroundPosition = 'center';
    }
  }

});